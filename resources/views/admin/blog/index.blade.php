@extends('admin.layouts.admin')
@section('title', 'Blog')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Blog</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Blog</li>
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
                        <a href="{{ url('bbadmin/blog/create')}}" class="btn btn-info btn-rounded">Add New Blog</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblBlog" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$blogs) {
                                    foreach (@$blogs as $blog) {
                                        ?>
                                        <tr>
                                            <td><?php echo $blog->id; ?></td>
                                            <td><a href="<?php echo url('bbadmin/blog/edit/' . $blog->id); ?>"><?php echo $blog->name; ?></a></td>
                                            <td><?php echo $blog->slug; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/blog/edit/' . $blog->id); ?>">Edit</a>
                                                        <a class="dropdown-item text-info" href="<?php echo url('blog/' . $blog->slug.'?preview=1'); ?>" target="_blank">Preview</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger blog-delete" href="#" data-rel="<?php echo $blog->id; ?>" >Delete</a>
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
<form id="frmBlog" name="frmBlog" action="{{ url('bbadmin/blog/destroy') }}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/blog.js?v=1') }}"></script>
@endsection
