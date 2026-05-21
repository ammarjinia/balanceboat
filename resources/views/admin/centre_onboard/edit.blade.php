@extends('admin.layouts.admin')
@section('title', 'Edit Centre Onboard')

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit  Centre Onboard</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/centre-onboard') }}">Centre Onboard</a></li>
<li class="breadcrumb-item active">Edit</li>
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
                    <form id="frmCenter" action="{{ url("bbadmin/centre-onboard/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo @$ecenter->id; ?>" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" required data-validation-required-message="This field is required" value="<?php echo @$ecenter->name; ?>" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Email <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="email" id="email" name="email" class="form-control" required data-validation-required-message="This field is required" value="<?php echo @$ecenter->email; ?>" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Url</h5>
                            <div class="controls">
                                <input type="text" id="url" name="url" class="form-control" value="<?php echo @$ecenter->url; ?>" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Location</h5>
                            <div class="controls">
                                <input type="text" id="location" name="location" class="form-control" value="{{ @$ecenter->location }}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Status</h5>
                                    <div class="controls">
                                        <select id="status" name="status" class="form-control select2 form-control-select2">
                                            <option value="Pending" {{ (@$ecenter->status == 'Pending') ? "selected" : "" }}>Pending</option>
                                            <option value="Active" {{ (@$ecenter->status == 'Active') ? "selected" : "" }}>Active</option>
                                        </select>
                                    </div>
                                </div>    
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
<script src="{{ asset('admin/js/centre-onboard-create.js') }}"></script>
@endsection
