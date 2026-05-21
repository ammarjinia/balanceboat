@extends('admin.layouts.admin')
@section('title', 'Lead Details')
@section('head')
<link href="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" />
@endsection
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Lead Details</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/leads') }}">Leads</a></li>
<li class="breadcrumb-item active">Details</li>
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
                    <h3 class="card-title">Lead Info</h3>
                    <hr />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5>Name</h5>
                                <div>{!! ($elead->name)?:'-' !!}</div>
                            </div>    
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5>Email</h5>
                                <div>{!! $elead->email !!}</div>
                            </div>    
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5>Phone</h5>
                                <div>{!! ($elead->country_code ?: '').' '.($elead->phone?:'') !!}</div>
                            </div>    
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5>Centre Name</h5>
                                <div>{!! @$elead?->experience?->center?->name !!}</div>
                            </div>    
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <h5>Experience Name</h5>
                                <div>{!! @$elead?->experience?->name !!}</div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <h5>Ref. Url</h5>
                                <div>{!! ($elead->ref_url) ? '<a href='.$elead->ref_url.' target="_blank">'.$elead->ref_url.'</a>' : '-' !!}</div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h5>Message</h5>
                                <div>{!! $elead->message !!}</div>
                            </div>    
                        </div>
                    </div>
                    <form id="frmLead" action="{{ url("bbadmin/lead/store") }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $elead->id }}" />
                        <div class="form-group">
                            <h5>Note</h5>
                            <div class="controls">
                                <textarea id="note" name="note" rows="6" class="form-control"><?php echo @$elead->note; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="whatsapp_verified" name="whatsapp_verified" value="1" {{ old('whatsapp_verified', @$elead->whatsapp_verified) == 1 ? 'checked' : '' }} />
                                <label class="form-check-label" for="whatsapp_verified">Whatsapp Verified</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="instagram_verified" name="instagram_verified" value="1" {{ old('instagram_verified', @$elead->instagram_verified) == 1 ? 'checked' : '' }} />
                                <label class="form-check-label" for="instagram_verified">Instagram Verified</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="email_verified" name="email_verified" value="1" {{ old('email_verified', @$elead->email_verified) == 1 ? 'checked' : '' }} />
                                <label class="form-check-label" for="email_verified">Email Verified</label>
                            </div>
                        </div>
                        <div class="text-xs-right form-group">
                            <button type="submit" name="submit_note" class="btn btn-info" value="submit_note">Submit</button>
                            <a href="{{ url('bbadmin/leads') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('admin/js/lead-details.js?v=1.4') }}"></script>
@endsection
