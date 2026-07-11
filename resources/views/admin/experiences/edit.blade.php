@extends('admin.layouts.admin')
@section('title', 'Edit Experiences')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Edit Experience</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/experiences') }}">Experiences</a></li>
<li class="breadcrumb-item active">Edit Experience</li>
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
                    @if(Session::has('flash_message'))
                    <div class="container">      
                        <div class="alert alert-success">
                            <em> {!! session('flash_message') !!}</em>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                    </div>
                    @endif 
                    @if(Session::has('flash_error_message'))
                    <div class="container">      
                        <div class="alert alert-danger">
                            <em> {!! session('flash_error_message') !!}</em>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                    </div>
                    @endif 
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <h5>Last Updated At</h5>
                                <code>{!! $eexperience->updated_at?->format('d M, Y h:i A') !!}</code>
                            </div>
                        </div>
                        <div class="col-6" align="right">
                            <a href="{{ url('/experience/'.(@$eexperience->slug).'?preview=1') }}" target="_blank" class="btn btn-info pull-right">Preview</a>
                        </div>
                    </div>
                    <form id="frmCenter" action="{{ url("bbadmin/experiences/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id" value="<?php echo @$eexperience->id; ?>" />
                        <div class="form-group">
                            <h5>Title of Experience <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="name" name="name" class="form-control" required data-validation-required-message="This field is required" value="{{ @$eexperience->name }}" /> </div>
                            <div class="form-control-feedback"><small>The name is how it appear on the site.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Slug <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="slug" name="slug" class="form-control" required data-validation-required-message="This field is required" value="{{ @$eexperience->slug }}" /> </div>
                            <div class="form-control-feedback"><small>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</small></div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Title</h5>
                            <div class="controls">
                                <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ @$eexperience->meta_title }}" /> 
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Description</h5>
                            <div class="controls">
                                <textarea id="meta_description" name="meta_description" class="form-control">{{ @$eexperience->meta_description }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Meta Keywords</h5>
                            <div class="controls">
                                <textarea id="keywords" name="keywords" class="form-control">{{ @$eexperience->keywords }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <h5>Tags</h5>
                                    <div class="controls">
                                        <input name="tags" id="tags" class="form-control" data-placeholder="" data-role="tagsinput" value="{{ old('tags', $eexperience->tags) }}" />
                                    </div>
                                    <em>Use comma(,) to add mulitple values</em>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>GPS</h5>
                                    <div class="controls">
                                        <input name="gps" id="gps" class="form-control" value="{{ old('gps', $eexperience->gps) }}" />
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Experience Category</h5>
                                    <div class="controls">
                                        <select id="experience_category_id[]" name="experience_category_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$categories) {
                                                foreach (@$categories as $category) {
                                                    if ($category->type == 0 && $category->parent == 0) {
                                                        ?>
                                                        <option value="<?php echo $category->id; ?>" <?php echo (in_array($category->id, @$experience_categories)) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Experience Sub Category</h5>
                                    <div class="controls">
                                        <select id="experience_category_id[]" name="experience_category_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$categories) {
                                                foreach (@$categories as $category) {
                                                    if ($category->type == 0 && $category->parent > 0) {
                                                        ?>
                                                        <option value="<?php echo $category->id; ?>" <?php echo (in_array($category->id, @$experience_categories)) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
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
                                <div class="col-md-6">
                                    <h5>Experience Destination</h5>
                                    <div class="controls">
                                        <select id="experience_category_id[]" name="experience_category_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$categories) {
                                                foreach (@$categories as $category) {
                                                    if ($category->type == 1 && $category->parent == 0) {
                                                        ?>
                                                        <option value="<?php echo $category->id; ?>" <?php echo (in_array($category->id, @$experience_categories)) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Experience Sub Destination</h5>
                                    <div class="controls">
                                        <select id="experience_category_id[]" name="experience_category_id[]" class="form-control select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="">
                                            <?php
                                            if (@$categories) {
                                                foreach (@$categories as $category) {
                                                    if ($category->type == 1 && $category->parent > 0) {
                                                        ?>
                                                        <option value="<?php echo $category->id; ?>" <?php echo (in_array($category->id, @$experience_categories)) ? "selected" : ""; ?>><?php echo $category->name; ?></option>
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
                                    <h5>Experience Duration(In Days)</h5>
                                    <div class="controls">
                                        <input type="text" id="duration" name="duration" class="form-control" value="{{ @$eexperience->duration }}" /> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>Avg Price</h5>
                                    <div class="controls">
                                        <input type="text" id="avg_price" name="avg_price" class="form-control" value="{{ @$eexperience->avg_price }}" /> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>Currency</h5>
                                    <div class="controls">
                                        <select id="exp_currency" name="exp_currency" class="form-control select2" style="width: 100%" data-placeholder="Select">
                                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                            <option value="{{ $currency }}" <?php echo (@$eexperience->currency == $currency) ? "selected" : ""; ?> >{{ $currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>    
                        </div>
                        <div class="alert well alert-primary">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Experience Duration(In Days)</h5>
                                </div>
                                <div class="col-md-3">
                                    <h5>Price</h5>
                                </div>
                                <div class="col-md-3">
                                    <h5>Prom. Price</h5>
                                </div>
                                <div class="col-md-2">
                                    <h5>Currency</h5>
                                </div>
                            </div>
                            @for($dr=0;$dr<=4;$dr++)
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="text" id="durations[]" name="durations[]" class="form-control" placeholder="Experience Duration" value="<?php echo @$experience_duration_prices[$dr]->duration;?>" /> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="text" id="duration_price[]" name="duration_price[]" class="form-control" placeholder="Price" value="<?php echo @$experience_duration_prices[$dr]->price;?>" /> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="text" id="promo_price[]" name="promo_price[]" class="form-control" placeholder="Prom. Price" value="<?php echo @$experience_duration_prices[$dr]->promo_price;?>" /> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="controls">
                                        <select id="duration_currency[]" name="duration_currency[]" class="form-control select2" style="width: 100%" data-placeholder="Select">
                                            @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                            <option value="{{ $currency }}" <?php echo (@$experience_duration_prices[$dr]->currency == $currency) ? "selected" : ""; ?> >{{ $currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div> 
                        <input type="hidden" id="hdn_exp_cat_id" name="hdn_exp_cat_id" value="<?php echo implode("||", @$experience_categories); ?>" />
                        <div class="form-group">
                            <h5>Link to Center</h5>
                            <div class="controls">
                                <select id="center_id" name="center_id" class="form-control select2 form-control-select2" data-placeholder="">
                                    <option value="">Select</option>
                                    <?php
                                    if (@$centers) {
                                        foreach (@$centers as $center) {
                                            ?>
                                            <option value="<?php echo @$center->id; ?>" data-have-accomodation="<?php echo @$center->have_accomodation; ?>" <?php echo (@$center->id == @$eexperience->center_id) ? "selected" : ""; ?>><?php echo @$center->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
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
                                            <option value="<?php echo $teacher->id; ?>" <?php echo (in_array($teacher->id, @$experience_teachers)) ? "selected" : ""; ?>><?php echo $teacher->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="hdn_teacher_id" name="hdn_teacher_id" value="<?php echo implode("||", @$experience_teachers); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Bookable</h5>
                                    <div class="controls">
                                        <select id="is_bookable" name="is_bookable" class="form-control select2 form-control-select2">
                                            <option value="1" <?php echo (@$eexperience->is_bookable == 1) ? "selected" : ""; ?>>Yes</option>
                                            <option value="0" <?php echo (@$eexperience->is_bookable == 0) ? "selected" : ""; ?>>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $k = 1;
                        if (sizeof($experience_accomodations)) {
                            foreach ($experience_accomodations as $experience_accomodation) {
                                ?>
                                <div class="alert well alert-primary acm-opt">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <h5>Accommodation Option {{ $k }}</h5>
                                                <div class="controls">
                                                    <select id="accomodation_title[]" name="accomodation_title[]" class="form-control acmselect2 form-control-select2" data-placeholder="Select">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if (@$center_accomodations) {
                                                            foreach (@$center_accomodations as $center_accomodation) {
                                                                ?>
                                                                <option value="<?php echo $center_accomodation->id; ?>" <?php echo ($experience_accomodation->title == $center_accomodation->id) ? "selected" : ""; ?>><?php echo $center_accomodation->name; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <input type="hidden" id="center_accomodation_id[]" name="center_accomodation_id[]" class="form-control" value="<?php echo $experience_accomodation->id; ?>" />
                                                    <input type="hidden" id="price_per_night_per_guest[]" name="price_per_night_per_guest[]" class="form-control" value="<?php echo $experience_accomodation->price_per_night_per_guest; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <?php /*<div class="col-md-3">
                                            <div class="form-group">
                                                <h5>Price per night per guest</h5>
                                                <div class="controls">
                                                    <input type="text" id="price_per_night_per_guest[]" name="price_per_night_per_guest[]" class="form-control" value="<?php echo $experience_accomodation->price_per_night_per_guest; ?>" />
                                                </div>
                                            </div>
                                        </div>*/?>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>Currency</h5>
                                                <div class="controls">
                                                    <select id="currency[]" name="currency[]" class="form-control select2" style="width: 100%" data-placeholder="Select">
                                                        @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                                        <option value="{{ $currency }}" <?php echo ($experience_accomodation->currency == $currency) ? "selected" : ""; ?> >{{ $currency }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group text-center">
                                                <h5>Default</h5>
                                                <div class="controls">
                                                    <div class="radio radio-primary text-center">
                                                        <input type="radio" id="accomodation_default{{ $k }}" name="accomodation_default" value="1-{{ $k-1 }}" {{ (@$experience_accomodation->accomodation_default == 1) ? "checked='checked'" : "" }} />
                                                        <label for="accomodation_default{{ $k }}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $exacm = 0;
                                    if (sizeof($experience_accomodation_prices)) {
                                        foreach ($experience_accomodation_prices as $experience_accomodation_price) {
                                            if ($experience_accomodation_price->accomodation_id == $experience_accomodation->title) {
                                                ?>
                                                <div class="row" id="accm_{{ $exacm }}">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <h5>Days</h5>
                                                            <input type="text" id="accomodation_durations[{{ $k - 1 }}][]" name="accomodation_durations[{{ $k - 1 }}][]" class="form-control" placeholder="" value="<?php echo @$experience_accomodation_price->duration;?>" /> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <h5>Date Range</h5>
                                                            <input type="text" id="accomodation_daterange[{{ $k - 1 }}][]" name="accomodation_daterange[{{ $k - 1 }}][]" class="form-control clsdaterangepicker" value="{{ (\Carbon\Carbon::parse($experience_accomodation_price->start_date)->format("m/d/Y"))." - ".(\Carbon\Carbon::parse($experience_accomodation_price->end_date)->format("m/d/Y")) }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <h5>Price per night per guest</h5>
                                                            <div class="controls">
                                                                <input type="text" id="accomodation_price[{{ $k - 1 }}][]" name="accomodation_price[{{ $k - 1 }}][]" class="form-control" value="{{ $experience_accomodation_price->price_per_night_per_guest }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <h5>Prom. Price</h5>
                                                            <div class="controls">
                                                                <input type="text" id="accomodation_promotional_price[{{ $k - 1 }}][]" name="accomodation_promotional_price[{{ $k - 1 }}][]" class="form-control" value="{{ $experience_accomodation_price->promotional_price }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <h5>Prom. Discount</h5>
                                                            <div class="controls">
                                                                <input type="text" id="accomodation_promotional_discount[{{ $k - 1 }}][]" name="accomodation_promotional_discount[{{ $k - 1 }}][]" class="form-control" value="{{ $experience_accomodation_price->promotional_discount }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="experience_accomodation_price_id[{{ $k - 1 }}][]" name="experience_accomodation_price_id[{{ $k - 1 }}][]" class="form-control" value="<?php echo $experience_accomodation_price->id; ?>" />
                                                <?php
                                                $exacm++;
                                            }
                                        }
                                    }

                                    for ($acm = $exacm; $acm <= 11; $acm++) {
                                        ?>
                                        <div class="row" id="accm_{{ $acm }}">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <h5>Days</h5>
                                                    <input type="text" id="accomodation_durations[{{ $k - 1 }}][]" name="accomodation_durations[{{ $k - 1 }}][]" class="form-control" placeholder="" value="" /> 
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h5>Date Range</h5>
                                                    <input type="text" id="accomodation_daterange[{{ $k - 1 }}][]" name="accomodation_daterange[{{ $k - 1 }}][]" class="form-control clsdaterangepicker" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h5>Price per night per guest</h5>
                                                    <div class="controls">
                                                        <input type="text" id="accomodation_price[{{ $k - 1 }}][]" name="accomodation_price[{{ $k - 1 }}][]" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <h5>Prom. Price</h5>
                                                    <div class="controls">
                                                        <input type="text" id="accomodation_promotional_price[{{ $k - 1 }}][]" name="accomodation_promotional_price[{{ $k - 1 }}][]" class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <h5>Prom. Discount</h5>
                                                    <div class="controls">
                                                        <input type="text" id="accomodation_promotional_discount[{{ $k - 1 }}][]" name="accomodation_promotional_discount[{{ $k - 1 }}][]" class="form-control" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                                $k++;
                            }
                        }

                        for ($j = $k; $j <= 10; $j++) {
                            ?>
                            <div class="alert well alert-primary acm-opt">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <h5>Accomodation Option <?php echo $j; ?></h5>
                                            <div class="controls">
                                                <select id="accomodation_title[]" name="accomodation_title[]" class="form-control select2 acmselect2 form-control-select2" data-placeholder="Select">
                                                    <option value="">Select</option>
                                                    <?php
                                                    if (@$center_accomodations) {
                                                        foreach (@$center_accomodations as $center_accomodation) {
                                                            ?>
                                                            <option value="<?php echo $center_accomodation->id; ?>"><?php echo $center_accomodation->name; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" id="center_accomodation_id[]" name="center_accomodation_id[]" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Price per night per guest</h5>
                                            <div class="controls">
                                                <input type="text" id="price_per_night_per_guest[]" name="price_per_night_per_guest[]" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <h5>Currency</h5>
                                            <div class="controls">
                                                <select id="currency[]" name="currency[]" class="form-control select2" style="width: 100%" data-placeholder="Select">
                                                    @foreach(\App\Http\Helpers\CommonHelper::get_currency() as $currency)
                                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group text-center">
                                            <h5>Default</h5>    
                                            <div class="controls">
                                                <div class="radio radio-primary text-center">
                                                    <input type="radio" id="accomodation_default{{ $j }}" name="accomodation_default" value="1-{{ $j-1 }}" />
                                                    <label for="accomodation_default{{ $j }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php for ($acm = 0; $acm <= 4; $acm++) { ?>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>Days</h5>
                                                <input type="text" id="accomodation_durations[{{ $j - 1 }}][]" name="accomodation_durations[{{ $j - 1 }}][]" class="form-control" placeholder="Duration" /> 
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>Date Range</h5>
                                                <input type="text" id="accomodation_daterange[{{ $j - 1 }}][]" name="accomodation_daterange[{{ $j - 1 }}][]" class="form-control clsdaterangepicker" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h5>Price per night per guest</h5>
                                                <div class="controls">
                                                    <input type="text" id="accomodation_price[{{ $j - 1 }}][]" name="accomodation_price[{{ $j - 1 }}][]" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>Prom. Price</h5>
                                                <div class="controls">
                                                    <input type="text" id="accomodation_promotional_price[{{ $j - 1 }}][]" name="accomodation_promotional_price[{{ $j - 1 }}][]" class="form-control" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <h5>Promo. Discount</h5>
                                                <div class="controls">
                                                    <input type="text" id="accomodation_promotional_discount[{{ $j - 1 }}][]" name="accomodation_promotional_discount[{{ $j - 1 }}][]" class="form-control" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h5>Batch size</h5>
                                    <div class="controls">
                                        <select id="batch_size" name="batch_size" class="form-control select2 form-control-select2" data-placeholder="Select">
                                            <option value="">Select</option>
                                            <?php for ($bs = 1; $bs <= 100; $bs++) { ?>
                                                <option value="<?php echo $bs; ?>" {{ (@$eexperience->batch_size == $bs) ? "selected" : "" }}><?php echo $bs; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-control-feedback"><small>As appears below the institutes name</small></div>
                                </div>
                            </div>
                        </div>

                        <?php /*<h3>Date & Time</h3>
                        <div class="alert well alert-info">
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <h5></h5>
                                    <div class="controls">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="is_full_day_event" name="is_full_day_event" value="1" {{ (@$eexperience->is_full_day_event) ? "checked='checked'" : "" }} />
                                            <label for="is_full_day_event">Is Full Day Experience?</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>Start Date/Time</h5>
                                    <div class="controls">
                                        <input type="text" id="start_date_time" name="start_date_time" class="form-control datetimepicker" value="{{ (@$eexperience->start_date_time) ? Carbon\Carbon::parse(@$eexperience->start_date_time)->format("m/d/Y h:i a") : "" }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>End Date/Time</h5>
                                    <div class="controls">
                                        <input type="text" id="end_date_time" name="end_date_time" class="form-control datetimepicker" value="{{ (@$eexperience->end_date_time) ? Carbon\Carbon::parse(@$eexperience->end_date_time)->format("m/d/Y h:i a") : "" }}" />
                                    </div>
                                </div>                            
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <h5>Recurrence</h5>
                                    <div class="controls">
                                        <select id="recurring_type" name="recurring_type" class="form-control">
                                            <option value="">None</option>
                                            <option value="Daily" {{ (@$experience_recurring->recurring_type == "Daily") ? "selected" :"" }}>Daily</option>
                                            <option value="Weekly" {{ (@$experience_recurring->recurring_type == "Weekly") ? "selected" :"" }}>Weekly</option>
                                            <option value="Monthly" {{ (@$experience_recurring->recurring_type == "Monthly") ? "selected" :"" }}>Monthly</option>
                                            <option value="Yearly" {{ (@$experience_recurring->recurring_type == "Yearly") ? "selected" :"" }}>Yearly</option>
                                            <option value="Manually" {{ (sizeof(@$experience_recurring_manually) > 0) ? "selected" :"" }}>Manually</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 recurring recurring_end_date" style="display: {{ (@$eexperience->is_recurring == 1) ? "block":"none" }};">
                                    <h5>End on </h5>
                                    <div class="controls">
                                        <input type="text" id="recurring_end_date" name="recurring_end_date" class="form-control datetimepicker" value="{{ (@$experience_recurring->recurring_end_date) ? Carbon\Carbon::parse(@$experience_recurring->recurring_end_date)->format("m/d/Y") : "" }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group recurring Weekly-recurring Monthly-recurring Yearly-recurring" style="display: {{ (@$eexperience->is_recurring == 1) ? "block":"none" }};">
                                <h5>Day of Week</h5>
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <?php
                                        $timestamp = strtotime('next Sunday');
                                        for ($day = 1; $day <= 7; $day++) {
                                            $timestamp = strtotime('+1 day', $timestamp);
                                            ?>
                                            <input type="checkbox" id="day_of_week{{ $day }}" name="day_of_week[]" value="{{ $day }}" {{ (!empty(@$experience_recurring->day_of_week) && in_array($day, explode(",", @$experience_recurring->day_of_week))) ? "checked='checked'" : "" }} />
                                            <label for="day_of_week{{ $day }}">{{ strftime('%A', $timestamp) }}</label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group recurring Monthly-recurring Yearly-recurring" style="display: {{ (@$eexperience->is_recurring == 1) ? "block":"none" }};">
                                <h5>Week of Month</h5>
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <?php
                                        for ($wk = 1; $wk <= 5; $wk++) {
                                            ?>
                                            <input type="checkbox" id="week_of_month{{ $wk }}" name="week_of_month[]" value="{{ $wk }}" {{ (!empty(@$experience_recurring->week_of_month) && in_array($wk, explode(",", @$experience_recurring->week_of_month))) ? "checked='checked'" : "" }} />
                                            <label for="week_of_month{{ $wk }}">{{ $wk }}</label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group recurring Monthly-recurring Yearly-recurring" style="display: {{ (@$eexperience->is_recurring == 1) ? "block":"none" }};">
                                <h5>Day of Month</h5>
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <?php
                                        for ($day = 1; $day <= 31; $day++) {
                                            ?>
                                            <input type="checkbox" id="day_of_month{{ $day }}" name="day_of_month[]" value="{{ $day }}" {{ (!empty(@$experience_recurring->day_of_month) && in_array($day, explode(",", @$experience_recurring->day_of_month))) ? "checked='checked'" : "" }} />
                                            <label for="day_of_month{{ $day }}">{{ $day }}</label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group recurring Yearly-recurring" style="display: {{ (@$eexperience->is_recurring == 1) ? "block":"none" }};">
                                <h5>Month of Year</h5>
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <?php
                                        for ($mnth = 1; $mnth <= 12; $mnth++) {
                                            ?>
                                            <input type="checkbox" id="month_of_year{{ $mnth }}" name="month_of_year[]" value="{{ $mnth }}" {{ (!empty(@$experience_recurring->month_of_year) && in_array($mnth, explode(",", @$experience_recurring->month_of_year))) ? "checked='checked'" : "" }} />
                                            <label for="month_of_year{{ $mnth }}">{{ date("F", mktime(0, 0, 0, $mnth, 1)) }}</label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group recurring Manually-recurring" style="display: {{ (@$eexperience->is_recurring == 1) ? "block":"none" }};">
                                <?php
                                $emdt = 1;
                                if (sizeof(@$experience_recurring_manually) > 0) {
                                    foreach (@$experience_recurring_manually as $experience_recurring_manually_data) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>Start Date/Time</h5>
                                                    <div class="controls">
                                                        <input type="text" id="manually_start_date_time{{ $emdt }}" name="manually_start_date_time[]" class="form-control datetimepicker" value="{{ (@$experience_recurring_manually_data->start_date) ? Carbon\Carbon::parse(@$experience_recurring_manually_data->start_date)->format("m/d/Y h:i a") : "" }}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <h5>End Date/Time</h5>
                                                    <div class="controls">
                                                        <input type="text" id="manually_end_date_time{{ $emdt }}" name="manually_end_date_time[]" class="form-control datetimepicker" value="{{ (@$experience_recurring_manually_data->end_date) ? Carbon\Carbon::parse(@$experience_recurring_manually_data->end_date)->format("m/d/Y h:i a") : "" }}" />
                                                    </div>
                                                </div>
                                            </div>     
                                        </div>
                                        <?php
                                        $emdt++;
                                    }
                                }

                                for ($mdt = $emdt; $mdt <= 8; $mdt++) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>Start Date/Time</h5>
                                                <div class="controls">
                                                    <input type="text" id="manually_start_date_time{{ $mdt }}" name="manually_start_date_time[]" class="form-control datetimepicker" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <h5>End Date/Time</h5>
                                                <div class="controls">
                                                    <input type="text" id="manually_end_date_time{{ $mdt }}" name="manually_end_date_time[]" class="form-control datetimepicker" value="" />
                                                </div>
                                            </div>
                                        </div>     
                                    </div>
                                <?php } ?>
                            </div>
                        </div>*/?>
                        <div class="form-group">
                            <h5>Experience Summary</h5>
                            <div class="controls">
                                <textarea id="experience_summary" name="experience_summary" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->experience_summary }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Experience Certification if any</h5>
                            <div class="controls">
                                <select id="experience_certification[]" name="experience_certification[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <option value="">Select</option>
                                    <?php
                                    if (@$certificates) {
                                        foreach (@$certificates as $certificate) {
                                            ?>
                                            <option value="<?php echo $certificate->id; ?>" <?php echo (in_array($certificate->id, explode("||", @$eexperience->experience_certification_id))) ? "selected" : ""; ?>><?php echo $certificate->name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Language Spoken</h5>
                            <div class="controls">
                                <select id="language_spoken[]" name="language_spoken[]" class="form-control select2-multiple form-control-select2" multiple="multiple" data-placeholder="">
                                    <option value="">Select</option>
                                    <option value="English" <?php echo (in_array("English", explode("||", @$eexperience->language_spoken))) ? "selected" : ""; ?>>English</option>
                                    <option value="Hindi" <?php echo (in_array("Hindi", explode("||", @$eexperience->language_spoken))) ? "selected" : ""; ?>>Hindi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Experience Overview</h5>
                            <div class="controls">
                                <textarea id="experience_overview" name="experience_overview" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->experience_overview }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Video Url</h5>
                            <div class="controls">
                                <input type="text" name="video" id="video" class="form-control" value="{{ @$eexperience->video_url }}" />
                                @if(@$elisting->video_url)
                                <div class="row" id="exp_vid_container">
                                    <div class="col-md-4 m-t-10">
                                        <div class="card">
                                            <iframe width="auto" height="180" src="{{ @$eexperience->video_url }}" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Thumbnail Image</h5>
                            <div class="controls">
                                <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control" />
                                @if(@$eexperience->thumbnail_image_url)
                                <div class="row" id="exp_thumb_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url(@$eexperience->thumbnail_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ strtok(Storage::disk('s3')->url(@$eexperience->thumbnail_image_url),'?') }}" alt="{{ @$eexperience->thumbnail_image_url }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_thumbnail_delete" href="{{ url('bbadmin/experiences/delete_thumbnail_image') }}" data-id="{{ @$eexperience->id }}" class="btn btn-danger">Remove</a>
                                            </div>
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
                                @if(@$eexperience->banner_image_url)
                                <div class="row" id="exp_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url(@$eexperience->banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ strtok(Storage::disk('s3')->url(@$eexperience->banner_image_url),'?') }}" alt="{{ @$eexperience->banner_image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_delete" href="{{ url('bbadmin/experiences/delete_image') }}" data-id="{{ @$eexperience->id }}" class="btn btn-danger">Remove</a>
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
                                <input type="hidden" id="dropzoneurl" value="{{ url("bbadmin/experiences/upload_gallery_image") }}" />
                                <div class="row">
                                    @if(@$imagegalleries)
                                    @foreach(@$imagegalleries as $gallery)
                                    @if ($gallery->image_url)
                                    <div class="col-md-3 m-t-10" id="img-{{ $gallery->id }}">
                                        <div class="card">
                                            <a href="{{ strtok(Storage::disk('s3')->url($gallery->image_url), '?') }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ strtok(Storage::disk('s3')->url($gallery->image_url), '?') }}" alt="{{ $gallery->image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="gallery_img_delete" href="{{ url('bbadmin/experiences/delete_gallery_image') }}" data-id="{{ $gallery->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Styles taught (if it is a training experience)</h5>
                            <div class="controls">
                                <input id="styles_taught" name="styles_taught" class="form-control form-control-tag" data-role="tagsinput" value="{{ @$eexperience->styles_taught }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Food</h5>
                            <div class="controls">
                                <input id="food" name="food" class="form-control form-control-tag" data-role="tagsinput" value="{{ @$eexperience->food }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Skill Level</h5>
                            <div class="controls">
                                <input id="skill_level" name="skill_level" class="form-control form-control-tag" data-role="tagsinput" value="{{ @$eexperience->skill_level }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Area</h5>
                            <div class="controls">
                                <input id="area" name="area" class="form-control form-control-tag" data-role="tagsinput" value="{{ @$eexperience->area }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Atmosphere</h5>
                            <div class="controls">
                                <input id="atmosphere" name="atmosphere" class="form-control form-control-tag" data-role="tagsinput" value="{{ @$eexperience->atmosphere }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Schedule</h5>
                            <div class="controls">
                                <textarea id="experience_schedule" name="experience_schedule" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->schedule }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Experience Details</h5>
                            <div class="controls">
                                <textarea id="experience_details" name="experience_details" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->experience_details }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Experience Highlights</h5>
                            <div class="controls">
                                <input id="experience_highlights" name="experience_highlights" class="form-control form-control-tag" data-role="tagsinput" value="{{ @$eexperience->experience_highlights }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Food Overview</h5>
                            <div class="controls">
                                <textarea id="food_overview" name="food_overview" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->food_overview }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Food Banner Image</h5>
                            <div class="controls">
                                <input type="file" name="food_banner_image" id="food_banner_image" class="form-control" />
                                @if(@$eexperience->food_banner_image_url)
                                <div class="row" id="exp_food_img_container">
                                    <div class="col-md-3 m-t-10">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url(@$eexperience->food_banner_image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ Storage::disk('s3')->url(@$eexperience->food_banner_image_url) }}" alt="{{ @$eexperience->food_banner_image_url }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="img_food_delete" href="{{ url('bbadmin/experiences/delete_food_image') }}" data-id="{{ @$eexperience->id }}" class="btn btn-danger">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Food Image gallery</h5>
                            <div class="controls">
                                <div id="food_image_gallery" class="dropzone">
                                    <div class="dz-message text-center">
                                        Upload Images (Click or Drag file here)
                                    </div>
                                    <input name="food_image_galleries" type="file" multiple style="display:none;" />
                                </div>
                                <input type="hidden" id="food_image_gallery_ids" name="food_image_gallery_ids" value="" />
                                <input type="hidden" id="fooddropzoneurl" value="{{ url("bbadmin/experiences/upload_gallery_image") }}" />
                                <div class="row">
                                    @if(@$foodimagegalleries)
                                    @foreach(@$foodimagegalleries as $gallery)
                                    @if($gallery->image_url)
                                    <div class="col-md-3 m-t-10" id="img-food-{{ $gallery->id }}">
                                        <div class="card">
                                            <a href="{{ Storage::disk('s3')->url($gallery->image_url) }}" target="_blank">
                                                <img class="card-img-top img-responsive" src="{{ strtok(Storage::disk('s3')->url($gallery->image_url), '?') }}" alt="{{ $gallery->image_title }}">
                                            </a>
                                            <div class="card-body">
                                                <a id="food_gallery_img_delete" href="{{ url('bbadmin/experiences/delete_food_gallery_image') }}" data-id="{{ $gallery->id }}" class="btn btn-danger food_gallery_img_delete">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>What is included</h5>
                            <div class="controls">
                                <textarea id="what_is_included" name="what_is_included" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->what_is_included }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>What is not included</h5>
                            <div class="controls">
                                <textarea id="what_is_not_included" name="what_is_not_included" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->what_is_not_included }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>How to Get Here</h5>
                            <div class="controls">
                                <textarea id="how_to_get_here" name="how_to_get_here" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->how_to_get_here }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Early Bird Offer</h5>    
                            <div class="controls">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <h5>Before specified number of days</h5>
                                            <div class="controls">
                                                <input id="eirly_bird_before_days" name="eirly_bird_before_days" class="form-control" value="{{ (@$eexperience->eirly_bird_before_days) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Discount</h5>
                                            <div class="controls">
                                                <input type="text" id="eirly_bird_discount" name="eirly_bird_discount" class="form-control" value="{{ (@$eexperience->eirly_bird_discount) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Discount Type</h5>
                                            <div class="controls">
                                                <select id="eirly_bird_discount_type" name="eirly_bird_discount_type" class="form-control">
                                                    <option value="per" {{ (@$eexperience->eirly_bird_discount_type == "per") ? "selected":"" }}>(%)</option>
                                                    <option value="amt" {{ (@$eexperience->eirly_bird_discount_type == "amt") ? "selected":"" }}>Amount</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Offer</h5>    
                            <div class="controls">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Start Date</h5>
                                            <div class="controls">
                                                <input id="offer_start_date" name="offer_start_date" class="form-control clssingledatepicker" value="{{ (@$eexperience->offer_start_date) ? \Carbon\Carbon::parse(@$eexperience->offer_start_date)->format("m/d/Y") : "" }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>End Date</h5>
                                            <div class="controls">
                                                <input id="offer_end_date" name="offer_end_date" class="form-control clssingledatepicker" value="{{ (@$eexperience->offer_end_date) ? \Carbon\Carbon::parse(@$eexperience->offer_end_date)->format("m/d/Y") : "" }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Discount</h5>
                                            <div class="controls">
                                                <input type="text" id="offer_discount" name="offer_discount" class="form-control" value="{{ (@$eexperience->offer_discount) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h5>Discount Type</h5>
                                            <div class="controls">
                                                <select id="offer_discount_type" name="offer_discount_type" class="form-control">
                                                    <option value="per" {{ (@$eexperience->offer_discount_type == "per") ? "selected":"" }}>(%)</option>
                                                    <option value="amt" {{ (@$eexperience->offer_discount_type == "amt") ? "selected":"" }}>Amount</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Booking Info</h5>
                            <div class="controls">
                                <textarea id="booking_info" name="booking_info" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->booking_info }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Cancellation Policy</h5>
                            <div class="controls">
                                <textarea id="cancellation_policy" name="cancellation_policy" class="textarea_editor form-control" rows="10" placeholder="Enter text ...">{{ @$eexperience->cancellation_policy }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Deposit policy</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <input type="radio" id="deposit_policy1" name="deposit_policy" value="1" {{ (@$eexperience->deposit_policy == 1) ? "checked='checked'" : "" }} />
                                        <label for="deposit_policy1">Full price</label>
                                        <input type="radio" id="deposit_policy2" name="deposit_policy" value="2" {{ (@$eexperience->deposit_policy == 2) ? "checked='checked'" : "" }} />
                                        <label for="deposit_policy2">Fixed amount</label>
                                        <input type="radio" id="deposit_policy3" name="deposit_policy" value="3" {{ (@$eexperience->deposit_policy == 3) ? "checked='checked'" : "" }} />
                                        <label for="deposit_policy3">Percentage of the listing price</label>
                                    </div>                                
                                </div>
                                <div class="controls" id="dv_deposit_amount" style="display: <?php echo (@$eexperience->deposit_policy >= 2) ? "block" : "none"; ?>;">
                                    <input id="deposit_amount" name="deposit_amount" class="form-control col-md-2" value="{{ (@$eexperience->deposit_amount) }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Cancellation((Refund) policy</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <input type="radio" id="cancellation_policy_condition1" name="cancellation_policy_condition" value="1" {{ (@$eexperience->cancellation_policy_condition == 1) ? "checked='checked'" : "" }} />
                                        <label for="cancellation_policy_condition1">Non Refundable</label>
                                        <input type="radio" id="cancellation_policy_condition2" name="cancellation_policy_condition" value="2" {{ (@$eexperience->cancellation_policy_condition == 2) ? "checked='checked'" : "" }} />
                                        <label for="cancellation_policy_condition2">Always refundable</label>
                                        <input type="radio" id="cancellation_policy_condition3" name="cancellation_policy_condition" value="3" {{ (@$eexperience->cancellation_policy_condition == 3) ? "checked='checked'" : "" }} />
                                        <label for="cancellation_policy_condition3">Refundable before specified number of days before arrival date</label>
                                    </div>                                
                                </div>
                                <div class="controls" id="dv_cancellation_policy_days"  style="display: <?php echo (@$eexperience->cancellation_policy_condition == 3) ? "block" : "none"; ?>;">
                                    <input id="cancellation_policy_days" name="cancellation_policy_days" class="form-control col-md-2" value="{{ (@$eexperience->cancellation_policy_days) }}" />
                                    <div class="form-control-feedback"><small>days before arrival</small></div>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group">
                            <h5>The rest of payment</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <div class="checkbox checkbox-primary">
                                        <input type="radio" id="rest_of_payment1" name="rest_of_payment" value="1" {{ (@$eexperience->rest_of_payment == 1) ? "checked='checked'" : "" }} />
                                        <label for="rest_of_payment1">On arrival</label>
                                        <input type="radio" id="rest_of_payment2" name="rest_of_payment" value="2" {{ (@$eexperience->rest_of_payment == 2) ? "checked='checked'" : "" }} />
                                        <label for="rest_of_payment2">On departure</label>
                                        <input type="radio" id="rest_of_payment3" name="rest_of_payment" value="3" {{ (@$eexperience->rest_of_payment == 3) ? "checked='checked'" : "" }} />
                                        <label for="rest_of_payment3">Specified number of days before arrival</label>
                                    </div>                                
                                </div>
                                <div class="controls" id="dv_rest_of_payment_days" style="display: <?php echo (@$eexperience->rest_of_payment == 3) ? "block" : "none"; ?>;">
                                    <input id="rest_of_payment_days" name="rest_of_payment_days" class="form-control col-md-2" value="{{ (@$eexperience->rest_of_payment_days) }}" />
                                    <div class="form-control-feedback"><small>days before arrival</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Over all Commission(%)</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <input id="commission" name="commission" class="form-control" value="{{ (@$eexperience->commission) }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <h5>Tax</h5>                            
                            <div class="controls">
                                <div class="controls">
                                    <input id="tax" name="tax" class="form-control" value="{{ (@$eexperience->tax) }}" />
                                </div>
                            </div>
                        </div>  

                        <div class="form-group">
                            <table class="table table-striped">
                                <tr>
                                    <th></th>
                                    <td>Yes/No</td>
                                    <td>Display on Homepage</td>
                                    <th></th>
                                    <td>Yes/No</td>
                                    <td>Display on Homepage</td>
                                </tr>
                                <tr>
                                    <th>Featured Experiences</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="featured_experiences" name="featured_experiences" value="1" {{ (@$eexperience->featured_experiences > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="featured_experiences"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="featured_experiences_on_home" name="featured_experiences_on_home" value="2" {{ (@$eexperience->featured_experiences == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="featured_experiences_on_home"></label>
                                        </div>
                                    </td>
                                    <th>Best Off Season Deals</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="best_off_season_deals" name="best_off_season_deals" value="1" {{ (@$eexperience->best_off_season_deals > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="best_off_season_deals"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="best_off_season_deals_on_home" name="best_off_season_deals_on_home" value="2" {{ (@$eexperience->best_off_season_deals == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="best_off_season_deals_on_home"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deals of the Month</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="deals_of_the_month" name="deals_of_the_month" value="1" {{ (@$eexperience->deals_of_the_month > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="deals_of_the_month"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="deals_of_the_month_on_home" name="deals_of_the_month_on_home" value="2" {{ (@$eexperience->deals_of_the_month == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="deals_of_the_month_on_home"></label>
                                        </div>
                                    </td>
                                    <th>Advance Trainings</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="advance_trainings" name="advance_trainings" value="1" {{ (@$eexperience->advance_trainings > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="advance_trainings"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="advance_trainings_on_home" name="advance_trainings_on_home" value="2" {{ (@$eexperience->advance_trainings == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="advance_trainings_on_home"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Weekend Retreats</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="weekend_retreats" name="weekend_retreats" value="1" {{ (@$eexperience->weekend_retreats > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="weekend_retreats"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="weekend_retreats_on_home" name="weekend_retreats_on_home" value="2" {{ (@$eexperience->weekend_retreats == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="weekend_retreats_on_home"></label>
                                        </div>
                                    </td>
                                    <th>Couple Retreats</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="couple_retreats" name="couple_retreats" value="1" {{ (@$eexperience->couple_retreats > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="couple_retreats"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="couple_retreats_on_home" name="couple_retreats_on_home" value="2" {{ (@$eexperience->couple_retreats == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="couple_retreats_on_home"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Blessed by the Sea</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="blessed_by_the_sea" name="blessed_by_the_sea" value="1" {{ (@$eexperience->blessed_by_the_sea > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="blessed_by_the_sea"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="blessed_by_the_sea_on_home" name="blessed_by_the_sea_on_home" value="2" {{ (@$eexperience->blessed_by_the_sea == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="blessed_by_the_sea_on_home"></label>
                                        </div>
                                    </td>
                                    <th>Retreats by the Mountains</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="retreats_by_the_mountains" name="retreats_by_the_mountains" value="1" {{ (@$eexperience->retreats_by_the_mountains > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="retreats_by_the_mountains"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="retreats_by_the_mountains_on_home" name="retreats_by_the_mountains_on_home" value="2" {{ (@$eexperience->retreats_by_the_mountains == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="retreats_by_the_mountains_on_home"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Luxury Retreats</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="luxury_retreats" name="luxury_retreats" value="1" {{ (@$eexperience->luxury_retreats > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="luxury_retreats"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="luxury_retreats_on_home" name="luxury_retreats_on_home" value="2" {{ (@$eexperience->luxury_retreats == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="luxury_retreats_on_home"></label>
                                        </div>
                                    </td>
                                    <th>Budget Retreats</th>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="budget_retreats" name="budget_retreats" value="1" {{ (@$eexperience->budget_retreats > 0) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="budget_retreats"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input type="checkbox" id="budget_retreats_on_home" name="budget_retreats_on_home" value="2" {{ (@$eexperience->budget_retreats == 2) ? "checked='checked'" : "" }} />
                                            <label class="no-margin" for="budget_retreats_on_home"></label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Status</h5>
                                    <div class="controls">
                                        <select id="is_draft" name="is_draft" class="form-control select2 form-control-select2">
                                            <option value="0" <?php echo (@$eexperience->is_draft == 0) ? "selected" : ""; ?>>Publish</option>
                                            <option value="1" <?php echo (@$eexperience->is_draft == 1) ? "selected" : ""; ?>>Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5>Display on Homepage</h5>
                                    <div class="controls">
                                        <select name="display_on_home" id="display_on_home" class="form-control select2 form-control-select2">
                                            <option value="0" <?php echo (@$eexperience->display_on_home == 0) ? "selected" : ""; ?>>No</option>
                                            <option value="1" <?php echo (@$eexperience->display_on_home == 1) ? "selected" : ""; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/experiences') }}" class="btn btn-primary">Back</a>
                            <a href="{{ url('/experience/'.(@$eexperience->slug).'?preview=1') }}" target="_blank" class="btn btn-info pull-right">Preview</a>
                            <div class="clear clearfix"></div>
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
<script src="{{ asset('admin/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/js/experience-create.js') }}"></script>
@endsection
