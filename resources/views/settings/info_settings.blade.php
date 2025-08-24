@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Info Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/info-settings')}}">Info Settings
                                </a></li>
                        <li class="breadcrumb-item active">Info Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Info Settings</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{url('settings-info')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                       
                       <div class="col-md-4">
                       	 <div class="card">
                       	   <div class="card-header bg-primary">
                       	   	 <h5 class="card-title">Privacy & Policy</h5>
                       	   </div>
                       	   <div class="card-body">
                       	   	 <div class="form-group">
                       	   	  <label for="privacy_policy">Privacy & Policy</label>
                       	   	  <textarea name="privacy_policy" class="form-control description" id="privacy_policy" placeholder="Privacy & Policy">{!!old('privacy_policy',$info?$info->privacy_policy:"")!!}</textarea> 
                       	   	 </div>
                       	   </div>	
                       	 </div>
                       </div> 

                       <div class="col-md-4">
                       	 <div class="card">
                       	   <div class="card-header bg-info">
                       	   	 <h5 class="card-title">Contact US</h5>
                       	   </div>
                       	   <div class="card-body">
                       	   	 <div class="form-group">
                       	   	  <label for="contact_name">Contact Name</label>
                       	   	  <input type="text" class="form-control" name="contact_name" id="contact_name" placeholder="Contact Name" value="{{old('contact_name',$info?$info->contact_name:"")}}"> 
                       	   	 </div>

                       	   	 <div class="form-group">
                       	   	  <label for="contact_phone">Contact Phone</label>
                       	   	  <input type="text" class="form-control" name="contact_phone" id="contact_phone" placeholder="Contact Phone" value="{{old('contact_phone',$info?$info->contact_phone:"")}}"> 
                       	   	 </div>


                       	   	 <div class="form-group">
                       	   	  <label for="contact_email">Contact Email</label>
                       	   	  <input type="text" class="form-control" name="contact_email" id="contact_email" placeholder="Contact Email" value="{{old('contact_email',$info?$info->contact_email:"")}}"> 
                       	   	 </div>


                       	   	 <div class="form-group">
                       	   	  <label for="contact_address">Contact Address</label>
                       	   	  <textarea name="contact_address" class="form-control" id="contact_address" placeholder="Contact Description">{!!old('contact_address',$info?$info->contact_address:"")!!}</textarea>
                       	   	 </div> 

                       	   	 <div class="form-group">
                       	   	  <label for="contact_description">Contact Description</label>
                       	   	  <textarea name="contact_description" class="form-control" id="contact_description" placeholder="Contact Description">{!!old('contact_description',$info?$info->contact_description:"")!!}</textarea>
                       	   	 </div>

                       	   </div>	
                       	 </div>
                       </div>


                       <div class="col-md-4">
                       	 <div class="card">
                       	   <div class="card-header bg-warning">
                       	   	 <h5 class="card-title text-light">About Us</h5>
                       	   </div>
                       	   <div class="card-body">
                       	   	 <div class="form-group">
                       	   	  <label for="about_us">About Us</label>
                       	   	  <textarea name="about_us" class="form-control description" id="about_us" placeholder="About Us">{!!old('about_us',$info?$info->about_us:"")!!}</textarea> 
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