@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Package</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/packages')}}">All Package
                                </a></li>
                        <li class="breadcrumb-item active">Add Package</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Package</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('packages.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="package_name">Package Name <span class="required">*</span></label>
                                <input type="text" name="package_name" class="form-control" id="package_name"
                                    placeholder="Package Name" required="" value="{{old('package_name')}}">
                                @error('package_name')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Package Price <span class="required">*</span></label>
                                <input type="text" name="price" class="form-control numericInput" id="price"
                                    placeholder="Package Price" required="" value="{{old('price')}}">
                                @error('price')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_product">Maximum Product Allowed <span class="required">*</span></label>
                                <input type="number" name="max_product" class="form-control" id="max_product"
                                    placeholder="Maximum Product Allowed" required="" value="{{old('max_product')}}">
                                @error('max_product')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Select Status <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="status" id="status" required="">
                                    <option value="" selected="" disabled="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                @error('status')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_title">Sub Title <span class="required">*</span></label>
                                <input type="text" name="sub_title" class="form-control" id="sub_title"
                                       placeholder="Sub Title" required="" value="{{old('sub_title')}}">
                                @error('sub_title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="demo_url">Demo URL <span class="required">*</span></label>
                                <input type="text" name="demo_url" class="form-control" id="demo_url"
                                       placeholder="Demo URL" required="" value="{{old('demo_url')}}">
                                @error('demo_url')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="short_description">Short Description <span class="required">*</span></label>
                                <textarea class="form-control" name="short_description" id="short_description" required="" placeholder="Short Description">{!!old('short_description')!!}</textarea>
                                @error('short_description')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="img">Background Image <span class="required">*</span></label>
                                <input
                                    name="img"
                                    type="file"
                                    id="img"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                />
                                @error('img')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="card">
                              <div class="card-header bg-success">
                              	<h5 class="card-title">Select Services</h5>
                              </div>
                              <div class="card-body">
                              	<div class="row">
                              	 @foreach(services() as $service)
                              	  <div class="col-md-4">
                              	  	<input type="checkbox" name="services[]" id="{{$service->id}}" value="{{$service->id}}"/>
                              	    <label for="{{$service->id}}">{{$service->title}}</label>
                              	  </div>
                              	@endforeach
                              	</div>
                              </div>
                            </div>
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
