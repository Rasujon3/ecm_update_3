@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Set Delivery Charge</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/app-settings')}}">Set Delivery Charge
                                </a></li>
                        <li class="breadcrumb-item active">Set Delivery Charge</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Set Delivery Charge</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-app')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="inside_delivery_charge">Inside Dhaka Delivery charge (%) <span class="required">*</span></label>
                                <input type="text" name="inside_delivery_charge" class="form-control numericInput" id="inside_delivery_charge"
                                    placeholder="Inside Dhaka Delivery charge (%)"  value="{{old('inside_delivery_charge',$setting?$setting->inside_delivery_charge:"")}}">
                                @error('inside_delivery_charge')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="outside_delivery_charge">Outside Dhaka Delivery charge (%) <span class="required">*</span></label>
                                <input type="text" name="outside_delivery_charge" class="form-control numericInput" id="outside_delivery_charge"
                                    placeholder="Outside Dhaka Delivery charge (%)"  value="{{old('outside_delivery_charge',$setting?$setting->outside_delivery_charge:"")}}">
                                @error('outside_delivery_charge')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>
@endsection