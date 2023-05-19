@extends('Theme::layouts.master')

@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
                <div class="col-md-11">
                <h4 class="card-title mb-3">Under Development</h4>
                </div>
                <div class="col-md-1">
                <!-- <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add User</a>   -->
                </div>
        </div>
        <!-- ss -->             
    </div>
    <div class="separator-breadcrumb border-top"></div>

    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
            <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                    @include('Theme::layouts.flash-message')
                    <h1 class="text-center">Under Development</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
@endsection