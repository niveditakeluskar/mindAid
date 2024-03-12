@extends('layouts.master')
@section('main-content')
<div class="breadcrumb">
                <h1>Blank Page</h1>
                <ul>
                    <li><a href="">UI Kits</a></li>
                    <li>Blank Page</li>
                </ul>

            </div>

            <div class="separator-breadcrumb border-top"></div>
            {{-- end of breadcrumb --}}
             @include('layouts.flash-message')
              <div class="auth-layout-wrap">
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4">
                            
                              <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-10">
                                         <h1 class="mb-3 text-18">Add User</h1>
                                        </div>
                                           <div class="col-md-2">
                                         <a href="{{ route('rcareorgs') }}" class="btn btn-primary btn-block btn-rounded mt-3">Go Back</a> 
                                        </div>
                                </div>
                            </div>
               <form action="{{ route('update', $row->id) }}" method="POST">
                 {{ csrf_field() }}

            <div class="form-group">
            <div class="row">
            <div class="col-md-12">
            <label for="name">Name</label>
            @text("name", ["class" => "form-control form-control-rounded", "value" => "$row->name"])

            <label for="uid">UID</label>
            @text("uid", ["class" => "form-control form-control-rounded", "value" => "$row->uid"])
  
            <label for="add1">Address Line 1</label>
            @text("add1", [ "class" => "form-control form-control-rounded","value" => "$row->add1"])

            <label for="add2">Address Line 2</label>
            @text("add2", [ "class" => "form-control form-control-rounded","value" => "$row->add2"])

            </div>
          
            <div class="col-md-6">
            <label for="city">City</label>
            @text("city", [ "class" => "form-control form-control-rounded","value" => "$row->city"])

            </div>
            <div class="col-md-6">
            <label for="state">State</label>
            @text("state", [ "class" => "form-control form-control-rounded","value" => "$row->state"])     
            </div>


            <div class="col-md-6">
            <label for="zip">Zip Code</label>
            @text("zip", [ "class" => "form-control form-control-rounded","value" => "$row->zip"])

            </div>
            <div class="col-md-6">
            <label for="phone">Phone</label>
            @text("phone", [ "class" => "form-control form-control-rounded","value" => "$row->phone"])      
            </div>

            <div class="col-md-12">
            <label for="email">Email</label>
            @text("email", [ "class" => "form-control form-control-rounded","value" => "$row->email"])   

            <label for="contact_person">Contact Person</label>
            @text("contact_person", [ "class" => "form-control form-control-rounded","value" => "$row->contact_person"])

            <label for="contact_person_phone">Contact Person Phone</label>
            @text("contact_person_phone", [ "class" => "form-control form-control-rounded","value" => "$row->contact_person_phone"]) 

            <label for="contact_person_email">Contact Person Email</label>
            @text("contact_person_email", [ "class" => "form-control form-control-rounded","value" => "$row->contact_person_email"])
           </div>
           


                    </div>
                </div>
               
               
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
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
        </div>
    </div>

@endsection
