@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-10">
                   <h4 class="card-title mb-3">Questionnaire Template</h4>
                </div>
                <div class="col-md-1">
                 <!-- <a class="float-right " href="questionnaire" ><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Questionnaire Template"></i></a>   -->
                 <a class="" href="RpmDecisionTree"><i class="add-icons i-Blinklist" data-toggle="tooltip" data-placement="top" title="Add Decision Tree Template"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Decision Tree Template"></i></a> 
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
                <div class="table-responsive">
                    <table id="mailList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Content Name</th>
                            <th>Content Type</th> 
                            <!-- <th>Services</th> -->
                            <th>Sub Services</th>                 
                            <!-- <th>Content</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        @foreach($data as $value)
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>{{ $value->content_title }}</td>
                            <td>{{ $value->template->template_type }}</td>
                            <!-- <td>{{ $value->service->service_name }}</td> -->
                            <td>
                                {{-- @if(isset($value->subservice->sub_services))
                                    {{ $value->subservice->sub_services }}
                                @endif --}}
                                @if(isset($value->moduleComponents->components))
                                    {{ $value->moduleComponents->components }}
                                @endif
                            </td>
                            <!-- <td>{{ $value->content }}</td> -->
                            <td>
                                <a href="updateQuestionnaire/{{ $value->id }}" ><i class=" editform i-Pen-4" style="color: #2cb8ea;"></i></a>
                                <a href="viewQuestionnaireDetails/{{ $value->id }}"><i class="text-15 i-Eye" style="color: green;"></i></a>
                                <a href="deleteQuestionnaire/{{ $value->id }}"><i class=" i-Close" style="color: #2cb8ea;"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>

            </div>
        </div>
    </div>
</div>
     


  

  
@endsection

@section('page-js')
 <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
    
    <script type="text/javascript">
  $(function () {
      var table = $('#mailList').DataTable({
       // processing: true,
        //serverSide: true,

    });

  });

</script> 

@endsection

 