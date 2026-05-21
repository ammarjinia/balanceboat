@extends('admin.layouts.admin')
@section('title', 'Edit Category')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Deal</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/deals') }}">Deals</a></li>
<li class="breadcrumb-item active">Edit Deal</li>
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
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <h5>Last Updated At</h5>
                                <code>{!! $edeal->updated_at?->format('d M, Y h:i A') !!}</code>
                            </div>
                        </div>
                        <div class="col-6" align="right">
                            <a href="{{ url('/deal/'.(@$edeal->slug).'?preview=1') }}" target="_blank" class="btn btn-info pull-right">Preview</a>
                        </div>
                    </div>
                    <form id="frmCourse" action="{{ url("bbadmin/deal/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="{{ $edeal->id }}" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" required data-validation-required-message="This field is required" value="{{ $edeal->name }}" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" required data-validation-required-message="This field is required" value="{{ $edeal->slug }}" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title</h5>
                            <div class="controls">
                                <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ $edeal->meta_title }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Keywords</h5>
                            <div class="controls">
                                <textarea id="meta_keywords" name="meta_keywords" class="form-control">{{ $edeal->meta_keywords }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Description</h5>
                            <div class="controls">
                                <textarea id="meta_description" name="meta_description" class="form-control">{{ $edeal->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Start Date</h5>
                            <div class="controls">
                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $edeal->start_date }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>End Date</h5>
                            <div class="controls">
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $edeal->end_date }}" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <h5>Link to Experience</h5>
                            <div class="controls">
                                <select name="experience_id[]" id="experience_id" class="form-control select2-multiple" multiple="multiple" data-placeholder="">
                                    <?php 
                                    if (@$deal_experiences) {
                                        foreach (@$deal_experiences as $experience) { ?>
                                            <option value="<?php echo $experience->experience_id; ?>" <?php echo (in_array($experience->id, explode("||", $experience->id))) ? "selected" : ""; ?>><?php echo $experience->name; ?></option>
                                            <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Deal Image</h5>
                            <div class="controls">
                                <input type="file" name="deal_image" class="form-control" />
                                @if(@$edeal->image_url)
                                <div class="col-md-3 m-t-10" id="col_img_container">
                                    <div class="card">
                                        <a href="{{ Storage::disk('azure')->url(@$edeal->image_url) }}" target="_blank">
                                            <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url(@$edeal->image_url) }}" alt="{{ @$edeal->image_title }}" />
                                        </a>
                                        <div class="card-body">
                                            <a id="img_delete" href="{{ url('bbadmin/deal/delete_image') }}" data-id="{{ $edeal->id }}" class="btn btn-danger">Remove</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Description</h5>
                            <div class="controls">
                                <textarea id="description" name="description" class="textarea_editor form-control" rows="15" placeholder="Enter text ...">{{ $edeal->description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Status</h5>
                            <div class="controls">
                                <select name="status" id="status" class="form-control select2 form-control-select2">
                                    <option value="0" {{ (@$edeal->status == "0") ? "selected" :"" }}>Publish</option>
                                    <option value="1" {{ (@$edeal->status == "1") ? "selected" :"" }}>Draft</option>
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
