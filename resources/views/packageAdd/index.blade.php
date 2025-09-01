@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase new loading page</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Purchase new loading page</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('package-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="slug">Sub Domain <span class="required">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="slug"
                                        placeholder=""
                                        disabled=""
                                        value="{{$domain}}"
                                    >
                                </div>
                                <div class="col-md-6">
                                    <input
                                        type="text"
                                        name="slug"
                                        class="form-control"
                                        id="slug"
                                        placeholder="Ex: v2"
                                        required=""
                                        value="{{old('slug')}}"
                                    >
                                    @error('slug')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($plans) > 0)
                        <div class="row pt-5">
                            @foreach($plans as $plan)
                                @include('packageAdd.components.pricing-card2', ['plan' => $plan])
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let form = document.querySelector("form[action='{{ route('package-store') }}']");
            form.addEventListener("submit", function(e) {
                let slug = document.getElementById("slug").value.trim();
                if (!slug) {
                    e.preventDefault();
                    alert("Please enter a Sub Domain before selecting a plan!");
                }
            });
        });
    </script>
@endpush
