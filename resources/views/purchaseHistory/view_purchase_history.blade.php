@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase History</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Purchase History</li>
                    </ol>
                </div><!-- /.col -->
                <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <p><strong>Domain Name:</strong> {{ $payment?->domain?->domain ?? '' }}</p>
                <p><strong>User Name:</strong> {{ $payment?->user?->name }}</p>
                <p><strong>Phone:</strong> {{ $payment?->user?->phone ?? '' }}</p>
                <p><strong>Package Name:</strong> {{ $payment?->package?->package_name ?? '' }}</p>
                <p><strong>Theme Name:</strong> {{ $payment?->theme ?? '' }}</p>
                <p><strong>Payment Method:</strong> {{ $payment?->payment_method ?? '' }}</p>
                <p><strong>Transaction Hash:</strong> {{ $payment?->transaction_hash ?? '' }}</p>
                <p><strong>Status:</strong> {{ $payment?->status ?? '' }}</p>
            </div>
        </div>
    </section>
</div>
@endsection
