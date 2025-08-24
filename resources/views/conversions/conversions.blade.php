@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Conversion</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/conversions')}}">Conversion
                                </a></li>
                        <li class="breadcrumb-item active">Conversion</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Conversion</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('conversions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facebook">Facebook <span class="required">*</span></label>
                                <input type="text" name="facebook" class="form-control" id="facebook"
                                    placeholder="Facebook"  value="{{old('facebook', ($conversions && $conversions->facebook) ? $conversions->facebook : "")}}">
                                @error('facebook')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp">Whatsapp <span class="required">*</span></label>
                                <input type="text" name="whatsapp" class="form-control" id="whatsapp"
                                       placeholder="Whatsapp"  value="{{old('whatsapp', ($conversions && $conversions->whatsapp) ? $conversions->whatsapp : "")}}">
                                @error('whatsapp')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone <span class="required">*</span></label>
                                <input type="text" name="phone" class="form-control" id="phone"
                                       placeholder="Phone"  value="{{old('phone', ($conversions && $conversions->phone) ? $conversions->phone : "")}}">
                                @error('phone')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </div>
                    <!-- /.card-body -->
            </form>
        </div>
    </section>
</div>
@endsection
