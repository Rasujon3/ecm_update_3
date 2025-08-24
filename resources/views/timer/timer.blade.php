@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Timer</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/timer')}}">Timer
                                </a></li>
                        <li class="breadcrumb-item active">Timer</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Timer</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('timer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control" id="title"
                                       placeholder="Title"  value="{{old('title', $data ? $data->title : "")}}">
                                @error('title')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time">Time <span class="required">*</span></label>
                                <input
                                    type="datetime-local"
                                    name="time"
                                    class="form-control"
                                    id="time"
                                    placeholder="Minutes"
                                    min="{{ now()->format('Y-m-d\TH:i') }}"
                                    value="{{ old('time', ($data && $data->time) ? \Carbon\Carbon::createFromTimestamp($data->time, 'Asia/Dhaka')->format('Y-m-d\TH:i') : "") }}"
                                >
                                @error('time')
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
