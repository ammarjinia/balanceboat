@extends('admin.layouts.admin')
@section('title', 'Edit Advert')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Advert</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/adverts') }}">Adverts</a></li>
<li class="breadcrumb-item active">Edit Advert</li>
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
                    <form id="frmAdvert" action="{{ url("bbadmin/advert/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo $eadvert->id; ?>" />
                        <div class="form-group">
                            <h5>Title <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="banner_image_title" name="banner_image_title" class="form-control" value="<?php echo $eadvert->banner_image_title; ?>" required data-validation-required-message="This field is required" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Sub Title</h5>
                            <div class="controls">
                                <textarea id="sub_title" name="sub_title" class="form-control">{!! old('sub_title', $eadvert->sub_title) !!}</textarea> </div>
                        </div>
                        <div class="form-group">
                            <h5>Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="banner_image" id="banner_image" class="form-control" />
                                @if($eadvert->banner_image_url)
                                <div class="row" id="banner_image_url_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url($eadvert->banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('s3')->url($eadvert->banner_image_url) }}" alt="{{ $eadvert->banner_image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_delete" href="{{ url('bbadmin/advert/delete_image') }}" data-id="{{ $eadvert->id }}" data-field="banner_image_url" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Website <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="url" id="website" name="website" class="form-control" value="<?php echo $eadvert->website; ?>" required data-validation-required-message="This field is required" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Position</h5>
                            <div class="controls">
                                <select id="position" name="position" class="form-control">
                                    <option value="Topbar" selected=""  {!! (old('position', $eadvert->position) == "Topbar") ? "selected" :"" !!}>Topbar</option>
                                    <option value="Sidebar"  {!! (old('position', $eadvert->position) == "Sidebar") ? "selected" :"" !!}">Sidebar</option>
                                    <option value="Between Content"  {!! (old('position', $eadvert->position) == "Between Content") ? "selected" :"" !!}">Between Content</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Status</h5>
                            <div class="controls">
                                <select id="is_draft" name="is_draft" class="form-control">
                                    <option value="0" {{ $eadvert->is_draft == 0 ? "selected" : "" }}>Publish</option>
                                    <option value="1" {{ $eadvert->is_draft == 1 ? "selected" : "" }}>Draft</option>
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
<script src="{{ asset('admin/js/advert-create.js') }}"></script>
@endsection
