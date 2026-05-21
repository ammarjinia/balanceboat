@extends('admin.layouts.admin')
@section('title', 'Users')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Users</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Users</li>
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
                        <a href="{{ url('bbadmin/users/create')}}" class="btn btn-info btn-rounded">Add New User</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblUsers" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Date/Time Added</th>
                                    <th>User Roles</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$users) {
                                    foreach (@$users as $user) {
                                        ?>
                                        <tr>
                                            <td><a href="{{ route('users.edit', $user->id) }}"><?php echo $user->first_name ? $user->first_name . " " . $user->last_name : $user->name; ?></a></td>
                                            <td><?php echo $user->email; ?></td>
                                            <td><?php echo ($user->created_at) ? $user->created_at->format('F d, Y h:ia') : ""; ?></td>
                                            <td>{{  $user->roles()->pluck('name')->implode(' ') }}</td>{{-- Retrieve array of roles associated to a user and convert to string --}}
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                                        @if($user->center)
                                                        <a class="dropdown-item" href="{{ url('bbadmin/users/invitation/'.$user->id) }}">Send Email</a>
                                                        @endif
                                                        <div class="dropdown-divider"></div>
                                                        <form id ="frmUsers" route="{!! route('users.destroy', $user->id) !!}" method="DELETE">
                                                            <input type="submit" class="dropdown-item text-danger user-delete" value="Delete" />
                                                        </form>
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
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/users.js') }}"></script>
@endsection