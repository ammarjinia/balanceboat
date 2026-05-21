@extends('admin.layouts.admin')
@section('title', 'Experiences Report')

@section('head')
<link href="{{ asset('admin/plugins/dropzone-master/dist/min/dropzone.min.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
@section('page-heading')
<h3 class="text-themecolor">Experiences Report</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('bbadmin/listing') }}">Reports</a></li>
<li class="breadcrumb-item active">Experiences</li>
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
                    <h4 class="m-b-0">Export Data</h4>
                </div>
                <div class="card-body">
                    <form id="frmCenter" action="{{ url('bbadmin/report/generate-experience-report') }}" method="post" enctype="multipart/form-data" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">Select Period</label>
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control" required name="start" placeholder="From" />
                                <span class="input-group-addon bg-info b-0 text-white">to</span>
                                <input type="text" class="form-control" name="end" placeholder="To" />
                            </div>
                        </div>
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
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
@endsection
@section('footer')
<script src="{{ asset('admin/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery('#date-range').datepicker({
        toggleActive: true,
        orientation: "bottom"
    });
});
!function (window, document, $) {
    "use strict";
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
}(window, document, jQuery);
</script>
@endsection
