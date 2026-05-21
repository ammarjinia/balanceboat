@extends('admin.layouts.admin')
@section('title', 'Category')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Category</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Category</li>
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
                        <a href="{{ url('bbadmin/category/create')}}" class="btn btn-info btn-rounded">Add New Category</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblCategory" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Parent</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$categories) {
                                    foreach ($categories as $parent=>$category) {
										if (@$category) {
										?>
                                        <tr>
                                            <td><a href="#"><?php echo @$category['name']; ?></a></td>
                                            <td><?php echo @$category['slug']; ?></td>
											<td>-</td>
                                            <td><?php echo @$category['description']; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/category/edit/'.@$category['id']); ?>">Edit</a>
                                                        <a class="dropdown-item text-info" href="<?php echo url('category/' . @$category['slug'].'?preview=1'); ?>" target="_blank">Preview</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger cat-delete" href="#" data-rel="<?php echo @$category['id']; ?>" >Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
										<?php
										}
										if (@$categories[$parent]['subcategory']) {
											foreach ($categories[$parent]['subcategory'] as $category) {
												if (@$category) {
												?>
												<tr>
													<td><a href="#"><?php echo @$category['name']; ?></a></td>
													<td><?php echo @$category['slug']; ?></td>
													<td><?php echo @$categories[$parent]['name']; ?></td>
													<td><?php echo @$category['description']; ?></td>
													<td>
														<div class="btn-group">
															<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Action
															</button>
															<div class="dropdown-menu" x-placement="bottom-start">
																<a class="dropdown-item" href="<?php echo url('bbadmin/category/edit/'.@$category['id']); ?>">Edit</a>
                                                                <a class="dropdown-item text-info" href="<?php echo url('category/'.@$categories[$parent]['slug'].'/'. $category['slug'].'?preview=1'); ?>" target="_blank">Preview</a>
                                                                <div class="dropdown-divider"></div>
																<a class="dropdown-item text-danger cat-delete" href="#" data-rel="<?php echo $category['id']; ?>" >Delete</a>
															</div>
														</div>
													</td>
												</tr>
												<?php
												}
											}
										}
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
<form id="frmCategory" name="frmCategory" action="{{ url('bbadmin/category/destroy') }}" method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/category.js') }}"></script>
@endsection
