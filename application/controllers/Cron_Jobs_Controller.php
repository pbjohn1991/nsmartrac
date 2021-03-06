<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron_Jobs_Controller extends MY_Controller
{

    private $timesheet_report_timezone = "UTC";
    private $timesheet_report_timezone_id = 0;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('timesheet_model');
    }
    public function get_time_sheet_storage($company_id, $timezone, $timesheet_report_timezone_id)
    {
        $this->timesheet_report_timezone = $timezone;
        $this->timesheet_report_timezone_id = $timesheet_report_timezone_id;
        $date_from = date("Y-m-d", strtotime('sunday last week', strtotime(date('Y-m-d'))));
        $date_to = date("Y-m-d", strtotime('saturday this week', strtotime(date('Y-m-d'))));
        $filename = $date_from . " to " . $date_to . ' ' . $company_id . ' ' . $timesheet_report_timezone_id . '.csv';
        $filename_pdf = $date_from . " to " . $date_to . ' ' . $company_id . ' ' . $timesheet_report_timezone_id . '.pdf';
        $time_sheet_storage = $this->generate_timelogs($date_from, $date_to, $filename, $company_id);
        return array($filename, $date_from, $time_sheet_storage, $filename_pdf);
    }
    public function timelogs_csv_email_sender($receiver, $company_name, $filename, $date_from, $FName, $business_name, $file_info, $company_id, $company_logo)
    {
        $server = MAIL_SERVER;
        $port = MAIL_PORT;
        $username = MAIL_USERNAME;
        $password = MAIL_PASSWORD;
        $from = MAIL_FROM;
        $subject = 'nSmarTrac: Time logs for Week ' . date("M d",strtotime($date_from));

        include APPPATH . 'libraries/PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->getSMTPInstance()->Timelimit = 5;
        $mail->Host = $server;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = 'ssl';
        $mail->Timeout = 10; // seconds
        $mail->Port = $port;
        $mail->From = $from;
        $mail->FromName = 'nSmarTrac';
        $mail->Subject = $subject;

        //get job data

        $this->page_data['company_name'] = $company_name;
        $this->page_data['date_from'] = $date_from;
        $this->page_data['date_to'] = date("Y-m-d", strtotime('saturday this week', strtotime(date('Y-m-d'))));
        $this->page_data['business_name'] = $business_name;
        $this->page_data['FName'] = $FName;
        $this->page_data['file_info'] = $file_info;
        $this->page_data['file_link'] = base_url() . '/timesheet/timelogs/' . $filename;
        $this->page_data['has_logo'] = false;
        $mail->IsHTML(true);
        $mail->AddEmbeddedImage(dirname(__DIR__, 2) . '/assets/dashboard/images/logo.png', 'logo_2u', 'logo.png');
        $filePath = base_url() . '/uploads/users/business_profile/'.$company_id.'/'.$company_logo;
        if (@getimagesize($filePath)) {
            $mail->AddEmbeddedImage(dirname(__DIR__, 2) . '/uploads/users/business_profile/'.$company_id.'/'.$company_logo, 'company_logo', $company_logo);
            $this->page_data['has_logo'] = true;
            
        }

        $mail->Body =  'Timesheet Report.';
        $content = $this->load->view('users/timesheet/emails/weekly_timelogs_report', $this->page_data, TRUE);
        $mail->MsgHTML($content);
        $mail->addAddress($receiver);
        $mail->Send();
    }
    public function send_timesheet_logs_email_action()
    {
        $busnesses = $this->timesheet_model->get_all_businesses();
        foreach ($busnesses as $business) {
            $email_sent_to = array();
            $timezone = "UTC";
            $timezone_id = 0;
            $user_ids = $this->timesheet_model->get_user_id_by_email_and_company_id($business->business_name, $business->company_id);

            foreach ($user_ids as $ids) {
                $user_id = $ids->id;
            }
            if (count($user_ids) > 0) {
                $saved_timezones = $this->timesheet_model->get_saved_timezone($user_id);
                foreach ($saved_timezones as $tz) {
                    $timezone = $tz->id_of_timezone;
                    $timezone_id = $tz->timezone_id;
                }
            }
            $file_info = $this->get_time_sheet_storage($business->company_id, $timezone, $timezone_id);
            if (count($file_info[2]) > 0) {
                $date_from = date("Y-m-d", strtotime('sunday last week', strtotime(date('Y-m-d'))));
                $date_to = date("Y-m-d", strtotime('saturday this week', strtotime(date('Y-m-d'))));
                $this->generate_timelogs_csv($file_info[2], $file_info[0]);
                $this->generate_weekly_timesheet_pdf_report($file_info, $business->business_name);
                // $this->timelogs_csv_email_sender($business->email, $business->business_name . "", $file_info[0], $date_from, $business->FName, $business->business_name, $file_info);
                $email_sent_to[] = array($business->business_email, $business->business_name);
                $busness_admins = $this->timesheet_model->get_all_business_admins($business->company_id);
                foreach ($busness_admins as $admin) {
                    $received = false;
                    // for ($i = 0; $i < count($email_sent_to); $i++) {
                    //     if ($email_sent_to[$i][0] == $admin->email) {
                    //         $received = true;
                    //         break;
                    //     }
                    // }
                    if (!$received) {
                        $timezone = "UTC";
                        $timezone_id = 0;
                        $subscribed = false;
                        $saved_timezones = $this->timesheet_model->get_saved_timezone($admin->id);
                        foreach ($saved_timezones as $tz) {
                            $timezone = $tz->id_of_timezone;
                            $timezone_id = $tz->timezone_id;
                            if($tz->subscribed == 1){
                                $subscribed = true;
                            }
                        }
                        if(count($saved_timezones) == 0){
                            $subscribed = true;
                        }
                        $file_info = $this->get_time_sheet_storage($business->company_id, $timezone, $timezone_id);
                        $this->generate_timelogs_csv($file_info[2], $file_info[0]);
                        $this->generate_weekly_timesheet_pdf_report($file_info, $business->business_name);
                        if($subscribed){
                            $this->timelogs_csv_email_sender($admin->email, $business->business_name . "", $file_info[0], $date_from, $admin->FName, $business->business_name, $file_info,$business->company_id,$business->business_image);
                        }
                        var_dump($subscribed);
                    $email_sent_to[] = array($admin->email, $business->business_name);
                    }
                }
            }
        }
    }
    public function cronjob_tester()
    {
        $busnesses = $this->timesheet_model->get_all_businesses();
        foreach ($busnesses as $business) {
            $email_sent_to = array();
            $timezone = "UTC";
            $timezone_id = 0;
            $user_ids = $this->timesheet_model->get_user_id_by_email_and_company_id($business->business_name, $business->company_id);

            foreach ($user_ids as $ids) {
                $user_id = $ids->id;
            }
            if (count($user_ids) > 0) {
                $saved_timezones = $this->timesheet_model->get_saved_timezone($user_id);
                foreach ($saved_timezones as $tz) {
                    $timezone = $tz->id_of_timezone;
                    $timezone_id = $tz->timezone_id;
                }
            }
            $file_info = $this->get_time_sheet_storage($business->company_id, $timezone, $timezone_id);
            if (count($file_info[2]) > 0) {
                $date_from = date("Y-m-d", strtotime('sunday last week', strtotime(date('Y-m-d'))));
                $date_to = date("Y-m-d", strtotime('saturday this week', strtotime(date('Y-m-d'))));
                $this->generate_timelogs_csv($file_info[2], $file_info[0]);
                $this->generate_weekly_timesheet_pdf_report($file_info, $business->business_name);
                // $this->timelogs_csv_email_sender($business->email, $business->business_name . "", $file_info[0], $date_from, $business->FName, $business->business_name, $file_info);
                $email_sent_to[] = array($business->business_email, $business->business_name);
                $busness_admins = $this->timesheet_model->get_all_business_admins($business->company_id);
                foreach ($busness_admins as $admin) {
                    $received = false;
                    // for ($i = 0; $i < count($email_sent_to); $i++) {
                    //     if ($email_sent_to[$i][0] == $admin->email) {
                    //         $received = true;
                    //         break;
                    //     }
                    // }
                    if (!$received) {
                        $timezone = "UTC";
                        $timezone_id = 0;
                        $subscribed = false;
                        $saved_timezones = $this->timesheet_model->get_saved_timezone($admin->id);
                        foreach ($saved_timezones as $tz) {
                            $timezone = $tz->id_of_timezone;
                            $timezone_id = $tz->timezone_id;
                            if($tz->subscribed == 1){
                                $subscribed = true;
                            }
                        }
                        if(count($saved_timezones) == 0){
                            $subscribed = true;
                        }
                        $file_info = $this->get_time_sheet_storage($business->company_id, $timezone, $timezone_id);
                        $this->generate_timelogs_csv($file_info[2], $file_info[0]);
                        $this->generate_weekly_timesheet_pdf_report($file_info, $business->business_name);
                        if($subscribed){
                            if ($admin->email == "pintonlou@gmail.com") {
                                $this->timelogs_csv_email_sender($admin->email, $business->business_name . " Tester", $file_info[0], $date_from, $admin->FName, $business->business_name, $file_info,$business->company_id,$business->business_image);
                                $this->timelogs_csv_email_sender("moresecureadi@gmail.com", $business->business_name . " Tester", $file_info[0], $date_from, "Tommy", $business->business_name, $file_info, $file_info,$business->company_id,$business->business_image);
                            
                            }
                            // $this->timelogs_csv_email_sender($admin->email, $business->business_name . "", $file_info[0], $date_from, $admin->FName, $business->business_name, $file_info);
                        }
                        var_dump($subscribed);
                        var_dump($saved_timezones);
                    $email_sent_to[] = array($admin->email, $business->business_name);
                    }
                }
            }
        }
    }
    public function generate_weekly_timesheet_pdf_report($file_info, $business_name)
    {
        $date_from = date("Y-m-d", strtotime('sunday last week', strtotime(date('Y-m-d'))));
        $date_to = date("Y-m-d", strtotime('saturday this week', strtotime(date('Y-m-d'))));
        $this->page_data['file_info'] = $file_info;
        $this->page_data['date_from'] = $date_from;
        $this->page_data['date_to'] = $date_to;
        $this->page_data['business_name'] = $business_name;
        $this->page_data['timesheet_report_timezone'] = $this->timesheet_report_timezone;
        $content = $this->load->view('users/timesheet/emails/html_to_pdf_weekly_report', $this->page_data, TRUE);
        $this->load->library('Reportpdf');
        $title = 'Timesheet Weekly Report for Pay Period ' . date('M d', strtotime($date_from)) . ' - ' . date('d');
        $obj_pdf = new Reportpdf('L', 'mm', 'A4', true, 'UTF-8', false);
        $obj_pdf->SetTitle($title);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $obj_pdf->SetFont('courierI', '', 9);
        $obj_pdf->setFontSubsetting(false);
        $res = copy(dirname(__DIR__, 2) . '/assets/img/timesheet/logojpg.jpg', dirname(__DIR__, 2) . '/assets/img/timesheet/' . 'logojpg2' . ".jpg");
        // echo 'image' . ($res ? '' : ' not') . ' created';

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $obj_pdf->AddPage('L');
        $html = '<div style="text-align:center">
        <img src="' . dirname(__DIR__, 2) . '/assets/img/timesheet/logojpg2.jpg' . '" alt="test alt attribute" width="300" height="60" border="0" /></div>';
        $obj_pdf->writeHTML($content, true, false, true, false, '');
        ob_clean();
        $obj_pdf->lastPage();
        // $obj_pdf->Output($title, 'I');
        $obj_pdf->Output(dirname(__DIR__, 2) . '/timesheet/timelogs/' . $file_info[3], 'F');
    }
    public function generate_timelogs_csv($timehseet_storage, $filename)
    { // file name 
        $file = fopen(APPPATH . '../timesheet/timelogs/' . $filename, 'wb');
        $header = array(
            "Employee",
            "Date (" . $this->timesheet_report_timezone . ")",
            "Role",
            "Wage",
            "Time Card (" . $this->timesheet_report_timezone . ")",
            "Act.& Sched Diff.",
            "Total Paid",
            "Regular",
            "Unpaid Breaks",
            "Overtime",
            "Est. Wages",
            "Notes",
        );
        // $file = fopen('php://output', 'w');
        fputcsv($file, $header);

        $id_running = -1;
        $started = false;
        $table = '';
        $time_card_ctr = 0;
        $act_dif_total = 0;
        $total_paid = 0;
        $total_regular = 0;
        $total_overtime = 0;
        $total_wage = 0;
        $total_est_wage = 0;
        $overall_act_dif_total = 0;
        $overall_total_paid = 0;
        $overall_total_regular = 0;
        $overall_total_overtime = 0;
        $overall_total_wage = 0;
        $overall_total_est_wage = 0;
        $overall_time_card_ctr = 0;
        $name = '';
        for ($i = 0; $i < count($timehseet_storage); $i++) {
            $data = array();
            if ($id_running != $timehseet_storage[$i][0]) {
                if (!$started) {
                    $started = true;
                    $name =  $timehseet_storage[$i][1];
                } else {
                    $data[] = 'Total for ' . $timehseet_storage[$i - 1][1];
                    $data[] = '';
                    $data[] = '';
                    $data[] = $total_wage;
                    $data[] = $time_card_ctr . ($time_card_ctr > 1 ? ' Time cards' : ' Time card');
                    $data[] = $act_dif_total;
                    $data[] = $total_paid;
                    $data[] = $total_regular;
                    $data[] = 0.00;
                    $data[] = $total_overtime;
                    $data[] = $total_est_wage;
                    $data[] = '';
                    fputcsv($file, $data);
                    $data = array();
                }
                $time_card_ctr = 0;
                $act_dif_total = 0;
                $total_paid = 0;
                $total_regular = 0;
                $total_overtime = 0;
                $total_wage = 0;
                $total_est_wage = 0;
                $id_running = $timehseet_storage[$i][0];
            }
            $clockout = ($timehseet_storage[$i][7] == '' ? '' : date("M d h:i A", strtotime($timehseet_storage[$i][7])));
            if ($timehseet_storage[$i][7] == '') {
                $actual_vs_expected = '-';
                $expected = 8;
            } else {
                $actual_vs_expected = $timehseet_storage[$i][10] == '' ?  8 - round($timehseet_storage[$i][18], 2) . "" : "0.00";
            }
            $regular_hours = ($timehseet_storage[$i][12] == '' ? 8 : $timehseet_storage[$i][12]);
            $paid_hours = ($timehseet_storage[$i][17] == 'Approved' ? $timehseet_storage[$i][18] : round($regular_hours, 2));

            $est_wage = 0;
            if ($timehseet_storage[$i][21] == "hourly") {
                $est_wage = round($paid_hours * $timehseet_storage[$i][20], 2);
            } else {
                $est_wage = round(($timehseet_storage[$i][20] / $regular_hours) * $paid_hours, 2);
            }
            $data[] = $timehseet_storage[$i][1];
            $data[] = date("D M d", strtotime($timehseet_storage[$i][3]));
            $data[] = $timehseet_storage[$i][2];
            $data[] = $timehseet_storage[$i][20];
            $data[] = date("h:i A", strtotime($timehseet_storage[$i][6])) . ' - ' . ($timehseet_storage[$i][7] == '' ? 'No clockout' : date("h:i A", strtotime($timehseet_storage[$i][7])));
            $data[] = $actual_vs_expected;
            $data[] = $paid_hours;
            $data[] = $regular_hours;
            $data[] = 0.00;
            $data[] = ($timehseet_storage[$i][17] == 'Approved' ? $timehseet_storage[$i][16] : 0.00);
            $data[] = $est_wage;
            $data[] = $timehseet_storage[$i][19];
            fputcsv($file, $data);
            $data = array();
            $time_card_ctr++;
            $act_dif_total += $timehseet_storage[$i][7] == '' ? 0 : $actual_vs_expected;
            $total_regular += ($timehseet_storage[$i][12] == '' ? 8 : $timehseet_storage[$i][12]);
            $total_paid += $paid_hours;
            $total_overtime += ($timehseet_storage[$i][17] == 'Approved' ? $timehseet_storage[$i][16] : 0.00);
            $total_wage += $timehseet_storage[$i][20];
            $total_est_wage += $est_wage;
            $overall_act_dif_total += $timehseet_storage[$i][7] == '' ? 0 : $actual_vs_expected;
            $overall_total_regular += ($timehseet_storage[$i][12] == '' ? 8 : $timehseet_storage[$i][12]);
            $overall_total_paid += $paid_hours;
            $overall_total_overtime += ($timehseet_storage[$i][17] == 'Approved' ? $timehseet_storage[$i][16] : 0.00);
            $overall_total_wage += $timehseet_storage[$i][20];
            $overall_total_est_wage += $est_wage;
            $overall_time_card_ctr++;
            if ($i == count($timehseet_storage) - 1) {
                $data[] = 'Total for ' . $timehseet_storage[$i][1];
                $data[] = '';
                $data[] = '';
                $data[] = $total_wage;
                $data[] = $time_card_ctr . ($time_card_ctr > 1 ? ' Time cards' : ' Time card');
                $data[] = $act_dif_total;
                $data[] = $total_paid;
                $data[] = $total_regular;
                $data[] = 0.00;
                $data[] = $total_overtime;
                $data[] = $total_est_wage;
                $data[] = '';
                fputcsv($file, $data);
                $data = array();
            }
        }
        $data = array();
        $data[] = 'Total for this Pay Period';
        $data[] = '';
        $data[] = '';
        $data[] =  $overall_total_wage;
        $data[] = $overall_time_card_ctr . ($overall_time_card_ctr > 1 ? ' Time cards' : ' Time card');
        $data[] = $overall_act_dif_total;
        $data[] = $overall_total_paid;
        $data[] = $overall_total_regular;
        $data[] = 0.00;
        $data[] = $overall_total_overtime;
        $data[] = $overall_total_est_wage;
        $data[] = '';
        fputcsv($file, $data);
    }
    public function generate_timelogs($date_from, $date_to, $filename, $company_id)
    {
        $date_from = $this->datetime_zone_converter($date_from . " 00:00:00", $this->timesheet_report_timezone, "UTC");
        $date_to = $this->datetime_zone_converter($date_to . " 24:59:00", $this->timesheet_report_timezone, "UTC");

        $attendances = $this->timesheet_model->get_all_attendance($date_from, $date_to, $company_id);



        $time_sheet_storage = array();
        foreach ($attendances as $attendance) {
            $data = array();
            $shift_date = $this->datetime_zone_converter($attendance->date_created, "UTC", $this->timesheet_report_timezone);
            $data[] = $attendance->user_id; //0
            $data[] = $attendance->FName . ' ' .   $attendance->LName; //1
            $data[] = $attendance->title; //2
            $data[] = $shift_date; //3

            date_default_timezone_set("UTC");
            $shift_schedules = $this->timesheet_model->get_schedule_in_shift_date(date("Y-m-d", strtotime($attendance->date_created)), $attendance->user_id);
            $shift_start = '';
            $shift_end = '';
            $expected_hours = '';
            $expected_break = '';
            $expected_work_hours = '';
            foreach ($shift_schedules as $sched) {
                $shift_start = $this->datetime_zone_converter($sched->shift_start, "UTC", $this->timesheet_report_timezone);
                $shift_end = $this->datetime_zone_converter($sched->shift_end, "UTC", $this->timesheet_report_timezone);
                $expected_hours = $sched->duration;
                $expected_break = 0;
                if ($expected_hours > 4) {
                    $expected_break = 30;
                }
                if ($expected_hours > 6) {
                    $expected_break += 15;
                }
                if ($expected_hours >= 8) {
                    $expected_break = 60;
                }
                $expected_work_hours = round((($expected_hours * 60) - $expected_break) / 60, 2);
            }

            $data[] = $shift_start; //4
            $data[] = $shift_end; //5



            $auxes = $this->timesheet_model->get_logs_of_attendance($attendance->id);
            $checkin = '';
            $checkout = '';
            $breakin = '';
            $breakout = '';

            foreach ($auxes as $aux) {
                $newdate = $this->datetime_zone_converter($aux->date_created, "UTC", $this->timesheet_report_timezone);
                if ($aux->action == "Check in") {
                    $checkin = $newdate;
                } elseif ($aux->action == "Check out") {
                    $checkout = $newdate;
                } elseif ($aux->action == "Break in") {
                    $breakin = $newdate;
                } elseif ($aux->action == "Break out") {
                    $breakout = $newdate;
                }
            }
            $pay_rate = 0;
            $pay_type = '';
            $employee_pay_details = $this->timesheet_model->get_employee_pay_details($attendance->user_id);
            foreach ($employee_pay_details as $pay_detail) {
                if ($expected_work_hours == '') {
                    $expected_work_hours = $pay_detail->hours_per_day;
                }
                $pay_rate = $pay_detail->pay_rate;
                $pay_type = $pay_detail->pay_type;
            }
            $data[] = $checkin; //6
            $data[] = $checkout; //7
            $data[] = $breakin; //8
            $data[] = $breakout; //9
            $data[] = $expected_hours; //10
            $data[] = $expected_break; //11
            $data[] = $expected_work_hours; //12
            $data[] =  ($attendance->shift_duration + $attendance->overtime); //13

            $data[] = $attendance->break_duration; //14

            $minutes_late = "";
            if ($shift_start != '') {
                $minutes_late = $this->get_differenct_of_dates($shift_start, $checkin) * 60;
            }
            $data[] = round($minutes_late, 2); //15
            $overtime = 0;
            if ($expected_hours != '') {
                if ($expected_work_hours < ($attendance->shift_duration + $attendance->overtime)) {
                    $overtime = round(($attendance->shift_duration + $attendance->overtime) - $expected_work_hours, 2);
                } elseif ($attendance->shift_duration == 0) {
                    $overtime = 0;
                } else {
                    $overtime = $expected_work_hours;
                }
            } else {
                $overtime = $attendance->overtime;
            }
            $data[] = $overtime; //16
            if ($attendance->overtime_status == 1) {
                $ot_status = "Pending";
            } elseif ($attendance->overtime_status == 0) {
                $ot_status = "Denied";
            } else {
                $ot_status = "Approved";
            }
            $data[] = $ot_status; //17
            $payable_hours = $attendance->shift_duration;
            if ($expected_hours != '') {
                if ($payable_hours > $expected_work_hours) {
                    $payable_hours = $expected_work_hours;
                }
            }
            if ($ot_status == "Approved") {
                $payable_hours = $payable_hours + $attendance->overtime;
            }

            $data[] = $payable_hours; //18
            $data[] = $attendance->notes; //19
            $data[] = $pay_rate; //20
            $data[] = $pay_type; //21
            $time_sheet_storage[] = $data;
        }
        return $time_sheet_storage;
    }

    public function get_differenct_of_dates($date_start, $date_end)
    {
        $start = new DateTime($date_start);
        $end =  new DateTime($date_end);
        $interval = $start->diff($end);

        $difference = ($interval->days * 24 * 60) * 60;
        $difference += ($interval->h * 60) * 60;
        $difference += ($interval->i) * 60;
        $difference += $interval->s;
        return ($difference / 60) / 60;
    }
    public function datetime_zone_converter($olddate, $from_timezone, $to_timezone)
    {
        date_default_timezone_set($from_timezone);
        $the_date = strtotime($olddate);
        date_default_timezone_set($to_timezone);
        $newdate = date("Y-m-d H:i:s", $the_date);
        return $newdate;
    }
    public function index()
    {
        add_css(array(
            "assets/css/trac360/people.css"
        ));
        $company_id = logged('company_id');
        $user_id = logged('id');

        $user_locations = $this->trac360_model->get_current_user_location($company_id);
        $locations = array();
        foreach ($user_locations as $user) {
            $found = false;
            $found_ctr = 0;
            $current_user_location = "";
            if ($user->last_tracked_location != "") {
                for ($i = 0; $i < count($locations); $i++) {
                    if ($locations[$i][0] == $user->last_tracked_location) {
                        $found = true;
                        $found_ctr = $i;
                        break;
                    }
                }
                if (!$found) {
                    $locations[] = array($user->last_tracked_location, array($user->name), $user->profile_img);
                } else {
                    $names = $locations[$found_ctr][1];
                    $names[] =  $user->name;
                    $locations[$found_ctr][1] = $names;
                }
                $current_user_location = $user->last_tracked_location;
                if ($user->user_id == $user_id) {
                    $current_user_location = $user->user_location;
                }
            } else {
                $last_loc = $this->trac360_model->get_last_location_from_timesheet_logs($user->user_id);
                for ($i = 0; $i < count($locations); $i++) {
                    if ($locations[$i][0] == $last_loc->user_location) {
                        $found = true;
                        $found_ctr = $i;
                        break;
                    }
                }
                if (!$found) {
                    $locations[] = array($last_loc->user_location, array($user->name), $user->profile_img,);
                } else {
                    $names = $locations[$found_ctr][1];
                    $names[] =  $user->name;
                    $locations[$found_ctr][1] = $names;
                }
                if ($user->user_id == $user_id) {
                    $current_user_location = $last_loc->user_location;
                }
            }
        }
    }
}
