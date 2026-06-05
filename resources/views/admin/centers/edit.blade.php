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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Last Updated At</h5>
                            <code>{!! $ecenter->updated_at?->format('d M, Y h:i A') !!}</code>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('/center/'.(@$ecenter->slug).'?preview=1') }}" target="_blank" class="btn btn-info">Preview</a>
                        </div>
                    </div>
                    <form id="frmCenter" action="{{ url('bbadmin/centers/store') }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="{{ @$ecenter->id }}" />

                        <!-- Name -->
                        <div class="form-group mb-3">
                            <h5>Name <span class="text-danger">*</span></h5>
                            <input type="text" id="name" name="name" class="form-control" required
                                data-validation-required-message="This field is required"
                                value="{{ old('name', @$ecenter->name) }}" />
                            <small class="form-text text-muted">The name is how it appear on the site.</small>
                        </div>

                        <!-- Slug -->
                        <div class="form-group mb-3">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <input type="text" id="slug" name="slug" class="form-control" required
                                data-validation-required-message="This field is required"
                                value="{{ old('slug', @$ecenter->slug) }}" />
                            <small class="form-text text-muted">URL-friendly version of the name.</small>
                        </div>

                        <!-- Meta Title -->
                        <div class="form-group mb-3">
                            <h5>Meta Title</h5>
                            <input type="text" id="meta_title" name="meta_title" class="form-control"
                                value="{{ old('meta_title', @$ecenter->meta_title) }}" />
                        </div>

                        <!-- Meta Keywords -->
                        <div class="form-group mb-3">
                            <h5>Meta Keywords</h5>
                            <textarea id="keywords" name="keywords" class="form-control">{{ old('keywords', @$ecenter->keywords) }}</textarea>
                        </div>

                        <!-- Meta Description -->
                        <div class="form-group mb-3">
                            <h5>Meta Description</h5>
                            <textarea id="meta_description" name="meta_description" class="form-control">{{ old('meta_description', @$ecenter->meta_description) }}</textarea>
                        </div>

                        <!-- Year of Foundation -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h5>Year of Foundation</h5>
                                <select id="year_of_foundation" name="year_of_foundation" class="form-control select2" style="width: 100%">
                                    <option value="">Select Year</option>
                                    @for ($year = date('Y'); $year >= date('Y') - 50; $year--)
                                        <option value="{{ $year }}" {{ old('year_of_foundation', @$ecenter->year_of_foundation) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                <small class="form-text text-muted">When was the institute founded</small>
                            </div>

                            <!-- Founders -->
                            <div class="col-md-4">
                                <h5>Founders</h5>
                                <input type="text" id="founders" name="founders" class="form-control form-control-tag"
                                    data-role="tagsinput" value="{{ old('founders', @$ecenter->founders) }}" />
                            </div>

                            <!-- GPS -->
                            <div class="col-md-4">
                                <h5>GPS</h5>
                                <input type="text" id="gps" name="gps" class="form-control"
                                    value="{{ old('gps', @$ecenter->gps) }}" />
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <h5>Location</h5>
                            <select id="location[]" name="location[]" class="form-control select2-multiple" multiple="multiple" style="width: 100%">
                                <option value="">Select</option>
                                @if(@$categories)
                                    @foreach(@$categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ (in_array($category->id, old('location', @$center_locations))) ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" id="hdn_location_id" name="hdn_location_id" value="{{ implode('||', old('location', @$center_locations)) }}" />
                            <small class="form-text text-muted">As appears below the institutes name.</small>
                        </div>

                        <!-- Center Type -->
                        <div class="mb-3">
                            <h5>Type of Center</h5>
                            <select id="center_type" name="center_type" class="form-control select2">
                                <option value="">Select</option>
                                @if(@$center_types)
                                    @foreach(@$center_types as $center_type)
                                        <option value="{{ $center_type->id }}"
                                            {{ old('center_type', @$ecenter->center_type) == $center_type->id ? 'selected' : '' }}>
                                            {{ $center_type->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Speciality -->
                        <div class="mb-3">
                            <h5>Speciality</h5>
                            <select id="speciality_id[]" name="speciality_id[]" class="form-control select2-multiple" multiple="multiple">
                                @if(@$expertises)
                                    @foreach(@$expertises as $expertise)
                                        <option value="{{ $expertise->id }}"
                                            {{ (collect(old('speciality_id', explode('||', @$ecenter->speciality_id ?? '')))->contains($expertise->id)) ? 'selected' : '' }}>
                                            {{ $expertise->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small class="form-text text-muted">Appears on the speciality section on the top right</small>
                        </div>

                        <!-- About the center -->
                        <div class="mb-3">
                            <h5>About the center</h5>
                            <textarea id="about_center" name="about_center" class="textarea_editor form-control" rows="15" placeholder="Enter text ...">{{ old('about_center', @$ecenter->about_center) }}</textarea>
                        </div>

                        <!-- What sets us apart -->
                        <div class="mb-3">
                            <h5>What sets us apart</h5>
                            <textarea id="what_sets_us_apart" name="what_sets_us_apart" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('what_sets_us_apart', @$ecenter->what_sets_us_apart) }}</textarea>
                        </div>

                        <!-- Our Philosophy -->
                        <div class="mb-3">
                            <h5>Our Philosophy</h5>
                            <textarea id="our_philosophy" name="our_philosophy" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('our_philosophy', @$ecenter->our_philosophy) }}</textarea>
                        </div>

                        <!-- Our Mission -->
                        <div class="mb-3">
                            <h5>Our Mission</h5>
                            <textarea id="our_mission" name="our_mission" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('our_mission', @$ecenter->our_mission) }}</textarea>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-3">
                            <h5>Amenities</h5>
                            <div class="row">
                                @if(@$amenities)
                                    @foreach(@$amenities as $amenity)
                                        <div class="col-md-3">
                                            <input type="checkbox" id="amenities{{ $amenity->id }}" name="amenities[]" value="{{ $amenity->id }}"
                                                {{ (is_array(old('amenities', explode('||', @$ecenter->amenities ?? '')))) && in_array($amenity->id, old('amenities', explode('||', @$ecenter->amenities ?? ''))) ? 'checked' : '' }} />
                                            <label for="amenities{{ $amenity->id }}">{{ $amenity->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Airport Name and Pickup/Drop Cost -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <h5>Airport Name</h5>
                                <input type="text" id="airport_name" name="airport_name" class="form-control" value="{{ old('airport_name', @$ecenter->airport_name) }}" />
                            </div>
                            <div class="col-md-4">
                                <h5>Pickup and Drop Cost</h5>
                                <input type="text" id="pickup_drop_cost" name="pickup_drop_cost" class="form-control" value="{{ old('pickup_drop_cost', @$ecenter->pickup_drop_cost) }}" />
                            </div>
                        </div>

                        <!-- Center Highlights -->
                        <div class="mb-3">
                            <h5>Center Highlights</h5>
                            <input id="center_highlights" name="center_highlights" class="form-control form-control-tag" data-role="tagsinput" value="{{ old('center_highlights', @$ecenter->center_highlights) }}" />
                        </div>

                        <!-- Link to Teacher Profile -->
                        <div class="mb-3">
                            <h5>Link to Teacher Profile</h5>
                            <select id="teacher_id[]" name="teacher_id[]" class="form-control select2-multiple" multiple>
                                @if(@$teachers)
                                    @foreach(@$teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ (collect(old('teacher_id', @$center_teachers))->contains($teacher->id)) ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" id="hdn_teacher_id" name="hdn_teacher_id" value="{{ implode('||', old('teacher_id', @$center_teachers)) }}" />
                        </div>

                        <!-- Video URL -->
                        <div class="mb-3">
                            <h5>Video Url</h5>
                            <input type="text" name="video" id="video" class="form-control" value="{{ old('video', @$ecenter->video_url) }}" />
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

                        <!-- Banner Image -->
                        <div class="mb-3">
                            <h5>Banner Image</h5>
                            <input type="file" name="banner_image" id="banner_image" class="form-control" />
                            @if(@$ecenter->banner_image_url)
                                <div class="row" id="center_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('azure')->url($ecenter->banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('azure')->url($ecenter->banner_image_url) }}" alt="{{ $ecenter->banner_image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_delete" href="{{ url('bbadmin/centers/delete_image') }}" data-id="{{ $ecenter->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Image Gallery -->
                        <div class="mb-3">
                            <h5>Image gallery for Center</h5>
                            <div id="image_gallery" class="dropzone">
                                <div class="dz-message text-center">
                                    Upload Images (Click or Drag file here)
                                </div>
                                <input name="image_galleries" type="file" multiple style="display:none;" />
                            </div>
                            <input type="hidden" id="image_gallery_ids" name="image_gallery_ids" value="{{ old('image_gallery_ids', '') }}" />
                            <input type="hidden" id="dropzoneurl" value="{{ url('bbadmin/centers/upload_gallery_image') }}" />
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

                        <!-- Certifications -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Certifications</h5>
                                <select id="certificate_id" name="certificate_id[]" class="form-control select2-multiple" multiple>
                                    @if(@$certificates)
                                        @foreach(@$certificates as $certificate)
                                            <option value="{{ $certificate->id }}" {{ (is_array(old('certificate_id', explode('||', @$ecenter->certificate_id ?? '')))) && in_array($certificate->id, old('certificate_id', explode('||', @$ecenter->certificate_id ?? '')))  ? 'selected' : '' }}>{{ $certificate->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <!-- Awards -->
                            <div class="col-md-6">
                                <h5>Awards</h5>
                                <input id="awards" name="awards" class="form-control form-control-tag" data-role="tagsinput" value="{{ old('awards', @$ecenter->awards) }}" />
                            </div>
                        </div>

                        <!-- Internal Use Section -->
                        <div class="alert alert-info">
                            <h4>For Internal Use Only</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5>Address of the center</h5>
                                    <input type="text" id="address_of_center" name="address_of_center" class="form-control" value="{{ old('address_of_center', @$ecenter->address_of_center) }}" />
                                </div>
                                <div class="col-md-3">
                                    <h5>City</h5>
                                    <input type="text" id="city" name="city" class="form-control" value="{{ old('city', @$ecenter->city) }}" />
                                </div>
                                <div class="col-md-3">
                                    <h5>Country</h5>
                                    <input type="text" id="country" name="country" class="form-control" value="{{ old('country', @$ecenter->country) }}" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5>Email Address(s)</h5>
                                    <input type="text" id="email_address" name="email_address" class="form-control" value="{{ old('email_address', @$ecenter->email_address) }}" />
                                </div>
                                <div class="col-md-3">
                                    <h5>Contact Number</h5>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control" value="{{ old('contact_number', @$ecenter->contact_number) }}" />
                                </div>
                                <div class="col-md-3">
                                    <h5>Whatsapp Number</h5>
                                    <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', @$ecenter->whatsapp_number) }}" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5>Balancegurus Profile link</h5>
                                <input type="text" id="balancegurus_profile_link" name="balancegurus_profile_link" class="form-control" value="{{ old('balancegurus_profile_link', @$ecenter->balancegurus_profile_link) }}" />
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5>Tags</h5>
                                    <input type="text" id="tags" name="tags" class="form-control form-control-tag" data-role="tagsinput" value="{{ old('tags', @$ecenter->tags) }}" />
                                </div>
                                <div class="col-md-3">
                                    <h5>Category</h5>
                                    <input type="text" id="category_id" name="category_id" class="form-control" value="{{ old('category_id', @$ecenter->category_id) }}" />
                                </div>
                            </div>
                        </div>

                        <!-- Have Accommodation -->
                        <div class="mb-3">
                            <h5>Does the center have accommodation?</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="have_accomodation" id="have_accomodation_yes" value="Yes" {{ old('have_accomodation', @$ecenter->have_accomodation) == 'Yes' ? 'checked' : '' }}>
                                <label class="form-check-label" for="have_accomodation_yes">Yes</label>
                                <input class="form-check-input" type="radio" name="have_accomodation" id="have_accomodation_no" value="No" {{ old('have_accomodation', @$ecenter->have_accomodation) == 'No' ? 'checked' : '' }}>
                                <label class="form-check-label" for="have_accomodation_no">No</label>
                            </div>
                        </div>

                        <!-- Link to Accommodation -->
                        <div class="mb-3 acm-opt">
                            <h5>Link to Accommodation</h5>
                            <select id="accomodation_id[]" name="accomodation_id[]" class="form-control select2-multiple" multiple>
                                @if(@$accomodations)
                                    @foreach(@$accomodations as $accomodation)
                                        <option value="{{ $accomodation->id }}" {{ (in_array($accomodation->id, old('accomodation_id', explode('||', @$ecenter->accomodation_id ?? '')))) ? 'selected' : '' }}>{{ $accomodation->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" id="hdn_accomodation_id" name="hdn_accomodation_id" value="{{ implode('||', old('accomodation_id', explode('||', @$ecenter->accomodation_id ?? ''))) }}" />
                        </div>

                        <!-- Center Features -->
                        <div class="mb-3">
                            <h5>Center Features</h5>
                            <input id="center_features" name="center_features" class="form-control form-control-tag" data-role="tagsinput" value="{{ old('center_features', @$ecenter->center_features) }}" />
                        </div>

                        <!-- Accommodation Overview -->
                        <div class="mb-3">
                            <h5>Accommodation Overview</h5>
                            <textarea id="accomodation_overview" name="accomodation_overview" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('accomodation_overview', @$ecenter->accomodation_overview) }}</textarea>
                        </div>

                        <!-- Accommodation Image -->
                        <div class="mb-3">
                            <h5>Accommodation Image</h5>
                            <input type="file" name="accomodation_banner_image" id="accomodation_banner_image" class="form-control" />
                            @if(@$ecenter->accomodation_banner_image_url)
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

                        <!-- How to get there -->
                        <div class="mb-3">
                            <h5>How to get there</h5>
                            <textarea id="how_to_get_there" name="how_to_get_there" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('how_to_get_there', @$ecenter->how_to_get_there) }}</textarea>
                            <small class="form-text text-muted">By air, by road, by train, by bus</small>
                        </div>

                        <!-- Things to do around the center -->
                        <div class="mb-3">
                            <h5>Things to do around the center</h5>
                            <textarea id="things_to_do_around_the_center" name="things_to_do_around_the_center" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ old('things_to_do_around_the_center', @$ecenter->things_to_do_around_the_center) }}</textarea>
                            <small class="form-text text-muted">What are the famous sights around the center</small>
                        </div>

                        <!-- Owner Account -->
                        <div class="alert well alert-warning">
                            <h4>Owner Account</h4>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <h5>Owner Email</h5>
                                    <input type="email" id="owner_email" name="owner_email" class="form-control" placeholder="owner@example.com" 
                                    value="{{ old('owner_email', @$ecenter?->user?->email) }}" />
                                    <small class="form-text text-muted">Email for the owner login account.</small>
                                </div>
                                <div class="col-md-4">
                                    <h5>Owner Password</h5>
                                    <input type="password" id="owner_password" name="owner_password" class="form-control" placeholder="Leave blank to keep current" />
                                    <small class="form-text text-muted">Leave blank to keep existing password. Min 6 characters if changing.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <h5>Status</h5>
                            <select id="is_draft" name="is_draft" class="form-control select2">
                                <option value="0" {{ old('is_draft', @$ecenter->is_draft) == 0 ? 'selected' : '' }}>Publish</option>
                                <option value="1" {{ old('is_draft', @$ecenter->is_draft) == 1 ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ url('bbadmin/centers') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
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