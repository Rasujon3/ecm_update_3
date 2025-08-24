@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Delivery partnes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/app-settings')}}">Delivery partnes
                                </a></li>
                        <li class="breadcrumb-item active">Delivery partnes</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Delivery partnes</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-app')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-header bg-primary">
                              <h5 class="card-title">SteadFast</h5>  
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label for="courier_api_key">API Key <span class="required">*</span></label>
                                <input type="text" name="courier_api_key" class="form-control" id="courier_api_key"
                                    placeholder="API Key"  value="{{old('courier_api_key',$setting?$setting->courier_api_key:"")}}">
                                @error('courier_api_key')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                              </div>  

                              <div class="form-group">
                                <label for="courier_secret">API Secret <span class="required">*</span></label>
                                <input type="text" name="courier_secret" class="form-control" id="courier_secret"
                                    placeholder="API Secret"  value="{{old('courier_secret',$setting?$setting->courier_secret:"")}}">
                                @error('courier_secret')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                             </div>

                              <div class="form-group">
                                <label for="merchant_id">Merchant ID <span class="required">*</span></label>
                                <input type="text" name="merchant_id" class="form-control" id="merchant_id"
                                    placeholder="Merchant ID"  value="{{old('merchant_id',$setting ? $setting->merchant_id : "")}}">
                                @error('merchant_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                             </div>

                            </div>  
                          </div>  
                        </div>


                        <div class="col-md-6 d-none">
                          <div class="card">
                            <div class="card-header bg-info">
                              <h5 class="card-title">Pathao</h5>  
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label for="pathao_client_id">Client ID <span class="required">*</span></label>
                                <input type="text" name="pathao_client_id" class="form-control" id="pathao_client_id"
                                    placeholder="Client ID"  value="{{old('pathao_client_id',$setting?$setting->pathao_client_id:"")}}">
                                @error('pathao_client_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                              </div>  

                              <div class="form-group">
                                <label for="pathao_client_secret">Client Secret <span class="required">*</span></label>
                                <input type="text" name="pathao_client_secret" class="form-control" id="pathao_client_secret"
                                    placeholder="Client Secret"  value="{{old('pathao_client_secret',$setting?$setting->pathao_client_secret:"")}}">
                                @error('pathao_client_secret')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                             </div>

                              <div class="form-group">
                                <label for="pathao_access_token">Access Token <span class="required">*</span></label>
                                <textarea class="form-control" name="pathao_access_token" id="pathao_access_token" placeholder="Access Token">{!!old('pathao_access_token',$setting?$setting->pathao_access_token:"")!!}</textarea>
                                @error('pathao_access_token')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                             </div>

                            </div>  
                          </div>  
                        </div>


                        <div class="col-md-4 d-none">
                          <div class="card">
                            <div class="card-header bg-warning">
                              <h5 class="card-title text-light">Others</h5>  
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label for="facebook_pixel_id">Facebook Pixel ID <span class="required">*</span></label>
                                <input type="text" name="facebook_pixel_id" class="form-control" id="facebook_pixel_id"
                                    placeholder="Facebook Pixel ID"  value="{{old('facebook_pixel_id',$setting?$setting->facebook_pixel_id:"")}}">
                                @error('facebook_pixel_id')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                               </div>  


                               <div class="form-group">
                                <label for="delivery_charge">Delivery charge (%) <span class="required">*</span></label>
                                <input type="text" name="delivery_charge" class="form-control numericInput" id="delivery_charge"
                                    placeholder="Delivery charge"  value="{{old('delivery_charge',$setting?$setting->delivery_charge:"")}}">
                                @error('delivery_charge')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                               </div>


                               <div class="form-group">
                                <label for="order_note">Order Note</label>
                                <textarea class="form-control" id="order_note" rows="3" col="30" name="order_note" placeholder="Order Note">{!!old('order_note',$setting?$setting->order_note:"")!!}</textarea>
                                @error('order_note')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                              </div>    


                            </div>  
                          </div>  
                        </div>
                        
                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-success btn-block">Save Changes</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
        </div>
    </section>
</div>
@endsection