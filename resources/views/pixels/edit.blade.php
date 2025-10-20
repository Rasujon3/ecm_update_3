@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Pixel</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/pixels')}}">All Pixel</a></li>
                        <li class="breadcrumb-item active">Edit Pixel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Edit Pixel</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('pixels.update',$pixel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pixel_id">Pixel ID <span class="required">*</span></label>
                                <input type="text" name="pixel_id" class="form-control numericInput" id="pixel_id"
                                       placeholder="Pixel ID" required="" value="{{old('pixel_id', $pixel->pixel_id)}}">
                                @error('pixel_id')
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


<div class="modal" id="addUnitModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Unit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="unitModalForm">
          <div class="form-group">
            <label for="unit_title">Title <span class="required">*</span></label>
            <input type="text" class="form-control" id="unit_title" placeholder="Title" required="">
          </div>
          <input type="hidden" id="unit_status" value="Active"/>
          <div class="form-group">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')


  <script>
    $(document).ready(function(){
        $(document).on('click', '.add-unit', function(e){
            e.preventDefault();
            $('#addUnitModal').modal('show');
        });

        $(document).on('submit', '#unitModalForm', function(e){
            e.preventDefault();
            $('#unit_id').html('');
            let user_id = "{{user()->id}}";
            let domain_id = "{{getDomain()->id}}";
            let title = $('#unit_title').val();
            let status = $('#unit_status').val();
            $.ajax({

                url: "{{url('/store-unit')}}",

                     type:"POST",
                     data:{'user_id':user_id, 'domain_id':domain_id, 'title':title, 'status':status},
                     dataType:"json",
                     success:function(data) {
                        console.log(data);
                        let html = `<option value="" selected="" disabled="">Select Unit</option>`;
                        $(data.units).each(function(idx,val){
                            let selectedUnit = val.id == data.unit_id?"selected":'';
                            html+=`<option value="${val.id}" ${selectedUnit}>${val.title}</option>`
                        });
                        $('#unit_id').append(html);
                        toastr.success(data.message);

                        $('#addUnitModal').modal('hide');

                },

            });
        });
    });
  </script>

@endpush
