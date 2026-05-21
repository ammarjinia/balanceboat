@extends('admin.layouts.admin')
@section('title', 'Experiences')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Experiences</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Experiences</li>
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
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bookable</label>
                                        <select id="sbookable" name="sbookable" class="form-control select2" data-placeholder="Select">
                                            <option value="">All</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select id="sdestination" name="sdestination" class="form-control select2" data-placeholder="Select">
                                            <option value="">Select</option>
                                            <?php if (@$destinations) {
                                                foreach (@$destinations as $destination) {
                                            ?>
                                                    <option value="<?php echo $destination->id; ?>"><?php echo $destination->name; ?></option>
                                            <?php

                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php /*<div class="col-md-3">
                                    <div class="form-group">
                                        <label>Discount Range</label>
                                        <select id="sdiscount" name="sdiscount" class="form-control select2" data-placeholder="Select">
                                            <option value="">Select</option>
                                            <option value="5-10">5-10%</option>
                                            <option value="10-20">10-20%</option>
                                            <option value="20-30">20-30%</option>
                                            <option value="30-40">30-40%</option>
                                            <option value="40-50">40-50%</option>
                                            <option value="50-60">50-60%</option>
                                        </select>
                                    </div>
                                </div>*/?>
                            </div>
                        </div>
                        <div class="col-md-5 text-right">
                            <a href="{{ url('bbadmin/experiences/upload')}}" class="btn btn-info btn-rounded">Upload Experience</a>
                            <a href="{{ url('bbadmin/experiences/create')}}" class="btn btn-info btn-rounded">Add New Experience</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tblExperiences" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Center</th>
                                    <th>Last Updated At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /*if (@$experiences) {
                                    foreach (@$experiences as $experience) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo url('bbadmin/experiences/edit/' . $experience->id); ?>"><?php echo $experience->name; ?></a></td>
                                            <td><?php echo $experience->slug; ?></td>
                                            <td><?php echo $experience->CenterName; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/experiences/edit/' . $experience->id); ?>">Edit</a>
                                                        <a href="{{ url('bbadmin/experiences/clone/' . $experience->id) }}" class="dropdown-item text-warning">Clone</a>
                                                        <a href="{{ url('/experience/'.(@$experience->slug).'?preview=1') }}" target="_blank" class="dropdown-item text-info">Preview</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger experience-delete" href="#" data-rel="<?php echo $experience->id; ?>" >Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }*/
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<form id="frmExperiences" name="frmExperiences" action="{{ url('bbadmin/experiences/destroy') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="id" value="" />
</form>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/experiences.js') }}"></script>
@endsection