@extends('admin.layouts.admin')
@section('title', 'Centers')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Centers</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Centers</li>
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
                    @include ('errors.list')
                    <div class="text-right">
                        <a href="{{ url('bbadmin/centre-onboard/config-email-template')}}" class="btn btn-info btn-rounded">Config Email Template</a>
                    </div>
                    <form id="frmCentreOnboardImport" action="{{ url("bbadmin/centre-onboard/import") }}" method="post" enctype="multipart/form-data" novalidate >
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Import Records</h5>
                            <div class="controls">
                                <input type="file" name="file" id="file" class="form-control required" required="" accept=".csv" />
                            </div>
                            <div class="form-control-feedback"><small>Support file type csv only</small></div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-right">
                        <a href="{{ url('bbadmin/centre-onboard/create')}}" class="btn btn-info btn-rounded">Add New Center</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblCentreOnboard" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$centers) {
                                    foreach (@$centers as $center) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo url('bbadmin/centre-onboard/edit/' . $center->id); ?>"><?php echo $center->name; ?></a></td>
                                            <td><?php echo $center->email; ?></td>
                                            <td><?php echo $center->location; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/centre-onboard/edit/' . $center->id); ?>">Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger center-delete" href="#" data-rel="<?php echo $center->id; ?>" >Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<form id="frmCentreOnboard" name="frmCentreOnboard" action="{{ url('bbadmin/centre-onboard/destroy') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/centre-onboard.js') }}"></script>
@endsection
