@extends('admin.layouts.admin')
@section('title', 'Create User')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Create User</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/users') }}">Users</a></li>
<li class="breadcrumb-item active">Create User</li>
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
                    <form id="frmUser" action="{{ url("bbadmin/users/store") }}" method="post" enctype="multipart/form-data" novalidate >
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>First Name <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="text" id="first_name" name="first_name" class="form-control" required data-validation-required-message="This field is required" value="{!! old('first_name') !!}" />
                            </div>                           
                        </div>
                        <div class="form-group">
                            <h5>Last Name</h5>
                            <div class="controls">
                                <input type="text" id="last_name" name="last_name" class="form-control" value="{!! old('last_name') !!}" />
                            </div>                           
                        </div>
                        <div class="form-group">
                            <h5>Email <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <input type="email" id="email" name="email" class="form-control required" required data-validation-required-message="This field is required" value="{!! old('email') !!}" />
                            </div>                            
                        </div>
                        <div class="form-group">
                            <h5>Roles <span class="text-danger">*</span></h5>
                            <div class="controls">
                                @foreach ($roles as $role)
                                <input type="checkbox" name="roles[]" id="role{{ $role->id }}" value="{{ $role->id }}" />
                                <label for="role{{ $role->id }}">{!! ucfirst($role->name) !!}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" />
                        </div>
                        <div class="form-group">
                            <h5>Center <span class="text-danger">*</span></h5>
                            <div class="controls">
                                <select name="center_id" id="center_id" class="form-control" required data-validation-required-message="This field is required">
                                </select>
                            </div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                            <a href="{{ url('bbadmin/users') }}" class="btn btn-primary">Back</a>
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
<script src="{{ asset('admin/js/user-create.js') }}"></script>
@endsection