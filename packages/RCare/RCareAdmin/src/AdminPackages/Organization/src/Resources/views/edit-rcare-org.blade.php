@extends('Theme::layouts.master')
@section('page-css')
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">


@endsection

@section('main-content')
   <div class="breadcrumb">
                <h1>Edit Organization</h1>
              <!--   <ul>
                    <li><a href="">Form</a></li>
                    <li>Edit Organization</li>
                </ul> -->
            </div>

            <div class="separator-breadcrumb border-top"></div>
            @include('Theme::layouts.flash-message')         
            <form action="{{ route('edit_org', $row->id) }}" method="POST">
                            {{ csrf_field() }}
             <div class="row">
                <!-- start Solid Bar -->
                    <div class="col-lg-4 mb-3">
                        <div class="card">
                                 <div class="card-body">
                                    <div class="card-title mb-3">Organization Details</div>
                                    <div class="form-row ">
                                        <div class="form-group col-md-12">
                                            <label for="name">Organization Name</label>
                                            @text("name", ["class" => "form-control  ", "value" => "$row->name"])
                                        </div>
                                     </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                           
                                             <label for="uid">UID</label>
                                         @text("uid", ["class" => "form-control  ", "value" => "$row->uid"])
                                        </div>
                                     </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="category">Category</label>
                                            @selectorgcategory("category", ["id" => "category", "class" => "form-control  "])
                                        </div>
                                    </div>
                                  
                                </div>
                            <!-- end::form -->
                        </div>
                    </div>
                    <!-- end Solid Bar -->
                    <div class="col-lg-8 mb-3">
                        <div class="card">                                
                            <!--begin::form-->                                
                                <div class="card-body">
                                <div class="card-title mb-3">Contact Details</div>
                                    <div class="form-row ">
                                        <div class="form-group col-md-6">
                                            <label for="phone">Phone</label>
                                         @number("phone", [ "class" => "form-control  ","value" => "$row->phone"])
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="email">Email</label>
                                         @email("email", [ "class" => "form-control  ","value" => "$row->email"])
                                        </div>
                                    </div>
                                    <div class="card-title mb-3">Contact Person</div>
                                    <div class="form-row mb-4">
                                        <div class="form-group col-md-4">


                                             <label for="contact_person">Contact Person</label>
                                             @text("contact_person", [ "class" => "form-control  ","value" => "$row->contact_person"])
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="contact_person_email">Contact Person Email</label>
                                            @email("contact_person_email", [ "class" => "form-control  ","value" => "$row->contact_person_email"])
                                        </div>

                                        <div class="form-group col-md-4">
                                              <label for="contact_person_phone">Contact Person Phone</label>
                                            @number("contact_person_phone", [ "class" => "form-control  ","value" => "$row->contact_person_phone"]) 
                                        </div>
                                    </div>
                                    
                                </div>

                            <!-- end::form -->
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title mb-3">Address</div>
                                <div class="form-row ">
                                    <div class="form-group col-md-6">
                                          <label for="add1">Address Line 1</label>
                                         <!--  @text("add1", [ "class" => "form-control  ","value" => "$row->add1"]) -->

                                         <textarea class="form-control" aria-label="With textarea" name="add1" >{{ $row->add1 }}</textarea>
                                    </div>

                                     <div class="form-group col-md-6">
                                     <label for="add1">Address Line 2</label>
                                           <!--  @text("add2", [ "class" => "form-control  ","value" => "$row->add2"]) -->

                                             <textarea class="form-control" aria-label="With textarea" name="add2" placeholder="Enter address line 1 " >{{ $row->add2 }}</textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                <div class="form-group col-md-4">
                                      <label for="city">City</label>
                                    
                                        @text("city", [ "class" => "form-control  ","value" => "$row->city"])
                                </div>

                                 <div class="form-group col-md-4">
                                    <label for="state">State</label>
                                     @text("state", [ "class" => "form-control  ","value" => "$row->state"])
                                </div>

                                <div class="form-group col-md-4">
                                      <label for="zip">Zip Code</label>
                                        @number("zip", [ "class" => "form-control  ","value" => "$row->zip"])
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12 mb-3">
                            <div class="card">
                                <div class="card-header ">
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="btn  btn-primary m-1">Save Changes</button>

                                         <a href="{{ route('addorg') }}" class="btn btn-outline-secondary m-1">Cancel</a> 
                                      
                                    </form>
                                    </div>
                                </div>                              
                            </div>
                        </div>
            </div>  


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>



@endsection

@section('bottom-js')
<script src="{{asset('assets/js/form.basic.script.js')}}"></script>


@endsection
