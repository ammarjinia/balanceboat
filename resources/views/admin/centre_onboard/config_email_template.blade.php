@extends('admin.layouts.admin')
@section('title', 'Config Email Template')

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Config Email Template</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/centre-onboard') }}">Centre Onboard</a></li>
<li class="breadcrumb-item active">Config Email Template</li>
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
                    <form id="frmCenter" action="{{ url("bbadmin/centre-onboard/config-email-template") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Subject <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="subject" name="subject" class="form-control" required data-validation-required-message="This field is required" value="<?php echo @$email->subject; ?>" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Body</h5>
                            <div class="controls">
                                <textarea id="body" name="body" class="form-control required textarea_editor">{!! @$email->body !!}</textarea>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/centre-onboard') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/centre-onboard-email.js?v=1.51') }}"></script>
@endsection
