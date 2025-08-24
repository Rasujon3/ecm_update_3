@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Banner</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Banner</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Banner</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if($count === 0)
                    <a href="{{ route('banner.create') }}" class="btn btn-primary add-new mb-2">Add New Banner</a>
                @endif

                <div class="fetch-data table-responsive">
                    <table id="user-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Image</th>
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
  		let user_id;
  		var sliderTable = $('#user-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/banner') }}",
		        },

		        columns: [
		            {data: 'title', name: 'title'},
		            {data: 'description', name: 'description'},
		            {data: 'img', name: 'img'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-user-update', function(){

	         user_id = $(this).data('id');
	         var isUserchecked = $(this).prop('checked');
	         var status_val = isUserchecked ? 'approved' : 'pending';
	         $.ajax({

                url: "{{url('/purchase-status-update')}}",

                     type:"POST",
                     data:{'id':user_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-service', function(e){

           e.preventDefault();

           user_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{ url('/delete/banner') }}/" + user_id,

                         type: "POST",
                         dataType: "json",
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
