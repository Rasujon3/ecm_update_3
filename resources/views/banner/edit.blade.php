@extends('admin_master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Banner</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ URL::to('/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL::to('/banner') }}">All Banner
                                </a></li>
                            <li class="breadcrumb-item active">Edit Banner</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Banner</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{route('banner.update',$item->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title<span class="required">*</span></label>
                                    <input type="text" name="title" class="form-control" id="title"
                                           placeholder="Title" required="" value="{{ old('title', $item->title) }}"></input>
                                    @error('title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description<span class="required">*</span></label>
                                    <textarea type="text" name="description" class="form-control" id="description"
                                           placeholder="Description" required="">{{ old('description', $item->description) }}</textarea>
                                    @error('description')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="img">Image<span class="required">*</span></label>
                                            <input type="file" name="img" class="form-control" id="img"
                                                   placeholder="Image" value="{{ old('img', $item->img_url) }}"></input>
                                            @error('img')
                                                <span class="alert alert-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4 px-5">
                                        <div class="form-group">
                                            <label for="img">Image Preview</label>
                                            <img src="{{ asset('files/music/mp3/' . $item->img_url) }}" alt="Banner Image" style="height:60px;">
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
