@extends('Theme::layouts_2.to-do-master')
@section('main-content')
    @foreach($patient as $checklist)
    <div class="separator-breadcrumb "></div>
    <div class="row text-align-center">
        @include('Theme::layouts_2.flash-message')  
        <div class="col-md-12">
            {{ csrf_field() }}
            <!--Add view Patient Overview -->
            @include('Ccm::monthly-service.patient-overview')
        </div>
    </div>
    @endforeach
@endsection

