@extends('Theme::layouts.to-do-master')

@section('main-content')
@foreach($patient as $checklist)

<div class="breadcrusmb">
	<div class="row">
		<div class="col-md-11">
			<h4 class="card-title mb-3">{{$checklist->fname}} {{$checklist->lname}}</h4>
		</div>
	</div>
	<!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row  mb-4 text-align-center">
	<div class="col-md-11  mb-4 patient_details">
		@include('Rpm::stepsbreadcum.patient-overview')
		<!-- SmartWizard html -->
		<div id="smartwizard">
			<ul>
				<li><a href="#step-1 done">Step 1<br /><small></small></a></li>
				<li class="active"><a href="#step-2">Step 2<br /><small>Calling</small></a></li>
				<li><a href="#step-3">Step 3<br /><small></small></a></li>
				<li><a href="#step-4">Step 4<br /><small></small></a></li>
				<li><a href="#step-5">Step 5<br /><small></small></a></li>
			</ul>

			<div>
				<div id="step-1" class="">
					@include('Rpm::stepsbreadcum.patient-device-training')
				</div>
				<div id="step-2" class="" style="display: block;">
					@include('Rpm::stepsbreadcum.calling_initial_research_followup')
				</div>
				<div id="step-3" class="">
					@include('Rpm::stepsbreadcum.patientdevicelog')
				</div>
				<div id="step-4" class="">
					@include('Rpm::stepsbreadcum.blank')
				</div>
				<div id="step-5" class="">
					@include('Rpm::stepsbreadcum.blank')
				</div>
			</div>
		</div>
	</div>
	
</div>
@endforeach
@endsection