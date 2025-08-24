@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Set Meta Pixel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/app-settings')}}">Set Meta Pixel
                                </a></li>
                        <li class="breadcrumb-item active">Set Meta Pixel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Set Meta Pixel</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-app')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="facebook_pixel_id">Facebook Pixel ID <span class="required">*</span></label>
                                <input type="text" name="facebook_pixel_id" class="form-control" id="facebook_pixel_id"
                                    placeholder="Facebook Pixel ID"  value="{{old('facebook_pixel_id',$setting?$setting->facebook_pixel_id:"")}}">
                                @error('facebook_pixel_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div> 
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="order_note">Order Note</label>
                                <textarea class="form-control" id="order_note" rows="3" col="30" name="order_note" placeholder="Order Note">{!!old('order_note',$setting?$setting->order_note:"")!!}</textarea>
                                @error('order_note')
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