@extends('admin.layouts.admin')
@section('title', 'Settings')

@section('content')
@section('page-heading')
<h3 class="text-themecolor">Settings</h3>
@endsection
@section('page-breadcrumb')
<li class="breadcrumb-item active">Settings</li>
@endsection
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form id="frmSettings" action="{{ route('admin.settings.save') }}" method="post" novalidate>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <h5>Booking Fee Auto-Discount %</h5>
                            <div class="controls">
                                <input type="number" step="0.01" min="0" max="100" id="deposit_auto_discount_pct" name="deposit_auto_discount_pct"
                                       class="form-control col-md-2" value="{{ old('deposit_auto_discount_pct', $depositAutoDiscount) }}" required />
                            </div>
                            <div class="form-control-feedback"><small>When a center picks "Percentage Deposit" for a retreat, the suggested booking fee is auto-filled as (commission % &minus; this discount %).</small></div>
                        </div>
                        <div class="text-xs-right">
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
