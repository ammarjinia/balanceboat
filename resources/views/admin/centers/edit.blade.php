@extends('admin.layouts.admin')
@section('title', 'Edit Center')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Center</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/centers') }}">Centers</a></li>
<li class="breadcrumb-item active">Edit Center</li>
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
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <h5>Last Updated At</h5>
                                <code>{!! $ecenter->updated_at?->format('d M, Y h:i A') !!}</code>
                            </div>
                        </div>
                        <div class="col-6" align="right">
                            <a href="{{ url('/center/'.(@$ecenter->slug).'?preview=1') }}" target="_blank" class="btn btn-info pull-right">Preview</a>
                        </div>
                    </div>
                    <form id="frmCenter" action="{{ url("bbadmin/centers/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo @$ecenter->id; ?>" />
                        <div class="form-group">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" required data-validation-required-message="This field is required" value="<?php echo @$ecenter->name; ?>" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" required data-validation-required-message="This field is required" value="<?php echo @$ecenter->slug; ?>" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title</h5>
                            <div class="controls">
                                <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ @$ecenter->meta_title }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Keywords</h5>
                            <div class="controls">
                                <textarea id="keywords" name="keywords" class="form-control">{{ @$ecenter->keywords }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Description</h5>
                            <div class="controls">
                                <textarea id="meta_description" name="meta_description" class="form-control">{{ @$ecenter->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Year of Foundation</h5>
                                    <div class="controls">
                                        <select id="year_of_foundation" name="year_of_foundation" class="select2 form-control custom-select" style="width: 100%" data-placeholder="Select Year">
                                            <option value="">Select Year</option>
                                            <?php for ($year = date('Y'); $year >= date('Y') - 50; $year--) { ?>
                                                <option value="<?php echo $year; ?>" <?php echo ($ecenter->year_of_foundation == $year) ? "selected" : ""; ?>><?php echo $year; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-control-feedback">
                                        <small>When was the institute founded</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Founders</h5>
                                    <div class="controls">
                                        <input type="text" id="founders" name="founders" class="form-control form-control-tag" data-role="tagsinput" value="{{ old('founders', $ecenter->founders) }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>GPS</h5>
                                    <div class="controls">
                                        <input type="text" id="gps" name="gps" class="form-control" value="{{ old('gps', $ecenter->gps) }}" />
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="form-group">
                            <h5>Location</h5>
                            <div class="controls">
                                <select id="location[]" name="location[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <option value="">Select</option>
                                    <?php
                                    if (@$categories) {
                                        foreach (@$categories as $category) {
                                            ?>
                                            <option value="<?php echo $category->id; ?>" <?php echo (in_array($category->id, @$center_locations)) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="hdn_location_id" name="hdn_location_id" value="<?php echo implode("||", @$center_locations); ?>" />
                                <div class="form-control-feedback"><small>As appears below the institutes name.</small></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Type of Center</h5>
                            <div class="controls">
                                <select id="center_type" name="center_type" class="form-control select2 form-control-select2" >
                                    <option value="">Select</option>
                                    <?php
                                    if (@$center_types) {
                                        foreach (@$center_types as $center_type) {
                                            ?>
                                            <option value="<?php echo $center_type->id; ?>" <?php echo ($center_type->id == $ecenter->center_type) ? " selected " : ""; ?> ><?php echo $center_type->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Speciality</h5>
                            <div class="controls">
                                <select id="speciality_id[]" name="speciality_id[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <?php
                                    if (@$expertises) {
                                        foreach (@$expertises as $expertise) {
                                            ?>
                                            <option value="<?php echo $expertise->id; ?>" <?php echo (in_array($expertise->id, explode("||", $ecenter->speciality_id))) ? "selected" : ""; ?>><?php echo $expertise->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-control-feedback">
                                <small>Appears on the speciality section on the top right</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>About the center</h5>
                            <div class="controls">
                                <textarea id="about_center" name="about_center" class="textarea_editor form-control" rows="15" placeholder="Enter text ...">{{ old('about_center', @$ecenter->about_center) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>What sets us apart</h5>
                            <div class="controls">
                                <textarea id="what_sets_us_apart" name="what_sets_us_apart" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('what_sets_us_apart', @$ecenter->what_sets_us_apart) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Our Philosophy</h5>
                            <div class="controls">
                                <textarea id="our_philosophy" name="our_philosophy" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('our_philosophy', @$ecenter->our_philosophy) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Our Mission</h5>
                            <div class="controls">
                                <textarea id="our_mission" name="our_mission" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('our_mission', @$ecenter->our_mission) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Amenities</h5>
                            <div class="controls">
                                <div class="row">
                                <?php
                                    if (@$amenities) {
                                        foreach (@$amenities as $amenity) {
                                            ?>
                                            <div class="col-md-3">
                                                <input type="checkbox" id="amenities<?php echo $amenity->id; ?>" name="amenities[]" value="<?php echo $amenity->id; ?>" <?php echo (in_array($amenity->id, explode("||", @$ecenter->amenities))) ? "checked" : ""; ?>  />
                                                <label for="amenities<?php echo $amenity->id; ?>"><?php echo $amenity->name; ?></label>
                                            </div>    
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Airport Name</h5>
                                    <div class="controls">
                                        <input type="text" id="airport_name" name="airport_name" class="form-control" value="{{ old('airport_name', $ecenter->airport_name)}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Pickup and Drop Cost</h5>
                                    <div class="controls">
                                        <input type="text" id="pickup_drop_cost" name="pickup_drop_cost" class="form-control" value="{{ old('pickup_drop_cost', $ecenter->pickup_drop_cost)}}" />
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="form-group">
                            <h5>Center Highlights</h5>
                            <div class="controls">
                                <input id="center_highlights" name="center_highlights" class="form-control form-control-tag" data-role="tagsinput" value="<?php echo @$ecenter->center_highlights; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Link to Teacher Profile</h5>
                            <div class="controls">
                                <select id="teacher_id[]" name="teacher_id[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <?php
                                    if (@$teachers) {
                                        foreach (@$teachers as $teacher) {
                                            ?>
                                            <option value="<?php echo $teacher->id; ?>" <?php echo (in_array($teacher->id, @$center_teachers)) ? "selected" : ""; ?>><?php echo $teacher->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="hdn_teacher_id" name="hdn_teacher_id" value="<?php echo implode("||", @$center_teachers); ?>" />
                            </div>
                        </div>

                        <div class="form-group">
                            <h5>Video Url</h5>
                            <div class="controls">
                                <input type="text" name="video" id="video" class="form-control" value="{{ @$ecenter->video_url }}" />
                                @if(@$ecenter->video_url)
                                <div class="row" id="exp_vid_container">
                                    <div class="col-md-4 m-t-10">
                                        <div class="card">
                                            <iframe width="auto" height="180" src="{{ @$ecenter->video_url }}" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="banner_image" id="banner_image" class="form-control" />
                                @if($ecenter->banner_image_url)
                                <div class="row" id="center_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            @if(@$imagegalleries[0]->bg_exp_id)
                                                <a href="{{ Storage::disk('azure_bg')->url($ecenter->banner_image_url) }}" target="_blank">
                                                    <img class="card-img-top img-responsive" src="{{ Storage::disk('azure_bg')->url($ecenter->banner_image_url) }}" alt="{{ $ecenter->banner_image_title }}">
                                                </a>
                                            @else
                                            <a href="{{ Storage::disk('azure')->url($ecenter->banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($ecenter->banner_image_url) }}" alt="{{ $ecenter->banner_image_title }}">
                                            </a>
                                            @endif
                                            <div class="card-body">
                                                <a id="img_delete" href="{{ url('bbadmin/centers/delete_image') }}" data-id="{{ $ecenter->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Image gallery for Center</h5>
                            <div class="controls">
                                <div id="image_gallery" class="dropzone">
                                    <div class="dz-message text-center">
                                        Upload Images (Click or Drag file here)
                                    </div>
                                    <input name="image_galleries" type="file" multiple style="display:none;" />
                                </div>
                                <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="" />
                                <input type="hidden" id="dropzoneurl" value="{{ url("bbadmin/centers/upload_gallery_image") }}" />
                                <div class="row">
                                    @if(@$imagegalleries)
                                    @foreach(@$imagegalleries as $gallery)
                                    <div class="col-md-3 m-t-10" id="img-{{ $gallery->id }}">
                                        <div class="card">
                                            @if($gallery->bg_exp_id)
                                                <a href="{{ Storage::disk('azure_bg')->url($gallery->image_url) }}" target="_blank">
                                                    <img class="card-img-top img-responsive" src="{{ Storage::disk('azure_bg')->url($gallery->image_url) }}" alt="{{ $gallery->image_title }}">
                                                </a>
                                            @else
                                            <a href="{{ Storage::disk('azure')->url($gallery->image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($gallery->image_url) }}" alt="{{ $gallery->image_title }}">
                                            </a>
                                            @endif
                                            <div class="card-body">
                                                <a id="gallery_img_delete" href="{{ url('bbadmin/centers/delete_gallery_image') }}" data-id="{{ $gallery->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5>Certications</h5>
                                    <div class="controls">
                                        <select id="certificate_id" name="certificate_id[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$certificates) {
                                                foreach (@$certificates as $certificate) {
                                                    ?>
                                                    <option value="<?php echo $certificate->id; ?>" <?php echo (in_array($certificate->id, explode("||", $ecenter->certificate_id))) ? "selected" : ""; ?>><?php echo $certificate->name; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h5>Awards</h5>
                                    <div class="controls">
                                        <input id="awards" name="awards" class="form-control form-control-tag" data-role="tagsinput" placeholder="" type="text" value="<?php echo old('awards',$ecenter->awards); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert well alert-info">
                            <h4>For Internal Use Only</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Address of the center</h5>
                                        <div class="controls">
                                            <input type="text" id="address_of_center" name="address_of_center" class="form-control" value="<?php echo $ecenter->address_of_center; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h5>City</h5>
                                        <div class="controls">
                                            <input type="text" id="city" name="city" class="form-control" value="<?php echo $ecenter->city; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h5>Country</h5>
                                        <div class="controls">
                                            <input type="text" id="country" name="country" class="form-control" value="<?php echo $ecenter->country; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Email Address(s)</h5>
                                        <div class="controls">
                                            <input type="text" id="email_address" name="email_address" class="form-control" value="<?php echo $ecenter->email_address; ?>" />
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Contact Number</h5>
                                        <div class="controls">
                                            <input type="text" id="contact_number" name="contact_number" class="form-control" value="<?php echo $ecenter->contact_number; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Whatsapp Number</h5>
                                        <div class="controls">
                                            <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="<?php echo $ecenter->whatsapp_number; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5>Balancegurus Profile link</h5>
                                <div class="controls">
                                    <input type="text" name="balancegurus_profile_link" id="balancegurus_profile_link" class="form-control" value="<?php echo $ecenter->balancegurus_profile_link; ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h5>Tags</h5>
                                        <div class="controls">
                                            <input type="text" id="tags" name="tags" class="form-control form-control-tag" data-role="tagsinput" value="<?php echo $ecenter->tags; ?>" />
                                        </div>
                                    </div>
                                </div>    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h5>Category</h5>
                                        <div class="controls">
                                            <input type="text" id="category_id" name="category_id" class="form-control" value="<?php echo $ecenter->category_id; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Does the center have accomodation?</h5>
                            <div class="controls">
                                <div>
                                    <input name="have_accomodation" id="have_accomodation_yes" class="have_accomodation" checked="" <?php echo ($ecenter->have_accomodation == "Yes") ? "checked" : ""; ?> type="radio" value="Yes" />
                                    <label for="have_accomodation_yes">Yes</label>
                                    <input name="have_accomodation" id="have_accomodation_no" class="have_accomodation" type="radio" value="No" <?php echo ($ecenter->have_accomodation == "No") ? "checked" : ""; ?> />
                                    <label for="have_accomodation_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group acm-opt">
                            <h5>Link to Accomodation</h5>
                            <div class="controls">
                                <select id="accomodation_id[]" name="accomodation_id[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <?php
                                    if (@$accomodations) {
                                        foreach (@$accomodations as $accomodation) {
                                            ?>
                                            <option value="<?php echo $accomodation->id; ?>" <?php echo (in_array($accomodation->id, @$center_accomodations)) ? "selected" : ""; ?>><?php echo $accomodation->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>                                
                                <input type="hidden" id="hdn_accomodation_id" name="hdn_accomodation_id" value="<?php echo implode("||", @$center_accomodations); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Center Features</h5>
                            <div class="controls">
                                <input id="center_features" name="center_features" class="form-control form-control-tag" data-role="tagsinput" value="<?php echo $ecenter->center_features; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Accommodation Overview</h5>
                            <div class="controls">
                                <textarea id="accomodation_overview" name="accomodation_overview" class="textarea_editor form-control" rows="10" placeholder="Enter text ..."><?php echo $ecenter->accomodation_overview; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Accommodation Image</h5>
                            <div class="controls">
                                <input type="file" name="accomodation_banner_image" id="accomodation_banner_image" class="form-control" />
                                @if($ecenter->accomodation_banner_image_url)
                                <div class="row" id="accomodation_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('azure')->url($ecenter->accomodation_banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($ecenter->accomodation_banner_image_url) }}" alt="{{ $ecenter->accomodation_banner_image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="accomodation_img_delete" href="{{ url('bbadmin/centers/delete_accomodation_image') }}" data-id="{{ $ecenter->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>How to get there</h5>
                            <div class="controls">
                                <textarea id="how_to_get_there" name="how_to_get_there" class="textarea_editor form-control" rows="10" placeholder="Enter text ..."><?php echo $ecenter->how_to_get_there; ?></textarea>
                            </div>
                            <div class="form-control-feedback">
                                <small>By air, by road, by train, by bus</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Things to do around the center</h5>
                            <div class="controls">
                                <textarea id="things_to_do_around_the_center" name="things_to_do_around_the_center" class="textarea_editor form-control" rows="10" placeholder="Enter text ..."><?php echo $ecenter->things_to_do_around_the_center; ?></textarea>
                            </div>
                            <div class="form-control-feedback">
                                <small>What are the famous sights around the center</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Status</h5>
                                    <div class="controls">
                                        <select id="is_draft" name="is_draft" class="form-control select2 form-control-select2">
                                            <option value="0" <?php echo ($ecenter->is_draft==0) ? "selected": "";?>>Publish</option>
                                            <option value="1" <?php echo ($ecenter->is_draft==1) ? "selected": "";?>>Draft</option>
                                        </select>
                                    </div>
                                </div>    
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/centers') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/center-create.js') }}"></script>
@endsection
