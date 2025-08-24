@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Area</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/ariadhakas')}}">All Area
                                </a></li>
                        <li class="breadcrumb-item active">Add Area</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Area</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('ariadhakas.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        {{--<div class="col-md-12">
                            <div class="form-group">
                                <label for="area_name">Area Name <span class="required">*</span></label>
                                <input type="text" name="area_name" class="form-control" id="area_name"
                                    placeholder="Area Name" required="" value="{{old('area_name')}}">
                                @error('area_name')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>--}}

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="division">Select Division <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="division" id="division" required="">
                                    <option value="" selected="" disabled="">Select Division</option>
                                    @if(count($divisions) > 0)
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division['name'] }}" data-id="{{$division['id']}}">{{ $division['bn_name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('division')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="district">Select District <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="area_name" id="district" required="">
                                    <option value="" selected="" disabled="">Select District</option>

                                </select>
                                @error('area_name')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        {{--<div class="col-md-12">
                            <div class="form-group">
                                <label for="area_type">Select Area Type <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="area_type" id="area_type" required="">
                                    <option value="" selected="" disabled="">Select Area Type</option>
                                    <option value="Inside">Inside</option>
                                    <option value="Outside">Outside</option>
                                </select>
                                @error('area_type')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>--}}

                        <div class="col-md-12">
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

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="inside_delivery_charges">Inside Delivery Charges <span class="required">*</span></label>
                                <input type="text" name="inside_delivery_charges" class="form-control" id="inside_delivery_charges"
                                    placeholder="inside_delivery_charges" required="" value="{{ old('inside_delivery_charges') }}">
                                @error('inside_delivery_charges')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="outside_delivery_charges">Outside Delivery Charges <span class="required">*</span></label>
                                <input type="text" name="outside_delivery_charges" class="form-control" id="outside_delivery_charges"
                                    placeholder="Delivery Charges" required="" value="{{ old('outside_delivery_charges') }}">
                                @error('outside_delivery_charges')
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

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#division').on('change', function () {
                let divisionId = $(this).find(':selected').data('id');

                if (divisionId) {
                    $.ajax({
                        url: '/get-districts/' + divisionId,
                        type: 'GET',
                        success: function (res) {
                            $('#district').empty().append('<option value="" disabled selected>Select District</option>');

                            if (res.status && res.districts.length > 0) {
                                $.each(res.districts, function (key, district) {
                                    $('#district').append('<option value="' + district.name + '">' + district.bn_name + '</option>');
                                });
                            } else {
                                $('#district').append('<option value="" disabled>No District Found</option>');
                            }
                        },
                        error: function () {
                            alert('Failed to fetch district data.');
                        }
                    });
                }
            });
        });
    </script>
@endpush
