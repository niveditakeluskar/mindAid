@extends('Theme::layouts.master')

@section('main-content')
    
    <div class="row mb-4 mt-4">
        <div class="col-md-12 mb-4">
            <div class="card text-left">
                <div class="card-body">
            <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                    @include('Theme::layouts.flash-message')
                    <h1 class="text-center">Access Denied</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js')
@endsection