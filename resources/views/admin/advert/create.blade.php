@extends('admin.layouts.admin')
@section('title', 'Create Advert')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Create Advert</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/adverts') }}">Adverts</a></li>
<li class="breadcrumb-item active">Create Advert</li>
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
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form id="frmCategory" action="{{ url("bbadmin/advert/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Title <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="banner_image_title" name="banner_image_title" class="form-control" required data-validation-required-message="This field is required" value="{!! old('banner_image_title') !!}" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Sub Title</h5>
                            <div class="controls">
                                <textarea id="sub_title" name="sub_title" class="form-control">{!! old('sub_title') !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="banner_image" id="banner_image" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Website <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="url" id="website" name="website" class="form-control" required data-validation-required-message="This field is required" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Position</h5>
                            <div class="controls">
                                <select id="position" name="position" class="form-control">
                                    <option value="Topbar" selected=""  {!! (old('position') == "Topbar") ? "selected" :"" !!}>Topbar</option>
                                    <option value="Sidebar"  {!! (old('position') == "Sidebar") ? "selected" :"" !!}">Sidebar</option>
                                    <option value="Between Content"  {!! (old('position') == "Between Content") ? "selected" :"" !!}">Between Content</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Status</h5>
                            <div class="controls">
                                <select id="is_draft" name="is_draft" class="form-control">
                                    <option value="0" selected=""  {!! (old('is_draft') == "0") ? "selected" :"" !!}>Publish</option>
                                    <option value="1"  {!! (old('is_draft') == "1") ? "selected" :"" !!}>Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/adverts') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.js') }}"></script>
<script src="{{ asset('admin/js/advert-create.js?v=1') }}"></script>
@endsection
