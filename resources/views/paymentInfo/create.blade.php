@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Payment Info</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::to('/payment-info') }}">All Payment Info
                                </a></li>
                        <li class="breadcrumb-item active">Add Payment Info</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Payment Info</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('payment-info.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="payment_method">Select Payment Method <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="payment_method" id="payment_method" required="">
                                    <option value="" selected="" disabled="">Select Payment Method</option>
                                    <option value="bKash">BKash</option>
                                    <option value="rocket">Rocket</option>
                                    <option value="nogod">Nogod</option>
                                </select>
                                @error('payment_method')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="account_number">Account Number<span class="required">*</span></label>
                                <input type="text" name="account_number" class="form-control" id="account_number"
                                    placeholder="Account Number" required="" value="{{old('account_number')}}">
                                @error('account_number')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="payment_type">Select Payment Type <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="payment_type" id="payment_type" required="">
                                    <option value="" selected="" disabled="">Select Payment Type</option>
                                    <option value="Personal">Personal</option>
                                    <option value="Agent">Agent</option>
                                    <option value="Merchant">Merchant</option>
                                </select>
                                @error('payment_type')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="instructions">Instructions <span class="required">*</span></label>
                                <textarea name="instructions" class="form-control description" id="instructions"
                                          placeholder="Instructions" required>{{ old('instructions') }}</textarea>
                                @error('instructions')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
