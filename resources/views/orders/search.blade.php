@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Search Courier Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/search-courier-order')}}">Search Courier Order
                                </a></li>
                        <li class="breadcrumb-item active">Search Courier Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Search Courier Order</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            
                <div class="card-body">
                   <form id="searchOrder">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="order_id">Enter Order ID <span class="required">*</span></label>
                                <input type="number" name="order_id" class="form-control" id="order_id"
                                    placeholder="Enter Order ID" required="" value="{{old('order_id')}}">
                            </div>
                        </div>

                        
                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-success btn-block">Search</button>
                        </div>
                      
                    </div>
                    </form>
                    <!-- /.card-body -->
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                           <th>Order ID</th>
                           <th>Date</th>
                           <th>Customer Name</th>  
                           <th>Customer Phone</th>
                           <th>Total</th>
                           <th>Status</th> 
                          </tr>
                        </thead>  
                        <tbody id="conts">
                          <tr>
                            <td colspan="6">
                              No Data Found
                            </td>  
                          </tr>  
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
       $(document).on('submit', '#searchOrder', function(e){ 
          e.preventDefault();
          $('#conts').html('');
          let search = $('#order_id').val();
          $.ajax({

                url: "{{url('/find-courier-order')}}",

                     type:"POST",
                     data:{'search':search},
                     dataType:"json",
                     success:function(response) {
                        console.log(response);
                        $('#conts').html(`
                            <tr>
                             <td>${response.data.consignment_id}</td>
                             <td>${response.data.created_at.split('T')[0]}</td>
                             <td>${response.data.orderdetail.customer_name}</td>
                             <td>${response.data.orderdetail.customer_phone}</td>
                             <td>${response.data.orderdetail.total}</td>
                             <td>${response.data.orderdetail.status}</td>
                            </tr>
                        `);
                        //toastr.success(data.message);
                },
                                
         }); 
       });
    });  
 </script>
@endpush