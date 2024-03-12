@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Test </h4>
                </div>
                 <div class="col-md-1">
                 <!-- <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>   -->
                 <a class="btn btn-success btn-sm " href="{{ route('test-add') }}" id="addUser"> Add Test User</a>
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
            <h2>Ayesh\PHP_Timer\Timer</h2>
            <?php
                use Ayesh\PHP_Timer\Timer;
                
                Timer::start('full');

                Timer::start('laps');
                sleep(1);
                Timer::stop('laps');
                
                sleep(2); // This time is not calculated under 'laps'
                
                Timer::start('laps');
                sleep(1);
                Timer::stop('laps');
                
                echo Timer::read('full', Timer::FORMAT_SECONDS); // 4 seconds.
                echo "<br />";
                echo Timer::read('laps', Timer::FORMAT_SECONDS); // 2 seconds (1 + 1)
            ?>
            </div>
        </div>
    </div>
</div>
  
@endsection

@section('page-js')


@endsection

 