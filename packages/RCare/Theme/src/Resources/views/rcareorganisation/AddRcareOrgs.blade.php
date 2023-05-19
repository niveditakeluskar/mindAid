@extends('Theme::layouts.master')
@section('before-css')
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">


@endsection

@section('main-content')
   <div class="breadcrumb">
                <h1>Basic</h1>
                <ul>
                    <li><a href="">Form</a></li>
                    <li>Basic</li>
                </ul>
            </div>

            <div class="separator-breadcrumb border-top"></div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">Add Org</div>
                            @include('Theme::layouts.flash-message')
                           <form action="{{ route('createOrgs') }}" method="POST">
                 {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                         <label for="name">Name</label>
                                         @text("name", ["id" => "name", "class" => "form-control form-control-rounded", "placeholder" => "Enter your name"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="uid">UID</label>
                                         @text("uid", ["id" => "uid", "class" => "form-control form-control-rounded", "placeholder" => "Enter your uid"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="zip">Phone</label>
                                         @text("phone", ["id" => "phone", "class" => "form-control form-control-rounded", "placeholder" => "Enter your phone number"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="email">Email</label>
                                         @email("email", ["id" => "email", "class" => "form-control form-control-rounded", "placeholder" => "Enter your email id"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="add1">Address Line 1</label>
                                         @text("add1", ["id" => "add1", "class" => "form-control form-control-rounded", "placeholder" => "Enter your address"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="add1">Address Line 2</label>
                                         @text("add2", ["id" => "add2", "class" => "form-control form-control-rounded", "placeholder" => "Enter your address"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="city">City</label>
                                         @text("city", ["id" => "city", "class" => "form-control form-control-rounded", "placeholder" => "Enter city"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="state">State</label>
                                         @text("state", ["id" => "state", "class" => "form-control form-control-rounded", "placeholder" => "Enter state"]) 
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                          <label for="zip">Zip Code</label>
                                          @text("zip", ["id" => "zip", "class" => "form-control form-control-rounded", "placeholder" => "Enter zip code"])
                          
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="contact_person">Contact Person</label>
                                         @text("contact_person", ["id" => "contact_person", "class" => "form-control form-control-rounded", "placeholder" => "Enter contact person name"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                          <label for="contact_person_phone">Contact Person Phone</label>
                                         @text("contact_person_phone", ["id" => "contact_person_phone", "class" => "form-control form-control-rounded","placeholder" => "Enter contact person phone number "])
                                         
                          
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                          <label for="contact_person_email">Contact Person Email</label>
                                          @email("contact_person_email", ["id" => "contact_person_email", "class" => "form-control form-control-rounded","placeholder" => "Enter contact person phone email id "]) 
                                    </div>

                                   
                                    <div class="col-md-6 form-group mb-3">
                                          <label for="category">Category</label>
                                          @selectcategory("category", ["id" => "category", "class" => "form-control form-control-rounded"])
                                    </div>
                                    </div>

                                    <div class="row">

                                    <div class="col-md-4">
                                    </div>
                                     <div class="col-md-6">
                                    </div>  
                                   <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
                                    </div>
                          <!--  <div class="col-md-6">
                          <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
                        </div> -->
                </div>
                                </div>
                            </form>
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
