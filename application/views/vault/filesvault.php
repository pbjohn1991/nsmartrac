<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php include viewPath('includes/header'); ?>
<style>
    .cell-active {
        background-color: #5bc0de;
    }

    .cell-inactive {
        background-color: #d9534f;
    }

    .hide {
        display: none;
    }
</style>
<div class="wrapper" role="wrapper">
    <?php include viewPath('includes/sidebars/filevault'); ?>
    <!-- page wrapper start -->
    <div wrapper__section>
        <div class="container-fluid">
            <!-- <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="page-title">Files Vault</h1>
                    </div>
                </div>
            </div> -->
            <!-- end row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card vaultContainer" style="padding: 5px 20px !important; min-height: 400px !important;">

                        <div class="vault__header">
                            <h1 class="vault__title">Files Vault</h1>
                            <div class="alert alert-warning mt-2 d-none" role="alert">
                                <span style="color:black;">
                                    Our archive of business forms include over 1000 commonly used templates to start, plan, organize, manage, finance and grow your business.
                                </span>
                            </div>
                        </div>

                        <?php include viewPath('flash'); ?>
                        <div class="center" style="margin: 95px auto;text-align: center;">
                            <h5 class="page-empty-header">Upload and use files in your work flow</h5>
                            <p class="text-ter margin-bottom">Markate Vault lets you retain, hold, view, and attach files to Estimate and invoice.</p>
                            <a class="btn btn-primary" href="#modalAddFileVault" data-toggle="modal" data-target="#modalAddFileVault"><span class="fa fa-plus fa-margin-right"></span> Add file</a>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- page wrapper end -->
</div>
<?php include viewPath('includes/settings_modal'); ?>
<?php include viewPath('includes/footer'); ?>