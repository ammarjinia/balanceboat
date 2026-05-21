@extends('admin.layouts.admin')
@section('title', 'Teachers')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Teachers</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Teachers</li>
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
                        <a href="{{ url('bbadmin/teachers/create')}}" class="btn btn-info btn-rounded">Add New Teacher</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblTeachers" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Short Intro</th>
                                    <th>Centers</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$teachers) {
                                    foreach (@$teachers as $teacher) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo url('bbadmin/teachers/edit/' . $teacher->id); ?>"><?php echo $teacher->name; ?></a></td>
                                            <td><?php echo $teacher->slug; ?></td>
                                            <td><?php echo $teacher->short_description; ?></td>
                                            <td><?php echo $teacher->CenterName; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/teachers/edit/' . $teacher->id); ?>">Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger teacher-delete" href="#" data-rel="<?php echo $teacher->id; ?>" >Delete</a>
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
<form id="frmTeachers" name="frmTeachers" action="{{ url('bbadmin/teachers/destroy') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/teachers.js') }}"></script>
@endsection
