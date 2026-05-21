@extends('admin.layouts.admin')
@section('title', 'Upload Experiences')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Upload Experiences</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/experiences') }}">Experiences</a></li>
<li class="breadcrumb-item active">Upload Experiences</li>
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
                    <form id="frmComment" action="{{ url("bbadmin/experiences/store-upload") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>File <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="file" name="file" id="file" class="form-control required" />
                                @error('file')
                                    <div class="text-danger">{!! $message !!}</div>
                                @enderror
                            </div>
                            <div class="form-control-feedback"><small>Sample File: <a href="{!! url('documents/Sample_Experience_File.csv') !!}" target="_blank" download="Sample_Experience_File.csv">Download</a></small></div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/experiences') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/experience-create.js') }}"></script>
@endsection
