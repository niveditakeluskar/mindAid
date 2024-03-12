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
                            <div class="card-title mb-3">Edit Org</div>
                   </td>



                            @include('Theme::layouts.flash-message')
                            <form action="{{ route('update', $row->id) }}" method="POST">
                            {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="name">Name</label>
                                        @text("name", ["class" => "form-control form-control-rounded", "value" => "$row->name"])
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                         <label for="uid">UID</label>
                                         @text("uid", ["class" => "form-control form-control-rounded", "value" => "$row->uid"])
                                    </div>

                                   <div class="col-md-6 form-group mb-3">
                                         <label for="phone">Phone</label>
                                         @text("phone", [ "class" => "form-control form-control-rounded","value" => "$row->phone"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="email">Email</label>
                                         @text("email", [ "class" => "form-control form-control-rounded","value" => "$row->email"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                          <label for="add1">Address Line 1</label>
                                          @text("add1", [ "class" => "form-control form-control-rounded","value" => "$row->add1"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="add2">Address Line 2</label>
                                        @text("add2", [ "class" => "form-control form-control-rounded","value" => "$row->add2"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="city">City</label>
                                        @text("city", [ "class" => "form-control form-control-rounded","value" => "$row->city"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="state">State</label>
                                         @text("state", [ "class" => "form-control form-control-rounded","value" => "$row->state"])
                                    </div>

                                     <div class="col-md-6 form-group mb-3">
                                          <label for="zip">Zip Code</label>
                                          @text("zip", [ "class" => "form-control form-control-rounded","value" => "$row->zip"])
                          
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="contact_person">Contact Person</label>
                                         @text("contact_person", [ "class" => "form-control form-control-rounded","value" => "$row->contact_person"])
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                         <label for="contact_person_phone">Contact Person Phone</label>
                                         @text("contact_person_phone", [ "class" => "form-control form-control-rounded","value" => "$row->contact_person_phone"]) 
                                         
                          
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                           <label for="contact_person_email">Contact Person Email</label>
                                           @text("contact_person_email", [ "class" => "form-control form-control-rounded","value" => "$row->contact_person_email"])
                                    </div>

                                    </div>

                                    <div class="row">

                                    <div class="col-md-4">
                                    </div>
                                     <div class="col-md-6">
                                    </div>  
                                   <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Save Changes</button>
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
