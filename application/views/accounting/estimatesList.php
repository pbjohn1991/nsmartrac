<style>
button#dropdown-edit {
    width: 100px;
}
.dropdown-toggle::after {
    display: block;
    position: absolute;
    top: 54% !important;
    right: 9px !important;
}
.page-title, .box-title {
  font-family: Sarabun, sans-serif !important;
  font-size: 1.75rem !important;
  font-weight: 600 !important;
  padding-top: 5px;
}
.pr-b10 {
  position: relative;
  bottom: 10px;
}
.left {
  float: left;
}
.p-40 {
    padding-left: 25px !important;
    padding-top: 55px !important;
}
.card.p-20 {
    padding-top: 25px !important;
}
.col.col-4.pd-17.left.alert.alert-warning.mt-0.mb-2 {
    position: relative;
    left: 13px;
}
.fr-right {
  float: right;
  justify-content: flex-end;
}
.p-20 {
  padding-top: 25px !important;
  padding-bottom: 25px !important;
  padding-right: 20px !important;
  padding-left: 20px !important;
}
.pd-17 {
  position: relative;
  left: 17px;
}
@media only screen and (max-width: 600px) {
  .p-40 {
    padding-top: 0px !important;
  }
  .pr-b10 {
    position: relative;
    bottom: 0px;
  }
}
</style>
<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php include viewPath('includes/header'); ?>
<!-- page wrapper start -->
<div class="wrapper" role="wrapper">
<?php include viewPath('includes/sidebars/accounting/accounting'); ?>
    <?php include viewPath('includes/notifications'); ?>
    <div wrapper__section class="p-40">
        <?php include viewPath('includes/notifications'); ?>
        <div class="card p-20">
            <div class="container-fluid pt-0 pl-0" style="font-size:14px;">
                <div class="row">
                    <div class="col-sm-6 left">
                      <h3 class="page-title mt-0">Estimates</h3>
                    </div>
                    <div class="col-sm-6 right dashboard-container-1">
                      <div class="float-right d-none d-md-block">
                        <div class="col-auto">
                            <div class="h1-spacer">
                                 <?php if (isset($estimates) && count($estimates)>0) { ?>
                                                    <a class="btn btn-primary" href="<?php echo base_url('estimate/print') ?>">
                                                        <span class="fa fa-print "></span> Print
                                                    </a>
                                                <?php } ?>

                                <a class="btn btn-primary" data-toggle="modal" data-target="#newJobModal" href="<?php echo url('job/new_job') ?>">
                                    <span class="fa fa-plus"></span> New Estimate
                                </a>
                            </div>
                        </div>
                      </div>
                    </div>
                </div><br>
                <div class="col mb-4 left alert alert-warning mt-0 mb-2">
                    <span style="color:black;font-family: 'Open Sans',sans-serif !important;font-weight:300 !important;font-size: 14px;">Create an estimate when you want to give your customer a quote, bid, or proposal for work you plan to do. There are 3 forms of estimate:  standard, options and bundle (package)  The estimate can later be turned into a sales order or an invoice.  With this layout you will be able to monitor the status of each estimate.</span>
                </div>
                <br style="clear:both;"/><br/>
                <div class="align-items-center mb-4 margin-bottom-ter">
                    <div class="row pl-0 pr-0">
                      <div class="col col-4 ">
                      </div>
                      <div class="col col-8 fr-right text-right-sm d-flex align-items-center mb-fix-list">
                          <form style="display: inline;" class="form-inline form-search" name="form-search"
                                action="<?php echo base_url('estimate') ?>" method="get">
                              <div class="form-group m-0 mobile-form" style="margin:0 !important;">
                                  <span>Search:</span> &nbsp;<input class="form-control form-control-md"
                                                                    name="search"
                                                                    value="<?php echo (!empty($search)) ? $search : '' ?>"
                                                                    type="text"
                                                                    placeholder="Search..."
                                                                    style="border-width: 1px;height: 38px !important;margin-right: 8px;">
                                  <button class="btn btn-default btn-md" type="submit">
                                      <span class="fa fa-search"></span>
                                  </button>
                                  <?php if (!empty($search)) { ?>
                                      <a class="btn btn-default btn-md ml-2"
                                         href="<?php echo base_url('estimate') ?>"><span
                                                  class="fa fa-times"></span></a>
                                  <?php } ?>
                              </div>
                          </form>
                          <span class="margin-left-sec sc-2 mobile-mt-2">Sort:</span> &nbsp;
                          <div class="dropdown dropdown-inline open sc-2"><a class="btn btn-default dropdown-toggle mobile-mt-2"
                                                                        data-toggle="dropdown" aria-expanded="true"
                                                                        href="<?php echo base_url('estimate') ?>?order=added-desc">Newest
                                  first <span class="caret"></span></a>
                              <ul class="dropdown-menu  btn-block" role="menu">
                                  <li class="active" role="presentation"><a role="menuitem" tabindex="-1"
                                                                            href="<?php echo base_url('estimate') ?>?order=added-desc">Newest
                                          first</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=added-asc">Oldest
                                          first</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=date-accepted-desc">Accepted:
                                          newest</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=date-accepted-asc">Accepted:
                                          oldest</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=number-asc">Number:
                                          Asc</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=number-desc">Number:
                                          Desc</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=amount-asc">Amount:
                                          Lowest</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1"
                                                             href="<?php echo base_url('estimate') ?>?order=amount-desc">Amount:
                                          Highest</a></li>
                              </ul>
                          </div>
                      </div>
                    </div>
                    <div></div>
                </div>

                <div class="tabs">
                    <ul class="clearfix work__order ul-mobile" id="myTab" role="tablist">
                        <?php foreach (get_config_item('estimate_status') as $key => $status) { ?>

                            <?php if ($key === 0) continue; ?>

                            <?php if ($key === 1) { ?>
                                <li <?php echo (empty($tab)) ? 'class="active"' : '' ?>>
                                    <a class="nav-link active"
                                       href="<?php echo base_url('estimate') ?>"
                                       aria-controls="tab1" aria-selected="true">All
                                        (<?php echo get_estimate_status_total(0, true, $role) ?>)</a>
                                </li>
                            <?php } ?>
                            <li <?php echo ((!empty($tab)) && strtolower($status) === $tab) ? "class='active'" : "" ?>>
                                <a class="nav-link"
                                   id="profile-tab"
                                   data-toggle="tab<?php echo $key ?>"
                                   href="<?php echo base_url('estimate/tab/' . strtolower($status)) ?>"
                                   role="tab"
                                   aria-controls="profile" aria-selected="false"><?php echo $status ?>
                                    (<?php echo get_estimate_status_total($status, true, $role) ?>)</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1">

                        <?php if (!empty($estimates)) { ?>
                            <table class="table table-hover table-to-list" data-id="work_orders">
                                <thead>
                                <tr>
                                    <th>Estinate#</th>
                                    <th>Date</th>
                                    <th>Job & Customer</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php foreach ($estimates as $estimate) { ?>
                                    <tr>
                                        <td>
                                            <a class="a-default"
                                               href="#">
                                                <?php echo $estimate->estimate_number; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="table-nowrap">
                                                <?php echo date('M d, Y', strtotime($estimate->estimate_date)) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div><a href="#"><?php echo $estimate->job_name; ?></a></div>
                                            <a href="<?php echo base_url('customer/view/' . $estimate->customer_id) ?>">
                                                <?php echo get_customer_by_id($estimate->customer_id)->contact_name ?>
                                            </a>
                                        </td>
                                        <td>
                                          <?php 
                                            if( $estimate->is_mail_open == 1 ){
                                              echo "<i class='fa fa-eye'></i>  ";
                                            }
                                            echo $estimate->status;                                              
                                          ?>
                                          
                                        </td>
                                        <td>
                                            <?php if (is_serialized($estimate->estimate_eqpt_cost)) { ?>
                                                $<?php echo unserialize($estimate->estimate_eqpt_cost)['eqpt_cost'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-btn">
                                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdown-edit" data-toggle="dropdown" aria-expanded="true">
                                                    <span class="btn-label">Manage</span><span class="caret-holder"><span class="caret"></span></span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdown-edit">
                                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                               href="#" est-id="<?php echo $estimate->id;?>" class="viewEstimate"><span
                                                                    class="fa fa-file-text-o icon"></span> View Estimate</a></li>
                                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                               href="<?php echo base_url('accounting/updateEstimate/' . $estimate->id) ?>"><span
                                                                    class="fa fa-pencil-square-o icon"></span> Edit</a>
                                                    </li>
                                                    <li role="separator" class="divider"></li>
                                                    <li role="presentation"><a role="menuitem"
                                                                               tabindex="-1"
                                                                               href="#"
                                                                               data-toggle="modal"
                                                                               data-target="#modalCloneWorkorder"
                                                                               data-id="<?php echo $estimate->id ?>"
                                                                               data-name="WO-00433"><span
                                                                    class="fa fa-files-o icon clone-workorder">

                                                        </span> Clone Estimate</a>
                                                    </li>
                                                    <li role="presentation"><a role="menuitem" tabindex="-1"
                                                                               href="<?php echo base_url('invoice') ?>"
                                                                               data-convert-to-invoice-modal="open"
                                                                               data-id="161983"
                                                                               data-name="WO-00433"><span
                                                                    class="fa fa-money icon"></span> Convert to Invoice</a>
                                                    </li>
                                                    <li role="presentation">
                                                        <a role="menuitem" href="<?php echo base_url('estimate/view_pdf/' . $estimate->id) ?>" class="">
                                                        <span class="fa fa-file-pdf-o icon"></span>  View PDF</a></li>
                                                    <li role="presentation">
                                                        <a role="menuitem" target="_new" href="<?php echo base_url('estimate/print/' . $estimate->id) ?>" class="">
                                                        <span class="fa fa-print icon"></span>  Print</a></li>
                                                    <li role="presentation">
                                                        <a role="menuitem" href="javascript:void(0);" class="btn-send-customer" data-id="<?= $estimate->id; ?>">
                                                        <span class="fa fa-envelope-open-o icon"></span>  Send to Customer</a></li>
                                                    <li><div class="dropdown-divider"></div></li>
                                                    <li role="presentation">
                                                        <a role="menuitem" href="<?php echo base_url('estimate/delete/' . $estimate->id) ?>>" onclick="return confirm('Do you really want to delete this item ?')" data-delete-modal="open"><span class="fa fa-trash-o icon"></span> Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                                </tbody>

                            </table>
                        <?php } else { ?>
                            <div class="page-empty-container">
                                You have no estimates.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="estimateview" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Estimate details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                    <div class="d-block">
                        <div class="col-md-12" style="text-align: right;margin-bottom: 60px;">
                          <a class="btn btn-success" href="<?php echo base_url('estimate/send_customer/' . $estimate->id) ?>"><span class="fa fa-envelope-open-o icon"></span> SEND TO CUSTOMER</a>
                          <a class="btn btn-info" href="<?php echo base_url('estimate/edit/' . $estimate->id) ?>"><span class="fa fa-pencil icon"></span> EDIT</a>
                          <a class="btn btn-info" target="_new" href="<?php echo base_url('estimate/view_pdf/' . $estimate->id) ?>"><span class="fa fa-file-pdf-o icon"></span> PDF</a>
                          <!-- <a class="btn btn-info" href="<?php echo base_url('estimate/') ?>">BACK TO ESTIMATE LIST</a> -->
                        </div>
                        <div class="col-xl-5 left" style="margin-bottom: 33px;">
                          <h5><span class="fa fa-user-o fa-margin-right"></span> From <span class="invoice-txt business_name"></span></h5>
                          <div class="col-xl-5 ml-0 pl-0">
                            ADDRESS: <span class="from_address"></span><br />
                            EMAIL: <span class="from_email"></span><br />
                            PHONE: <span class="from_phone"></span>
                          </div>
                        </div>
                        <div class="col-xl-5 right" style="float: right">
                          <div style="text-align: right;">
                            <h5 style="font-size:30px;margin:0px;">ESTIMATE</h5>
                            <small style="font-size: 14px;">#<span class="estimate_no"></span></small>
                          </div>
                          <div class="" style="text-align: right;margin-top: 20px;">
                            <table style="width: 100%;text-align: right;">
                              <tr>
                                <td style="text-align: right;width: 70%;">Estimate Date :</td>
                                <td><span class="estimate_date"></span></td>
                              </tr>
                              <tr>
                                <td style="text-align: right;width: 70%;">Expiry Date :</td>
                                <td><span class="expiry_date"></span></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <div class="clear"></div>
                        <div class="col-xl-5 left">
                          <h5><span class="fa fa-user-o fa-margin-right"></span> To <span class="invoice-txt"></span></h5> 
                          <div class="col-xl-5 ml-0 pl-0">
                          ADDRESS: <span class="to_address"></span><br />
                          EMAIL: <span class="to_email"></span></span><br />
                          PHONE: <span class="to_phone"></span></span><br />
                          </div>
                        </div>
                        <br class="clear"/>    
                        <table class="table-print table-items" style="width: 100%; border-collapse: collapse;margin-top: 55px;">
                        <thead>
                            <tr>
                                <th style="background: #f4f4f4; text-align: center; padding: 5px 0;">#</th>
                                <th style="background: #f4f4f4; text-align: left; padding: 5px 0;">Items</th>
                                <th style="background: #f4f4f4; text-align: left; padding: 5px 0;">Item Type</th>
                                <th style="background: #f4f4f4; text-align: right; padding: 5px 0;">Qty</th>
                                <th style="background: #f4f4f4; text-align: right; padding: 5px 0;">Discount</th>
                                <th style="background: #f4f4f4; text-align: right; padding: 5px 8px 5px 0;" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr><td colspan="6"><hr/></td></tr>
                        <div id="tableEstimate"></div>
                        <tr>
                          <td colspan="5" style="text-align: right;"><b>TOTAL AMOUNT</b></td>
                          <td style="text-align: right;"><b>$</b></td>
                        </tr>
                      </tbody>
                      </table>
                      </div>
                      <hr />
                      <p><b>Instructions</b><br /><br /> <span class="instructions"></span></p>
                      <p><b>Message</b><br /><br /> <span class="message"></span></p>
                      <p><b>Terms</b><br /><Br /> <span class="terms"></span></p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

     <!-- Modal Send Email  -->
        <div class="modal fade bd-example-modal-md" id="modalSendEmail" tabindex="-1" role="dialog" aria-labelledby="modalSendEmailTitle" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-envelope-open-o"></i> Send to Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php echo form_open_multipart('estimate/_send_customer', ['class' => 'form-validate', 'autocomplete' => 'off' ]); ?>
              <?php echo form_input(array('name' => 'eid', 'type' => 'hidden', 'value' => '', 'id' => 'eid'));?>
              <div class="modal-body">
                  <p>Are you sure you want to send the selected estimate to customer?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-info">Yes</button>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>

    <!-- end container-fluid -->
</div>

<!-- CONVERT ESTIMATE MODAL -->
<div class="modal fade" id="modalConvertEstimate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Convert Estimate To Work Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="convert-to-work-order-modal-form">
                    <p>
                        You are going create a new work order based on <b>Estimate# <span
                                    id="estimateCustomNumber"></span></b>.<br>
                        The estimate items (e.g. materials, labour) will be copied to this work order.<br>
                        You can always edit/delete work order items as you need.
                    </p>
                    <!-- <div class="checkbox checkbox-sec">
                      <input type="checkbox" name="copy_attachment" value="1" checked="checked" id="ctwo_copy_attachment">
                      <label for="ctwo_copy_attachment"><span>Copy estimate attachments to work order</span></label>
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" data-link="<?php echo base_url('workorder/add/?estimate_id=' . $estimate->id) ?>"
                        class="btn btn-primary" id="button_convert_estimate">Convert To Work Order
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="newJobModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Estimate</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p class="text-lg margin-bottom">
            What type of estimate you want to create
        </p><center>
        <div class="margin-bottom text-center" style="width:60%;">
            <div class="help help-sm">Create a regular estimate with items</div>
            <a class="btn btn-primary add-modal__btn-success" style="background-color: #2ab363 !important" href="<?php echo base_url('estimate/add') ?>"><span class="fa fa-file-text-o"></span> Standard Estimate</a>
        </div>
        <div class="margin-bottom" style="width:60%;">
            <div class="help help-sm">Customers can select all <br>or only certain options</div>
            <a class="btn btn-primary add-modal__btn-success" style="background-color: #2ab363 !important" href="<?php echo base_url('estimate/addoptions?type=2') ?>"><span class="fa fa-list-ul fa-margin-right"></span> Options Estimate</a>
        </div>
        <div  class="margin-bottom" style="width:60%;">
            <div class="help help-sm">Customers can select both Bundle Packages to obtain an overall discount</div>
            <a class="btn btn-primary add-modal__btn-success" style="background-color: #2ab363 !important" href="<?php echo base_url('estimate/addbundle?type=3') ?>"><span class="fa fa-cubes"></span> Bundle Estimate</a>
        </div></center>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
<style>
    .hid-deskx {
        display: none !important;
    }


    @media only screen and (max-width: 600px) {
        .hid-desk {
            display: none !important;
        }

        .hid-deskx {
            display: block !important;
        }
    }
</style>
<!-- page wrapper end -->
<?php include viewPath('includes/footer_accounting'); ?>
<?php include viewPath('accounting/workorder_modal'); ?>
<?php include viewPath('accounting/estimate_one_modal'); ?>
<script>
    $('#dataTable1').DataTable({

        "ordering": false
    });

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function (html) {
        var switchery = new Switchery(html, {
            size: 'small'
        });
    });

    window.updateUserStatus = (id, status) => {
        $.get('<?php echo url('company/change_status') ?>/' + id, {
            status: status
        }, (data, status) => {
            if (data == 'done') {
                // code
            } else {
                alert('Unable to change Status ! Try Again');
            }
        })
    }

    $(document).ready(function () {

        $(".btn-send-customer").click(function(e){
            var eid = $(this).attr("data-id");
            $("#eid").val(eid);
            $("#modalSendEmail").modal('show');
        });

        // open service address form
        $('#modalConvertEstimate').on('shown.bs.modal', function (e) {

            var element = $(this);

            var estimate_id = $(e.relatedTarget).attr('data-estimate-id');

            $(this).find('#estimateCustomNumber').html(estimate_id);

        });

        $(document).on('click', '#button_convert_estimate', function (e) {

            e.preventDefault();

            location.href = $(this).attr('data-link');
        });
    });
</script>

<script>
    $('.viewEstimate').each(function(e){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
        // e.preventDefault();
        $(this).on('click', function(){
            var id = $(this).attr('est-id');
            // alert(id);

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>accounting/estimateviewdetailsajax",
                dataType:"json",
                data : { id : id },
                success: function(response){

                  console.log('yeahhhhhhhhhhhhhhh'+response['estimate']); 

                  $("#estimateview").modal('show');
                  $(".business_name").html(response['client'].business_name);
                  $(".from_address").html(response['client'].business_address);
                  $(".from_email").html(response['client'].email_address);
                  $(".from_phone").html(response['client'].phone_number);

                  $(".estimate_no").html(response['estimate'].estimate_number);
                  $(".estimate_date").html(response['estimate'].estimate_date);
                  $(".expiry_date").html(response['estimate'].expiry_date);

                  $(".to_address").html(response['customer'].city);
                  $(".to_email").html(response['customer'].email);
                  $(".to_phone").html(response['customer'].phone_w);

                  // var objJSONconf = JSON.parse(response['items'][0].conference);
                  //   var input_conf = "";
                  //   $.each(objJSONconf, function (i, v) {
                  //       input_conf += '<tr><td valign="top" style="width:45%;"> '+ response['items'].item +'</td></tr>';
                  //   });
                  //   $("#tableEstimate").html(input_conf);
                  
                  $(".instructions").html(response['estimate'].instructions);
                  $(".message").html(response['estimate'].customer_message);
                  $(".terms").html(response['estimate'].terms_conditions);

                },
                    error: function(response){
                    alert('Error'+response);
       
                }

              });
            // location.reload();
        });
    });
</script>
