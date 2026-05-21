@extends('admin.layouts.admin')
@section('title', 'Edit Teacher')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Teacher</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/teachers') }}">Teachers</a></li>
<li class="breadcrumb-item active">Edit Teacher</li>
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
                    <form id="frmTeacher" action="{{ url("bbadmin/teachers/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo $eteacher->id; ?>" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo $eteacher->name; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" value="<?php echo $eteacher->slug; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title</h5>
                            <div class="controls">
                                <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ @$eteacher->meta_title }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Keywords</h5>
                            <div class="controls">
                                <textarea id="keywords" name="keywords" class="form-control"><?php echo $eteacher->keywords; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Description</h5>
                            <div class="controls">
                                <textarea id="meta_description" name="meta_description" class="form-control">{{ @$eteacher->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Short Intro</h5>
                            <div class="controls">
                                <textarea name="short_description" id="short_description" class="form-control"><?php echo $eteacher->short_description; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Expertise</h5>
                            <div class="controls">
                                <select id="expertise_id" name="expertise_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                    <?php
                                    if (@$expertises) {
                                        foreach (@$expertises as $expertise) {
                                            ?>
                                            <option value="<?php echo $expertise->id; ?>" <?php echo (in_array($expertise->id, explode("||", $eteacher->expertise_id))) ? "selected" : ""; ?>><?php echo $expertise->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-control-feedback">
                                <small>Used for Seo</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Certifications</h5>
                            <div class="controls">
                                <select id="certificate_id" name="certificate_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                    <?php
                                    if (@$certificates) {
                                        foreach (@$certificates as $certificate) {
                                            ?>
                                            <option value="<?php echo $certificate->id; ?>" <?php echo (in_array($certificate->id, explode("||", $eteacher->certificate_id))) ? "selected" : ""; ?>><?php echo $certificate->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Teaching Since</h5>
                            <div class="controls">
                                <select id="teaching_since" name="teaching_since" class="select2 form-control custom-select" style="width: 100%" data-placeholder="Select Year">
                                    <option value="">Select Year</option>
                                    <?php for ($year = date('Y'); $year >= date('Y') - 50; $year--) { ?>
                                        <option value="<?php echo $year; ?>" <?php echo ($eteacher->teaching_since == $year) ? "selected" : ""; ?>><?php echo $year; ?></option>
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
                                            <option value="<?php echo @$center->id; ?>" <?php echo (in_array(@$center->id, @$center_teachers)) ? "selected" : ""; ?>><?php echo @$center->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="hdn_center_id" name="hdn_center_id" value="<?php echo implode("||", @$center_teachers); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Complete Bio</h5>
                            <div class="controls">
                                <textarea id="complete_bio" name="complete_bio" class="textarea_editor form-control" rows="15" placeholder="Enter text ..."><?php echo $eteacher->complete_bio; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Profile Image</h5>
                            <div class="controls">
                                <input type="file" name="profile_image" class="form-control" />
                                @if($eteacher->profile_image_url)
                                <div class="row" id="teach_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('azure')->url($eteacher->profile_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($eteacher->profile_image_url) }}" alt="{{ $eteacher->profile_image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_delete" href="{{ url('bbadmin/teachers/delete_image') }}" data-id="{{ $eteacher->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Image gallery</h5>
                            <div class="controls">
                                <div id="image_gallery" class="dropzone">
                                    <div class="dz-message text-center">
                                        Upload Images (Click or Drag file here)
                                    </div>
                                    <input name="image_galleries" type="file" multiple style="display:none;" />
                                </div>
                                <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="" />
                                <input type="hidden" id="dropzoneurl" value="{{ url("bbadmin/teachers/upload_gallery_image") }}" />
                                <div class="row">
                                    @if(@$imagegalleries)
                                    @foreach(@$imagegalleries as $gallery)
                                    <div class="col-md-3 m-t-10" id="img-{{ $gallery->id }}">
                                        <div class="card">
                                            <a href="{{ Storage::disk('azure')->url($gallery->image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($gallery->image_url) }}" alt="{{ $gallery->image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="gallery_img_delete" href="{{ url('bbadmin/teachers/delete_gallery_image') }}" data-id="{{ $gallery->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Currently Working With</h5>
                            <div class="controls">
                                <textarea id="currently_working_with" name="currently_working_with" class="textarea_editor form-control" rows="15" placeholder="Enter text ..."><?php echo $eteacher->currently_working_with; ?></textarea>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/teachers') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('admin/js/teacher-create.js') }}"></script>
@endsection
