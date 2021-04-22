<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<?php include viewPath('includes/header'); ?>
<div class="wrapper" role="wrapper">
    <?php include viewPath('includes/sidebars/estimate'); ?>
   

    <!-- page wrapper start -->
    <div wrapper__section>
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="page-title">Submit Standard Estimate</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Submit your estimate. Include a breakdown of all costs
                                for this job.
                            </li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-right d-none d-md-block">
                            <div class="dropdown">
                                <?php //if (hasPermissions('WORKORDER_MASTER')) : ?>
                                    <a href="<?php echo base_url('estimate') ?>" class="btn btn-primary"
                                       aria-expanded="false">
                                        <i class="mdi mdi-settings mr-2"></i> Go Back to Estimate
                                    </a>
                                <?php //endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <?php echo form_open_multipart('estimate/update/' . $estimate->id, ['class' => 'form-validate require-validation', 'id' => 'estimate_form', 'autocomplete' => 'off']); ?>
            <style>

            </style>
            <div class="row custom__border">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="customers">Customer</label>
                                    <select id="sel-customer" name="customer_id" data-customer-source="dropdown"
                                            class="form-control searchable-dropdown" placeholder="Select">
                                        <option value="0">- none -</option>
                                        <?php foreach($customers as $c){ ?>
                                            <?php echo "<pre>";print_r($c); ?>
                                            <option <?= $c->prof_id == $estimate->customer_id ? 'selected="selected"' : ''; ?> value="<?= $c->prof_id; ?>"><?= $c->first_name . ' ' . $c->last_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="job_location">Job Location</label>
                                    <input type="text" class="form-control"
                                           value="<?php echo $estimate->job_location ?>" name="job_location"
                                           id="job_location" required placeholder="Enter address" autofocus
                                           onChange="jQuery('#customer_name').text(jQuery(this).val());"/>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="job_name">Job Name</label>
                                    <input type="text" class="form-control" value="<?php echo $estimate->job_name ?>"
                                           name="job_name" id="job_name" placeholder="Enter Job Name" required/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="estimate">Estimate#</label>
                                    <input type="text" value="<?= $estimate->estimate_number; ?>" class="form-control" name="estimate_number" id="estimate" required placeholder="Enter Estimate#"/>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="estimate_date">Estimate Date</label>
                                    <!-- <input type="text" class="form-control" name="estimate_date" id="estimate_date" required placeholder="Enter Estimate Date" autofocus onChange="jQuery('#customer_name').text(jQuery(this).val());" /> -->
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text"
                                               value="<?php echo date('d/m/Y', strtotime($estimate->estimate_date)) ?>"
                                               class="form-control" name="estimate_date" id="estimate_date"
                                               placeholder="Enter Estimate Date">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="expiry_date">Expiry Date</label>
                                    <!-- <input type="text" class="form-control" name="expiry_date" id="expiry_date" required placeholder="Enter Expiry Date" autofocus onChange="jQuery('#customer_name').text(jQuery(this).val());" /> -->
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text"
                                               value="<?php echo date('d/m/Y', strtotime($estimate->expiry_date)) ?>"
                                               class="form-control" name="expiry_date" id="expiry_date"
                                               placeholder="Enter Expiry Date">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="purchase_order_number">Purchase Order#</label>
                                    <input type="text" value="<?php echo $estimate->purchase_order_number ?>"
                                           class="form-control" name="purchase_order_number" id="purchase_order_number"
                                           required placeholder="Enter Purchase Order#" autofocus
                                           onChange="jQuery('#customer_name').text(jQuery(this).val());"/>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="zip">Estimate Status</label>
                                    <!-- <input type="text" value="<?php echo $estimate->job_location ?>" class="form-control" name="zip" id="zip" required placeholder="Enter Estimate Status" /> -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group mt-3">
                                    <label for="street_address"> Plan Type:</label>
                                    <div class="c__custom c__custom_width  ">
                                        <?php if (count($plans) > 0) { ?>
                                            <?php foreach ($plans as $pn) { ?>
                                                <?php if ($estimate->plan_id == $pn->id) { ?>
                                                    <div class="checkbox checkbox-sec margin-right mr-4">
                                                        <input onClick="getplanItems(<?= $pn->id; ?>)" type="radio"
                                                               name="plan_id" value="<?= $pn->id; ?>"
                                                               id="radio_credit_card<?= $pn->id; ?>" checked>
                                                        <label for="radio_credit_card<?= $pn->id; ?>"><span><?= $pn->plan_name; ?></span></label>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="checkbox checkbox-sec margin-right mr-4">
                                                        <input onClick="getplanItems(<?= $pn->id; ?>)" type="radio"
                                                               name="plan_id" value="<?= $pn->id; ?>"
                                                               id="radio_credit_card<?= $pn->id; ?>">
                                                        <label for="radio_credit_card<?= $pn->id; ?>"><span><?= $pn->plan_name; ?></span></label>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="plansItemDiv">
                                <?php if ($estimate->estimate_items != '') {

                                    $estimate_items = unserialize($estimate->estimate_items);
                                } else {

                                    $estimate_items = [];
                                } ?>
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-hover">
                                        <input type="hidden" name="count" value="0" id="count">
                                        <thead>
                                        <tr>                                            
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th width="100px">Quantity</th>
                                            <th>Location</th>
                                            <th width="100px">Cost</th>
                                            <th width="100px">Discount</th>
                                            <th>Tax(%)</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody id="table_body">
                                        <?php if (count($estimate_items) > 0) { ?>
                                            <input type="hidden" name="count"
                                                   value="<?php echo count($estimate_items) > 0 ? count($estimate_items) - 1 : 0; ?>"
                                                   id="count">
                                            <?php $i = 0;
                                            foreach ($estimate_items as $row) { ?>

                                                <tr>                                                    
                                                    <td><select name="item_type[]" class="form-control">

                                                            <option value="material" <?php if ($row['item_type'] == 'material') echo 'selected'; ?>>
                                                                Material
                                                            </option>
                                                            <option value="product" <?php if ($row['item_type'] == 'product') echo 'selected'; ?>>
                                                                Product
                                                            </option>
                                                            <option value="service" <?php if ($row['item_type'] == 'service') echo 'selected'; ?>>
                                                                Service
                                                            </option>
                                                        </select></td>
                                                    <td>
                                                        <input type="text" class="form-control getItems"
                                                               onKeyup="getItems(this)" name="item[]"
                                                               value="<?php echo $row['item']; ?>">
                                                        <ul class="suggestions"></ul>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control quantity"
                                                               name="quantity[]" data-counter="<?php echo $i; ?>"
                                                               id="quantity_<?php echo $i; ?>"
                                                               value="<?php echo $row['quantity'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="location[]"
                                                               value="<?php echo $row['location'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control price" name="price[]"
                                                               data-counter="<?php echo $i; ?>"
                                                               id="price_<?php echo $i; ?>" min="0"
                                                               value="<?php echo $row['price'] ?>">
                                                    </td>
                                                    <td>
                                                        <input type="number" value="<?php echo $row['discount'] ?>"
                                                               class="form-control discount" name="discount[]"
                                                               data-counter="<?php echo $i; ?>"
                                                               id="discount_<?php echo $i; ?>" min="0" value="0"
                                                               readonly>
                                                    </td>
                                                    <td>
                                                        <span id="span_tax_<?php echo $i; ?>"><?php $tax = ($row['price'] * 7.5 / 100) * $row['quantity'];
                                                            echo number_format($tax, 2) ?></span>
                                                    </td>
                                                    <td>
                                                        <span id="span_total_<?php echo $i; ?>"><?php $price = ($row['price'] + $tax) * $row['quantity'];
                                                            echo number_format($price, 2); ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="remove btn btn-sm btn-danger">x</a>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            } ?>

                                        <?php } else { ?>
                                            <input type="hidden" name="count" value="0" id="count">
                                            <tr>                                                
                                                <td><select name="item_type[]" class="form-control">
                                                        <option value="service">Service</option>
                                                        <option value="material">Material</option>
                                                        <option value="product">Product</option>
                                                    </select></td>
                                                <td><input type="text" class="form-control getItems"
                                                           onKeyup="getItems(this)" name="item[]">
                                                    <ul class="suggestions"></ul>
                                                </td>
                                                <td><input type="text" class="form-control quantity" name="quantity[]"
                                                           data-counter="0" id="quantity_0" value="1"></td>
                                                <td><input type="text" class="form-control" name="location[]"></td>
                                                <td><input type="number" class="form-control price" name="price[]"
                                                           data-counter="0" id="price_0" min="0" value="0"></td>
                                                <td><input type="number" class="form-control discount" name="discount[]"
                                                           data-counter="0" id="discount_0" min="0" value="0" readonly>
                                                </td>
                                                <td><span id="span_tax_0">0.00 (7.5%)</span></td>
                                                <td><span id="span_total_0">0.00</span></td>
                                            </tr>

                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <a href="#" class="btn btn-primary" id="add_another">Add Items</a>
                                </div>
                            </div>
                            <br/>

                            <?php
                            if ($estimate->estimate_eqpt_cost != '') {

                                $estimate_eqpt_cost = unserialize($estimate->estimate_eqpt_cost);
                            } else {

                                $estimate_eqpt_cost = [];
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Equipment Cost</td>
                                            <td class="d-flex align-items-center">$ <input type="text"
                                                                                           value="<?php echo !empty($estimate_eqpt_cost) ? $estimate_eqpt_cost['eqpt_cost'] : 0.00; ?>"
                                                                                           name="eqpt_cost"
                                                                                           id="eqpt_cost"
                                                                                           onfocusout="cal_total_due()"
                                                                                           class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>Sales Tax</td>
                                            <td class="d-flex align-items-center">$ <input type="text"
                                                                                           value="<?php echo !empty($estimate_eqpt_cost) ? $estimate_eqpt_cost['sales_tax'] : 0.00; ?>"
                                                                                           name="sales_tax"
                                                                                           id="sales_tax"
                                                                                           onfocusout="cal_total_due()"
                                                                                           class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>Installation Cost</td>
                                            <td class="d-flex align-items-center">$ <input type="text"
                                                                                           value="<?php echo !empty($estimate_eqpt_cost) ? $estimate_eqpt_cost['inst_cost'] : 0.00; ?>"
                                                                                           name="inst_cost"
                                                                                           id="inst_cost"
                                                                                           onfocusout="cal_total_due()"
                                                                                           class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>One time P/Dated <br/>Program and Setup</td>
                                            <td class="d-flex align-items-center">$ <input type="text"
                                                                                           value="<?php echo !empty($estimate_eqpt_cost) ? $estimate_eqpt_cost['one_time'] : 0.00; ?>"
                                                                                           name="one_time" id="one_time"
                                                                                           onfocusout="cal_total_due()"
                                                                                           class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>Monthly Monitoring</td>
                                            <td class="d-flex align-items-center">$ <input type="text"
                                                                                           value="<?php echo !empty($estimate_eqpt_cost) ? $estimate_eqpt_cost['m_monitoring'] : 0.00; ?>"
                                                                                           name="m_monitoring"
                                                                                           id="m_monitoring"
                                                                                           onfocusout="cal_total_due()"
                                                                                           class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>Total Due</td>
                                            <td class="d-flex align-items-center">$ <span
                                                        id="total_due"><?php echo !empty($estimate_eqpt_cost) ? number_format($estimate_eqpt_cost['eqpt_cost'] + $estimate_eqpt_cost['sales_tax'] + $estimate_eqpt_cost['inst_cost'] + $estimate_eqpt_cost['one_time'] + $estimate_eqpt_cost['m_monitoring'], 2) : '0.00'; ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Request a Deposit</h5>
                                    <p>You can request an upfront payment on accept estimate.</p>
                                </div>
                                <div class="col-md-4 form-group">
                                    <select name="deposit_request" class="form-control">
                                        <option value="1" selected="selected">Deposit amount $</option>
                                        <option value="2">Percentage %</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <div class="input-group">
                                        <!-- <div class="input-group-addon bold">$</div> -->
                                        <input type="text" value="<?php echo $estimate->deposit_amount ?>"
                                               name="deposit_amount" value="0" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Message to Customer</label> <span class="help help-sm help-block">Add a message that will be displayed on the estimate.</span>
                                        <textarea name="customer_message" cols="40" rows="2"
                                                  class="form-control"><?php echo $estimate->customer_message ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Terms &amp; Conditions</label> <span class="help help-sm help-block">Mention your company's T&amp;C that will appear on the estimate.</span>
                                        <textarea name="terms_conditions" cols="40" rows="2"
                                                  class="form-control"><?php echo $estimate->terms_conditions ?></textarea>
                                    </div>
                                </div>

                                <!-- <div class="col-md-12">
                                    <label>Attachments</label>
                                    <div class="help help-sm help-block margin-bottom-sec">Optionally attach files to this invoice. Allowed type: pdf, doc, docx, png, jpg, gif</div>


                                    <span class="btn btn-default btn-md fileinput-button vertical-top"><span class="fa fa-upload"></span> Upload File <input data-fileupload="attachment-file" name="attachment-file" type="file"></span>
                                </div> -->
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Instructions</label> <span class="help help-sm help-block">Optional internal notes, will not appear to customer</span>
                                        <textarea name="instructions" cols="40" rows="2"
                                                  class="form-control"><?php echo $estimate->instructions ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Proposal Kit Template</label> <span class="help help-sm help-block">Apply a proposal template to this estimate to create a proposal.</span>
                                        <textarea name="message" cols="40" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div> -->


                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <button type="submit" class="btn btn-flat btn-primary">Save</button>
                                    <a href="<?php echo url('estimate') ?>" class="btn btn-danger">Cancel this</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
            </div>

            <?php echo form_close(); ?>

            <!-- Modal Service Address -->
            <div class="modal fade" id="modalServiceAddress" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Service Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Additional Contact -->
            <div class="modal fade" id="modalAdditionalContacts" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalAddNewSource" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Service Address</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="frm_add_new_source" name="modal-form" method="post">
                                <div class="validation-error" style="display: none;"></div>
                                <div class="form-group">
                                    <label>Source Name</label> <span class="form-required">*</span>
                                    <input type="text" name="source_name" value="" class="form-control"
                                           autocomplete="off">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary save">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- page wrapper end -->
</div>
<?php include viewPath('includes/footer'); ?>

<script>
    function validatecard() {
        var inputtxt = $('.card-number').val();

        if (inputtxt == 4242424242424242) {
            $('.require-validation').submit();
        } else {
            alert("Not a valid card number!");
            return false;
        }
    }

    $(document).ready(function () {
        $('#sel-customer').select2();
        // $("#customer_source").select2().select2('val', 1);
        /*$('#customers')
            .empty() //empty select
            .append($("<option/>") //add option tag in select
                .val("<?php echo $estimate->customer_id ?>") //set value for option to post it
                .text("<?php echo $estimate->customer->contact_name ?>")) //set a text for show in select
            .val("<?php echo $estimate->customer_id ?>") //select option of select2
            .trigger("change"); //apply to select2*/
    });
</script>