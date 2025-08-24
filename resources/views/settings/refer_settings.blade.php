@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Refer Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/app-settings')}}">Refer Settings
                                </a></li>
                        <li class="breadcrumb-item active">Refer Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Refer Settings</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-refer')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="per_refer_point">Per Refer Point <span class="required">*</span></label>
                                <input type="number" name="per_refer_point" class="form-control" id="per_refer_point"
                                    placeholder="Per Refer Point" required="" value="{{old('per_refer_point',$refer?$refer->per_refer_point:"")}}">
                                @error('per_refer_point')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="total_required_point">Total Required Point <span class="required">*</span></label>
                                <input type="number" name="total_required_point" class="form-control" id="total_required_point"
                                    placeholder="Total Required Point" required="" value="{{old('total_required_point',$refer?$refer->total_required_point:"")}}">
                                @error('total_required_point')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="website_quantity">Website Quantity <span class="required">*</span></label>
                                <input type="number" name="website_quantity" class="form-control" id="website_quantity"
                                    placeholder="Website Quantity" required="" value="{{old('website_quantity',$refer?$refer->website_quantity:"")}}">
                                @error('website_quantity')
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