@extends('Theme::layouts.master')
@section('main-content')
<meta name="csrf-token" content="{{ csrf_token() }}" />


<div class="breadcrusmb">

    <div class="row">
		<div class="col-md-12">
		   <h4 class="card-title mb-3">Edit Diagnosis Code</h4>
		</div>
		 
	</div>
              
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
	
	<div id="success">
	</div>	
	<div class="card" style=" margin-left: 260px; margin-right: 295px;">
		<div class="card-body" id="hobby">
			<form action="{{ route("ajax.save.diagnosis")}}" method="post" name ="main_edit_diagnosis_form"  id="main_edit_diagnosis_form">
				@csrf
				<div class="alert alert-success" id="success-alert" style="display: none;">
					<button type="button" class="close" data-dismiss="alert">x</button>
					<strong> Diagnosis Code Updated successfully! </strong><span id="text"></span>
				</div>
				<input type="hidden" name="id" value="">
				@include('Diagnosis::diagnosis')
			</form>
		</div>	
	</div>	
	</div>	
</div>	

@endsection
@section('page-js')
	<script type="text/javascript">
		$(document).ready(function() {
			diagnosisCode.init();
			util.getToDoListData(0, {{getPageModuleName()}});
		});
	</script>
@endsection