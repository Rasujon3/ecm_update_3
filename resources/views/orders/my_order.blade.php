@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Order</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="card w-100">
                  <div class="card-header">
                    <h5>Filter Order</h5>
                  </div>

                  <div class="card-body">
                     <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="from_date">From Date</label>
                          <input type="date" class="form-control" id="from_date" required=""/>
                        </div>

                      </div>


                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="to_date">To Date</label>
                          <input type="date" class="form-control" id="to_date" required=""/>
                        </div>

                      </div>


                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Select Status</label>
                          <select class="form-control select2bs4" id="selected_status">
                           <option value="" selected="" disabled="">Select Status</option>
                           <option value="Pending">Pending</option>
                           <option value="Accept">Accept</option>
                           <option value="Cancel">Cancel</option>
                          </select>
                        </div>

                      </div>

                      <div class="col-md-12">

                        <button type="button" class="btn btn-primary btn-block filter-order"><i class="fa fa-search"></i> SEARCH</button>



                     </div>

                     </div>
                  </div>
                </div>
                <div class="fetch-data table-responsive">
                    <table id="my-order-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Order <br> ID</th>
                                <th>Date</th>
                                <th>Customer <br> Name</th>
                                <th>Payment <br> Status</th>
                                <th>Product <br> Details</th>
                                <th>Customer Phone</th>
                                <th>Total (BDT)</th>
                                <th>Order Status</th>
                                <th>Courier Status</th>
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

<div class="modal" id="discountModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Discoubt</h5>
        <button type="button" class="close close-modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="discountForm">

          <div class="form-group">
            <label for="total">Total (BDT)</label>
            <input type="text" class="form-control numericInput" id="total" placeholder="Total" readonly="" required=""/>
          </div>

          <div class="form-group">
            <label for="discount">Discount (%)</label>
            <input type="text" class="form-control numericInput" id="discount" placeholder="Discount" required=""/>
          </div>


          <div class="form-group">
            <label for="discount_price">Discount Price</label>
            <input type="text" class="form-control numericInput" readonly="" id="discount_price" placeholder="Discount Price" required=""/>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-success">Add Discount</button>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

  <script>
    $(document).ready(function(){
      let order_id;
      var orderTable = $('#my-order-table').DataTable({
            searching: true,
            processing: true,
            serverSide: true,
            ordering: false,
            responsive: true,
            stateSave: true,
            ajax: {
              url: "{{url('/orders')}}",
              data: function (d) {

                  d.from_date = $('#from_date').val(),
                  d.to_date = $('#to_date').val(),
                  d.status = $('#selected_status').val()
                  //d.category_id = $('#selected_category_id').val(),
                  d.search = $('.dataTables_filter input').val()
              }
            },

            columns: [
                {data: 'order_id', name: 'order_id'},
                {data: 'order_date', name: 'order_date'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'product_details', name: 'product_details', orderable: false, searchable: false},
                {data: 'customer_phone', name: 'customer_phone'},
                {data: 'total', name: 'total'},
                {data: 'status', name: 'status'},
                {data: 'courier_status', name: 'courier_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

       $('.filter-order').click(function(e){
          e.preventDefault();
          orderTable.draw();
      });


       $(document).on('change', '.change-status', function(){

           order_id = $(this).data('id');
           var status_val = $(this).val();

           if(confirm('Do you want to change the status?'))
           {
              $.ajax({

                url: "{{url('/order-status-update')}}",

                     type:"POST",
                     data:{'order_id':order_id, 'status':status_val},
                     dataType:"json",
                     success:function(data) {
                      console.log(data);
                        toastr.success(data.message);

                        $('.data-table').DataTable().ajax.reload(null, false);

                },

             });
           }
       });


       $(document).on('click', '.delete-order', function(e){

           e.preventDefault();

           order_id = $(this).data('id');

           if(confirm('Do you want to delete this?'))
           {
               $.ajax({

                    url: "{{url('/delete-order')}}/"+order_id,

                         type:"DELETE",
                         dataType:"json",
                         success:function(data) {

                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);

                    },

              });
           }

       });


       $(document).on('click', '.see-order-status', function(e){
           e.preventDefault();
           order_id = $(this).data('id');
           $('.order_status_btn_'+order_id).text('Please Wait...');
           $.ajax({

            url: "{{url('/see-order-status')}}",

                 type:"POST",
                 data:{'order_id':order_id},
                 dataType:"json",
                 success:function(data) {
                   console.log(data);
                   $('.order_status_btn_'+order_id).html(data);

            },

          });

       });


       $(document).on('click', '.customer-discount', function(e){
          e.preventDefault();
          order_id = $(this).data('id');
          $.ajax({

            url: "{{url('/order-details')}}",

                 type:"POST",
                 data:{'order_id':order_id},
                 dataType:"json",
                 success:function(data) {
                   $('#total').val(data.total);

            },

          });
          $('#discountModal').show();
       });

       $(document).on('click', '.close-modal', function(e){
         e.preventDefault();
         $('#total').val('');
         $('#discount').val('');
         $('#discount_price').val('');
         $('#discountModal').hide();
      });

      $(document).on('submit','#discountForm',function(e){
         e.preventDefault();
         let discount = $('#discount').val();
         let discount_price = $('#discount_price').val();
         if(discount != "")
         {
            $.ajax({

                url: "{{url('/order-custom-discount')}}",

                     type:"POST",
                     data:{'order_id':order_id, 'discount':discount, 'discount_price':discount_price},
                     dataType:"json",
                     success:function(data) {

                        if(data.status == true)
                        {
                            //console.log(data)
                           toastr.success(data.message);

                          $('.data-table').DataTable().ajax.reload(null, false);

                        }else{
                          toastr.error(data.message);
                        }

                        $('#total').val('');
                         $('#discount').val('');
                         $('#discount_price').val('');

                        $('#discountModal').hide();

                },

            });
         }
      });

      $(document).on('input','#discount', function(){
        let originalPrice = parseFloat($('#total').val());

        let discountPercent = $(this).val();
        //alert(discountPercent);
        let discountAmount = (originalPrice * discountPercent) / 100;
        let finalPrice = originalPrice - discountAmount;
        $('#discount_price').val(finalPrice.toFixed(2));
      });

    });
  </script>

@endpush
