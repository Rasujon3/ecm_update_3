@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Why Choose Us Title</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/why-choose-us-title')}}">Why Choose Us Title
                                </a></li>
                        <li class="breadcrumb-item active">Why Choose Us Title</li>
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
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Why Choose Us Title</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('why-choose-us-title.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Why Choose Us Title <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    id="title"
                                    placeholder="Why Choose Us Title"
                                    value="{{old('title', ($data && $data->title) ? $data->title : "")}}"
                                >
                                @error('title')
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
            @include('components.youtubeVideoSection')
    </section>
</div>
@endsection

@push('scripts')
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
@endpush
