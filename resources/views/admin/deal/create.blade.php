@extends('admin.layouts.admin')
@section('title', 'Create Deal')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Create Deal</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/deals') }}">Deals</a></li>
<li class="breadcrumb-item active">Create Deal</li>
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
                    <form id="frmDeal" action="{{ url("bbadmin/deal/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"  required data-validation-required-message="This field is required" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}"  required data-validation-required-message="This field is required" /> </div>
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
                            <h5>Link to Experience</h5>
                            <div class="controls">
                                <select id="experience_id" name="experience_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Start Date</h5>
                                    <div class="controls">
                                        <input type="date" id="start_date" name="start_date" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>End Date</h5>
                                    <div class="controls">
                                        <input type="date" id="end_date" name="end_date" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php /*
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Category</h5>
                                    <div class="controls">
                                        <select id="deal_category_id[]" name="deal_category_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$categories) {
                                                foreach (@$categories as $category) {
                                                    if ($category->type == 0 && $category->parent == 0) {
                                                        ?>
                                                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Sub Category</h5>
                                    <div class="controls">
                                        <select id="deal_category_id[]" name="deal_category_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$categories) {
                                                foreach (@$categories as $category) {
                                                    if ($category->type == 0 && $category->parent > 0) {
                                                        ?>
                                                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Country</h5>
                                    <div class="controls">
                                        <select id="country" name="country" class="form-control select2" data-placeholder="Select Country">
                                            <option value="">Select Country</option>
                                            <?php
                                            if (@$countries) {
                                                foreach (@$countries as $objCountry) {
                                                    ?>
                                                    <option value="<?php echo $objCountry->name; ?>"><?php echo $objCountry->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>State</h5>
                                    <div class="controls">
                                        <select id="state" name="state" class="form-control select2" data-placeholder="Select State">
                                            <option value="">Select State</option>
                                            <?php
                                            if (@$states) {
                                                foreach (@$states as $objState) {
                                                    ?>
                                                    <option value="<?php echo $objState->name; ?>"><?php echo $objState->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>City</h5>
                                    <div class="controls">
                                        <select id="city" name="city" class="form-control select2" data-placeholder="Select City">
                                            <option value="">Select City</option>
                                            <?php
                                            if (@$cities) {
                                                foreach (@$cities as $objCity) {
                                                    ?>
                                                    <option value="<?php echo $objCity->name; ?>"><?php echo $objCity->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> */ ?>
                        <div class="form-group">
                            <h5>Deal Image</h5>
                            <div class="controls">
                                <input type="file" name="deal_image" class="form-control" /> </div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea id="description" name="description" class="textarea_editor form-control" rows="15" placeholder="Enter text ..."></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Status</h5>
                            <div class="controls">
                                <select name="status" id="status" class="form-control select2 form-control-select2">
                                    <option value="0" selected>Publish</option>
                                    <option value="1">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/deals') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/deal-create.js') }}"></script>
@endsection
