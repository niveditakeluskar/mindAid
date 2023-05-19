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
                                         <h1 class="mb-3 text-18">Add Org</h1>
                                        </div>
                                           <div class="col-md-2">
                                         <a href="{{ route('rcareorgs') }}" class="btn btn-primary btn-block btn-rounded mt-3">Go Back</a> 
                                        </div>
                                </div>
                            </div>
               <form action="{{ route('createOrgs') }}" method="POST">
                 {{ csrf_field() }}
                <div class="form-group">
                <div class="row">
                <div class="col-md-12">
                <label for="name">Name</label>
                          @text("name", ["id" => "name", "class" => "form-control form-control-rounded"])

                          <label for="uid">UID</label>
                          @text("uid", ["id" => "uid", "class" => "form-control form-control-rounded"])

                          <label for="add1">Address Line 1</label>
                          @text("add1", ["id" => "add1", "class" => "form-control form-control-rounded"])

                          <label for="add2">Address Line 2</label>
                          @text("add2", ["id" => "add2", "class" => "form-control form-control-rounded"])

                        </div>
                       
                        <div class="col-md-6">
                         <label for="city">City</label>
                          @text("city", ["id" => "city", "class" => "form-control form-control-rounded"])
                        </div>  
                        
                         <div class="col-md-6">
                         <label for="state">State</label>
                          @text("state", ["id" => "state", "class" => "form-control form-control-rounded"]) 
                          </div>


                          <div class="col-md-6">
                         <label for="zip">Zip Code</label>
                          @text("zip", ["id" => "zip", "class" => "form-control form-control-rounded"])
                          </div>

                            <div class="col-md-6">
                          <label for="zip">Phone</label>
                          @text("phone", ["id" => "phone", "class" => "form-control form-control-rounded"])
                          </div>

                        
          

               <div class="col-md-12">
                          
                          <label for="email">Email</label>
                          @email("email", ["id" => "email", "class" => "form-control form-control-rounded"])

                          <label for="contact_person">Contact Person</label>
                          @text("contact_person", ["id" => "contact_person", "class" => "form-control form-control-rounded"])

                          <label for="contact_person_phone">Contact Person Phone</label>
                          @text("contact_person_phone", ["id" => "contact_person_phone", "class" => "form-control form-control-rounded"])

                          <label for="contact_person_email">Contact Person Email</label>
                          @email("contact_person_email", ["id" => "contact_person_email", "class" => "form-control form-control-rounded"])

                           <label for="Status">Category</label>
                                @selectcategory("category",["id" => "category", "class" => "form-control form-control-rounded"])




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
