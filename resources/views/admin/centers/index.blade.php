@extends('admin.layouts.admin')
@section('title', 'Centres')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Centres</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Centres</li>
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
                    <div class="text-right">
                        <a href="{{ url('bbadmin/centers/upload')}}" class="btn btn-primary btn-rounded">Upload Centres</a>
                        <a href="{{ url('bbadmin/centers/create')}}" class="btn btn-info btn-rounded">Add New Centre</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblCenters" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Location</th>
                                    <th>Last Updated At</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$centers) {
                                    foreach (@$centers as $center) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo url('bbadmin/centers/edit/' . $center->id); ?>"><?php echo $center->name; ?></a></td>
                                            <td><?php echo $center->slug; ?></td>
                                            <td><?php echo $center->LocationName; ?></td>
                                            <td><?php echo $center->updated_at?->format('d M, Y h:i A'); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/centers/edit/' . $center->id); ?>">Edit</a>
                                                        <a href="{{ url('/center/'.(@$center->slug).'?preview=1') }}" target="_blank" class="dropdown-item text-info">Preview</a>
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
<form id="frmCenters" name="frmCenters" action="{{ url('bbadmin/centers/destroy') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/centers.js') }}"></script>
@endsection
