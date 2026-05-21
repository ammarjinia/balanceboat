@extends('admin.layouts.admin')
@section('title', 'Create Commission')

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Create Commission</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/commissions') }}">Commissions</a></li>
<li class="breadcrumb-item active">Create Commission</li>
@endsection
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="frmCommission" action="{{ url("bbadmin/commission/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea name="description" id="description" class="form-control textarea_editor" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Link to Center</h5>
                            <div class="controls">
                                <select id="center_id" name="center_id" class="form-control form-control-select2" data-placeholder="">
                                    <option value="">Select</option>
                                    <?php
                                    if (@$centers) {
                                        foreach (@$centers as $center) {
                                            ?>
                                            <option value="<?php echo @$center->id; ?>"><?php echo @$center->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div><div class="form-group">
                            <h5>Cancellation Policy</h5>
                            <div class="controls">
                                <textarea id="cancellation_policy" name="cancellation_policy" class="textarea_editor form-control" rows="10" placeholder="Enter text ..."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Deposit policy</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <input type="radio" id="deposit_policy1" name="deposit_policy" value="1" checked="" />
                                        <label for="deposit_policy1">Full price</label>
                                        <input type="radio" id="deposit_policy2" name="deposit_policy" value="2" />
                                        <label for="deposit_policy2">Fixed amount</label>
                                        <input type="radio" id="deposit_policy3" name="deposit_policy" value="3" />
                                        <label for="deposit_policy3">Percentage of the listing price</label>
                                    </div>                                
                                </div>
                                <div class="controls" id="dv_deposit_amount" style="display: none;">
                                    <input id="deposit_amount" name="deposit_amount" class="form-control col-md-2" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Cancellation(Refund) policy</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <input type="radio" id="cancellation_policy_condition1" name="cancellation_policy_condition" value="1" checked="" />
                                        <label for="cancellation_policy_condition1">Non Refundable</label>
                                        <input type="radio" id="cancellation_policy_condition2" name="cancellation_policy_condition" value="2" />
                                        <label for="cancellation_policy_condition2">Always refundable</label>
                                        <input type="radio" id="cancellation_policy_condition3" name="cancellation_policy_condition" value="3" />
                                        <label for="cancellation_policy_condition3">Refundable before specified number of days before arrival date</label>
                                    </div>                                
                                </div>
                                <div class="controls" id="dv_cancellation_policy_days" style="display: none;">
                                    <input id="cancellation_policy_days" name="cancellation_policy_days" class="form-control col-md-2" />
                                    <div class="form-control-feedback"><small>days before arrival</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Over all Commission</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <input id="commission" name="commission" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Tax</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <input id="tax" name="tax" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/commissions') }}" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/commission-create.js') }}"></script>
@endsection
