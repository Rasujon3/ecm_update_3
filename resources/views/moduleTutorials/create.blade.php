@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Module Tutorials</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::to('/module-tutorials') }}">All Details</a></li>
                        <li class="breadcrumb-item active">Add Details</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Module Tutorials</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('module-tutorials.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="module_id">Select Module <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="module_id" id="module_id" required="">
                                    <option value="" selected="" disabled="">Select Module</option>
                                    @if(count($modules) > 0)
                                        @foreach ($modules as $module)
                                            <option
                                                value="{{ $module['id'] }}"
                                                @if(old('module_id') === $module['id']) selected @endif
                                            >
                                                {{ $module['title'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('module_id')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="video_url">Video URL <span class="required">*</span></label>
                                <input
                                    name="video_url"
                                    class="form-control"
                                    id="video_url"
                                    placeholder="Video URL"
                                    required
                                    value="{{ old('video_url') }}"
                                >
                                </input>
                                @error('video_url')
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
