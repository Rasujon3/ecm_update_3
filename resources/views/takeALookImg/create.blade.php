@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Take A Look Img</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::to('/take-a-look-images') }}">All Image</a></li>
                        <li class="breadcrumb-item active">Add Image</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        @if(!empty($url))
            <button type="button" class="btn btn-success mb-3" id="watchTutorialBtn">
                Watch Tutorial
            </button>
        @endif

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Take A Look Img</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('take-a-look-images.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="img">Image <span class="required">*</span></label>
                                <input
                                    name="img"
                                    type="file"
                                    id="image"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    required="" />
                                @error('img')
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
        @include('components.youtubeVideoSection')
    </section>
</div>
@endsection

@push('scripts')
    @if(!empty($url))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("watchTutorialBtn");
            const videoSection = document.getElementById("tutorialVideoSection");
            const iframe = document.getElementById("tutorialIframe");

            btn?.addEventListener("click", function () {
                // Set YouTube embed URL
                iframe.src = "{{ $url }}";

                // Show section
                videoSection.style.display = "block";

                // Smooth scroll to video
                videoSection.scrollIntoView({ behavior: "smooth" });
            });
        });
    </script>
    @endif
@endpush
