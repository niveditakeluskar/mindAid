@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

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
                                         <h1 class="mb-3 text-18">License</h1>
                                        </div>
                                           <div class="col-md-2">
                                      <!--    <a href="{{ route('rcareorgs') }}" class="btn btn-primary btn-block btn-rounded mt-3">Go Back</a> -->
                                        </div>
                                </div>
                            </div>


               <form action="{{ route('crateLicense') }}" method="POST">
                 {{ csrf_field() }}
                <div class="form-group">
                <div class="row">
                <div class="col-md-12">
                
                         <input id="org_id" name ="org_id" class="form-control form-control-rounded" type="hidden" value=" <?php print_r($_GET['id']); ?>">  


                         <input id="service_id" name ="service_id" class="form-control form-control-rounded" type="hidden" value=" <?php print_r($_GET['id']); ?>">  

                
                          <label for="name">Model</label>
                          @selectslicensemodel("license_model", ["id" => "license_model", "class" => "form-control form-control-rounded"])

                          <label for="subscription_in_months">Subscription in Month</label>
                          @number("subscription_in_months", ["id" => "subscription_in_months", "class" => "form-control form-control-rounded"])
                          <div class="row">
                          <div class="col-md-6">
                          <label for="start_date">Start Date</label>
                          @date("start_date", ["id" => "start_date", "class" => "form-control form-control-rounded"])
                         </div>

                         <div class="col-md-6">
                          <label for="end_date">End Date</label>
                          @date("end_date", ["id" => "end_date", "class" => "form-control form-control-rounded"])
                          </div>
                          </div>

                          <label for="Status">Status</label>
                                @selectactivestatus("status",["id" => "status", "class" => "form-control form-control-rounded"])

                          </div>
              

                     <div class="col-md-12">
                          
                         


                     </div>
           


                    </div>
                </div>
               
               
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                         <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
                        </div>
                        <div class="col-md-4">
                          
                        </div>
                           <div class="col-md-2">
                                         <button type="button" class="btn btn-primary btn-block btn-rounded mt-3" id="formButton">+</button>
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

<!-- multiple license -->
 <form action="{{ route('crateLicense') }}" method="POST" id="form1" style="display: none;">
 <div class="auth-layout-wrap">
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4">
      


              
                 {{ csrf_field() }}
                <div class="form-group">
                <div class="row">
                <div class="col-md-12">
                
                         <input id="org_id" name ="org_id" class="form-control form-control-rounded" type="hidden" value=" <?php print_r($_GET['id']); ?>">  


                         <input id="service_id" name ="service_id" class="form-control form-control-rounded" type="hidden" value=" <?php print_r($_GET['id']); ?>">  

                
                          <label for="name">Model</label>
                          @selectslicensemodel("license_model", ["id" => "license_model", "class" => "form-control form-control-rounded"])

                          <label for="subscription_in_months">Subscription in Month</label>
                          @number("subscription_in_months", ["id" => "subscription_in_months", "class" => "form-control form-control-rounded"])
                          <div class="row">
                          <div class="col-md-6">
                          <label for="start_date">Start Date</label>
                          @date("start_date", ["id" => "start_date", "class" => "form-control form-control-rounded"])
                         </div>

                         <div class="col-md-6">
                          <label for="end_date">End Date</label>
                          @date("end_date", ["id" => "end_date", "class" => "form-control form-control-rounded"])
                          </div>
                          </div>

                          <label for="Status">Status</label>
                                @selectactivestatus("status",["id" => "status", "class" => "form-control form-control-rounded"])

                          </div>
              

                     <div class="col-md-12">
                          
                         


                     </div>
           


                    </div>
                </div>
               
               
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                         <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
                        </div>
                        <div class="col-md-4">
                          
                        </div>
                           <div class="col-md-2">
                                         <button type="button" class="btn btn-primary btn-block btn-rounded mt-3" id="formButton">+</button>
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

<!-- end license -->

@endsection

@section('page-js')

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
        $("#formButton").click(function() {
        $("#form1").toggle();
         });
         });

        
    </script>

@endsection

