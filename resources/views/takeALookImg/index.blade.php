@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Take A Look Img</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Take A Look Img</li>
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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Take A Look Img</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if($count < 5)
                    <a href="{{ route('take-a-look-images.create') }}" class="btn btn-primary add-new mb-2">Add New Take A Look Img</a>
                @endif
                <div class="fetch-data table-responsive">
                    <table id="user-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
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
        @include('components.youtubeVideoSection')
    </section>
</div>
@endsection

@push('scripts')

  <script>
  	$(document).ready(function(){
  		let id;
  		var sliderTable = $('#user-table').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/take-a-look-images') }}",
		        },

		        columns: [
		            {data: 'img', name: 'img'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });



       $(document).on('click', '#status-user-update', function(){

	         id = $(this).data('id');
	         var isUserchecked = $(this).prop('checked');
	         var status_val = isUserchecked ? 'approved' : 'pending';
	         $.ajax({

                url: "{{url('/purchase-status-update')}}",

                     type:"POST",
                     data:{'id':id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {

                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

	        });
       });


       $(document).on('click', '.delete-data', function(e){

           e.preventDefault();

           id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{ url('/delete/take-a-look-images') }}/" + id,

                         type: "POST",
                         dataType: "json",
                         success:function(data) {
                            if (data.status) {
                                toastr.success(data.message);

                                // $('.data-table').DataTable().ajax.reload(null, false);
                                window.location.reload();
                            } else {
                                toastr.error(data.message);
                            }
                        },
                       error: function (xhr) {
                           toastr.error('Something went wrong!!!');
                       }
              });
           }

       });

  	});
  </script>
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
