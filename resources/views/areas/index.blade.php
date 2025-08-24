@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Area</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Area</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Area</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if($count === 0)
                    <a href="{{route('ariadhakas.create')}}" class="btn btn-primary add-new mb-2">Add New Area</a>
                @endif
                <div class="fetch-data table-responsive">
                    <table id="area-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Area Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="conts">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')

  <script>
  	$(document).ready(function(){
  		let area_id;
  		var sliderTable = $('#area-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{route('ariadhakas.index')}}",
		        },

		        columns: [
		            {data: 'area_name', name: 'area_name'},
		            {data: 'status', name: 'status'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-aria-update', function(){

	         area_id = $(this).data('id');
	         var isAreachecked = $(this).prop('checked');
	         var status_val = isAreachecked ? 'Active' : 'Inactive';
	         $.ajax({

                url: "{{url('/ariadhaka-status-update')}}",

                     type:"POST",
                     data:{'area_id':area_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-aria', function(e){

           e.preventDefault();

           area_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/ariadhakas')}}/"+area_id,

                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {

                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);

                    },

              });
           }

       });

  	});
  </script>

@endpush
