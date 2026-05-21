@extends('admin.layouts.admin')
@section('title', 'Create Blog')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Create Blog</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/blogs') }}">Blogs</a></li>
<li class="breadcrumb-item active">Create Blog</li>
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
                    <form id="frmCategory" action="{{ url("bbadmin/blog/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Title <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title</h5>
                            <div class="controls">
                                <input type="text" id="meta_title" name="meta_title" class="form-control" /> 
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Keywords</h5>
                            <div class="controls">
                                <textarea id="meta_keywords" name="meta_keywords" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Description</h5>
                            <div class="controls">
                                <textarea id="meta_description" name="meta_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea name="description" id="description" class="form-control textarea_editor" rows="10"></textarea>
                                <input name="image" type="file" id="upload" class="hidden" onchange="" style="display:none;" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="banner_image" id="banner_image" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Image gallery for blog</h5>
                            <div class="controls">
                                <div id="image_gallery" class="dropzone">
                                    <div class="dz-message text-center">
                                        Upload Images (Click or Drag file here)
                                    </div>
                                    <input name="image_galleries" type="file" multiple style="display:none;" />
                                </div>
                                <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="" />
                                <input type="hidden" id="dropzoneurl" value="{{ url("bbadmin/blog/upload_gallery_image") }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Tags</h5>
                            <div class="controls">
                                <input name="tags" id="tags" class="form-control" data-placeholder="" data-role="tagsinput" value="{{ old('tags') }}" />
                            </div>
                                <em>Use comma(,) to add mulitple values</em>
                        </div>
                        <div class="form-group">
                            <h5>Popup Heading</h5>
                            <div class="controls">
                                <input type="text" name="popup_heading" id="popup_heading" class="form-control" value="{{ old('popup_heading') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Popup Description</h5>
                            <div class="controls">
                                <textarea name="popup_description" id="popup_description" class="form-control" rows="5">{{ old('popup_description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Popup URL</h5>
                            <div class="controls">
                                <input type="text" name="popup_url" id="popup_url" class="form-control" value="{{ old('popup_url') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Sidebar Adv. Image</h5>
                            <div class="controls">
                                <input type="file" name="sidebar_adv_image" id="sidebar_adv_image" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Topbar Adv. Image</h5>
                            <div class="controls">
                                <input type="file" name="topbar_adv_image" id="topbar_adv_image" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Status</h5>
                            <div class="controls">
                                <select id="is_draft" name="is_draft" class="form-control">
                                    <option value="0" selected="">Publish</option>
                                    <option value="1">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/blogs') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/blog-create.js') }}"></script>
@endsection
