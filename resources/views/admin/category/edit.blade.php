@extends('admin.layouts.admin')
@section('title', 'Edit Category')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Category</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/category') }}">Categories</a></li>
<li class="breadcrumb-item active">Edit Category</li>
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
                    <form id="frmCategory" action="{{ url("bbadmin/category/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo $ecategory->id; ?>" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo $ecategory->name; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" value="<?php echo $ecategory->slug; ?>" required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title</h5>
                            <div class="controls">
                                <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ @$ecategory->meta_title }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Keywords</h5>
                            <div class="controls">
                                <textarea id="keywords" name="keywords" class="form-control"><?php echo $ecategory->keywords; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Description</h5>
                            <div class="controls">
                                <textarea id="meta_description" name="meta_description" class="form-control">{{ @$ecategory->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Type</h5>
                                    <div class="controls">
                                        <select name="type" id="type" class="form-control">
                                            <option value="0" <?php echo ($ecategory->type == 0) ? "selected" : ""; ?>>Category</option>
                                            <option value="1" <?php echo ($ecategory->type == 1) ? "selected" : ""; ?>>Destination</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>Parent Category</h5>
                                    <div class="controls">
                                        <select name="parent" id="parent" class="form-control">
                                            <option value="0" <?php echo ($ecategory->parent == 0) ? "selected" : ""; ?>>None</option>
                                            <?php
                                            if (@$categories) {
                                                foreach ($categories as $category) {
                                                    ?>
                                                    <option class="<?php echo ($category->type == 1) ? "destination-type" : "category-type"; ?>" value="<?php echo $category->id; ?>" <?php echo ($ecategory->parent == $category->id) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>Display on Homepage</h5>
                                    <div class="controls">
                                        <select name="display_on_home" id="display_on_home" class="form-control">
                                            <option value="0" <?php echo ($ecategory->display_on_home == 0) ? "selected" : ""; ?>>No</option>
                                            <option value="1" <?php echo ($ecategory->display_on_home == 1) ? "selected" : ""; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Category Image</h5>
                            <div class="controls">
                                <input type="file" name="category_image" class="form-control" />
                                @if($ecategory->image_url)
                                <div class="col-md-3 m-t-10" id="cat_img_container">
                                    <div class="card">
                                        <a href="{{ Storage::disk('azure')->url($ecategory->image_url) }}" target="_blank">
                                            <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($ecategory->image_url) }}" alt="{{ $ecategory->image_title }}">
                                        </a>
                                        <div class="card-body">
                                            <a id="img_delete" href="{{ url('bbadmin/category/delete_image') }}" data-id="{{ $ecategory->id }}" class="btn btn-danger">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Category Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="category_banner_image" class="form-control" />
                                @if($ecategory->banner_image_url)
                                <div class="col-md-3 m-t-10" id="cat_img_banner_container">
                                    <div class="card">
                                        <a href="{{ Storage::disk('azure')->url($ecategory->banner_image_url) }}" target="_blank">
                                            <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($ecategory->banner_image_url) }}" alt="{{ $ecategory->banner_image_title }}">
                                        </a>
                                        <div class="card-body">
                                            <a id="img_banner_delete" href="{{ url('bbadmin/category/delete_banner_image') }}" data-id="{{ $ecategory->id }}" class="btn btn-danger">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea name="description" id="description" class="form-control" rows="10"><?php echo $ecategory->description; ?></textarea>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/category') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/category-create.js') }}"></script>
@endsection
