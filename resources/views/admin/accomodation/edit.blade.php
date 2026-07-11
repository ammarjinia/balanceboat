@extends('admin.layouts.admin')
@section('title', 'Edit Accomodation')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Accomodation</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/accomodations') }}">Accomodations</a></li>
<li class="breadcrumb-item active">Edit Accomodation</li>
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
                    <form id="frmAccomodation" action="{{ url("bbadmin/accomodation/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo $eaccomodation->id; ?>" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo $eaccomodation->name; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" value="<?php echo $eaccomodation->slug; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea name="description" id="description" class="form-control textarea_editor" rows="10"><?php echo $eaccomodation->description; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Max Guests in this room</h5>
                            <div class="controls">
                                <select id="max_guest_in_room" name="max_guest_in_room" class="select2 form-control custom-select form-control-select2" data-placeholder="Select ">
                                    <option value="">Select </option>
                                    <?php for ($i = 1; $i <= 100; $i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($eaccomodation->max_guest_in_room == $i) ? "selected" : ""; ?> ><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Link to Center</h5>
                            <div class="controls">
                                <select id="center_id[]" name="center_id[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <option value="">Select</option>
                                    <?php
                                    if (@$centers) {
                                        foreach (@$centers as $center) {
                                            ?>
                                            <option value="<?php echo @$center->id; ?>" <?php echo (in_array(@$center->id, @$center_accomodations)) ? "selected" : ""; ?>><?php echo @$center->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="hdn_center_id" name="hdn_center_id" value="<?php echo implode("||", @$center_accomodations); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="banner_image" id="banner_image" class="form-control" />
                                @if($eaccomodation->banner_image_url)
                                <div class="row" id="center_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url($eaccomodation->banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('s3')->url($eaccomodation->banner_image_url) }}" alt="{{ $eaccomodation->banner_image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_delete" href="{{ url('bbadmin/accomodation/delete_image') }}" data-id="{{ $eaccomodation->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Image gallery for accomodation</h5>
                            <div class="controls">
                                <div id="image_gallery" class="dropzone">
                                    <div class="dz-message text-center">
                                        Upload Images (Click or Drag file here)
                                    </div>
                                    <input name="image_galleries" type="file" multiple style="display:none;" />
                                </div>
                                <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="" />
                                <input type="hidden" id="dropzoneurl" value="{{ url("bbadmin/accomodation/upload_gallery_image") }}" />
                                <div class="row">
                                    @if(@$imagegalleries)
                                    @foreach(@$imagegalleries as $gallery)
                                    <div class="col-md-3 m-t-10" id="img-{{ $gallery->id }}">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url($gallery->image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('s3')->url($gallery->image_url) }}" alt="{{ $gallery->image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="gallery_img_delete" href="{{ url('bbadmin/accomodation/delete_gallery_image') }}" data-id="{{ $gallery->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/accomodations') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/accomodation-create.js') }}"></script>
@endsection
