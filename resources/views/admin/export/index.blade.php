@extends('admin.layouts.admin')
@section('title', 'Export')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Export</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item">Reports</li>
<li class="breadcrumb-item active">Export</li>
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
        <div class="col-6">
            <div class="card card-default">
                <div class="card-header">
                    <h4 class="m-b-0">Package Report</h4>
                </div>
                <div class="card-body">
                    <form id="frmCenter" action="{{ url("bbadmin/export/experience-packages") }}" method="get" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Status</label>
                            <select id="is_draft" name="is_draft" class="form-control">
                                <option value="">All</option>
                                <option value="0">Publish</option>
                                <option value="1">Draft</option>
                            </select>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Export</button>
                            <button type="reset" class="btn btn-inverse">Cancel</button>
                        </div>
                    </form>
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
