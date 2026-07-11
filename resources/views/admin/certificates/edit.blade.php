@extends('admin.layouts.admin')
@section('title', 'Edit Certificate')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Certificate</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/certificates') }}">Certificates</a></li>
<li class="breadcrumb-item active">Edit Certificate</li>
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
                    <form id="frmCertificate" action="{{ url("bbadmin/certificates/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo $ecertificate->id; ?>" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo $ecertificate->name; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" value="<?php echo $ecertificate->slug; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Certificate Image</h5>
                            <div class="controls">
                                <input type="file" name="certificate_image" class="form-control" />
                                @if($ecertificate->image_url)
                                <div class="col-md-3 m-t-10" id="cert_img_container">
                                    <div class="card">
                                        <a href="{{ Storage::disk('s3')->url($ecertificate->image_url) }}" target="_blank">
                                            <img class="card-img-top img-responsive" src="{{ Storage::disk('s3')->url($ecertificate->image_url) }}" alt="{{ $ecertificate->image_title }}">
                                        </a>
                                        <div class="card-body">
                                            <a id="img_delete" href="{{ url('bbadmin/certificates/delete_image') }}" data-id="{{ $ecertificate->id }}" class="btn btn-danger">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea name="description" id="description" class="form-control" rows="10"><?php echo $ecertificate->description; ?></textarea>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/certificates') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/certificate-create.js') }}"></script>
@endsection
