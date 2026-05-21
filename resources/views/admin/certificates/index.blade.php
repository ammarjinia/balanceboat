@extends('admin.layouts.admin')
@section('title', 'Certificates')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Certificates</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Certificates</li>
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
                        <a href="{{ url('bbadmin/certificates/create')}}" class="btn btn-info btn-rounded">Add New Certificate</a>
                    </div>
                    <div class="table-responsive">
                        <table id="tblCertificate" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$certificates) {
                                    foreach ($certificates as $certificate) {
										?>
                                        <tr>
                                            <td><a href="#"><?php echo $certificate->name; ?></a></td>
                                            <td><?php echo $certificate->slug; ?></td>
											<td><?php echo $certificate->description; ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" x-placement="bottom-start">
                                                        <a class="dropdown-item" href="<?php echo url('bbadmin/certificates/edit/'.$certificate->id); ?>">Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-danger certificate-delete" href="#" data-rel="<?php echo $certificate->id; ?>" >Delete</a>
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
<form id="frmCertificate" name="frmCertificate" action="{{ url('bbadmin/certificates/destroy') }}" method="post">
{{ csrf_field() }}
<input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/js/certificates.js') }}"></script>
@endsection
