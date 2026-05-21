@extends('admin.layouts.admin')
@section('title', 'Leads')

@section('head')
<link href="{{ asset('admin/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Leads</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Leads</li>
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
                    <form id="frmSearch" action="{{ url('bbadmin/leads') }}" method="get">
                        <input type="hidden" name="ajax" value="true" />
                        <input type="hidden" name="download" value="true" />
                        <input type="hidden" name="search" id="search" value="" />
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date-range">Select Period</label>
                                <div class="input-daterange input-group" id="date-range">
                                    <input type="text" class="form-control" required name="start_date" placeholder="From" autocomplete="off" />
                                    <span class="input-group-addon bg-info b-0 text-white">to</span>
                                    <input type="text" class="form-control" name="end_date" placeholder="To" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6" align="right">
                                <button class="btn btn-rounded btn-success" id="btnExport">Export Data</button>
                                <a href="{{ url('bbadmin/lead/create')}}" class="btn btn-info btn-rounded">Add New</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="tblLead" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th>Verified By</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<form id="frmLead" name="frmLead" action="{{ url('bbadmin/lead/destroy') }}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="id" id="id" value="" />
</form>  
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script defer src="{{ asset('admin/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('admin/js/lead.js?v=1.8') }}"></script>
@endsection
