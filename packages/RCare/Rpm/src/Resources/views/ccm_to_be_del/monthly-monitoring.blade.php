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
		@include('Rpm::ccm.traningsteps.patient-overview')
		<!-- SmartWizard html -->
		<div id="smartwizard">
			<ul>
				<li class=""><a href="#step-1 ">Step 1<br /><small></small></a></li>
				<li class=""><a href="#step-2">Step 2<br /><small></small></a></li>
				<li><a href="#step-3">Step 3<br /><small></small></a></li>
				<!-- <li><a href="#step-4">Step 4<br /><small></small></a></li>
				<li><a href="#step-5">Step 5<br /><small></small></a></li> -->
			</ul>

			<div>
				<div id="step-1" class="">
					@include('Rpm::ccm.traningsteps.patient-monthly-training-step-1')
					 
				</div>
					<div id="step-2" class="" >
					
	           @include('Rpm::ccm.traningsteps.patient-calling')
					
				</div>
				
				<div id="step-3" class="">
					
					@include('Rpm::ccm.traningsteps.follow-up')
                
				</div>
			
				 <div id="step-4" class="">
					
				</div> 
				<div id="step-5" class="">
					
				</div> 
			</div>
		</div>
	</div>
	
</div>
@endforeach
@endsection
<script type="text/javascript">
	
	function final() {
		  var checkBox = document.getElementById("finalCheck");
		  var text = document.getElementById("text");
		  if (checkBox.checked == true){
		    text.style.display = "block";
		  } else {
		     text.style.display = "none";
		  }
		}

</script>