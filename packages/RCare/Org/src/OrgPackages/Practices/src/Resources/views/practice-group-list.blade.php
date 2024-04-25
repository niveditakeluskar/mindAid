@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
<div class="row">
  <div class="col-lg-12 mb-3">
    <div class="card">
        <div class=" card-body">
          <div class="row">
            <div class="col-md-10">
             <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{config('global.practice_group')}} Data saved successfully! </strong><span id="text"></span>
              </div>
              <div id ="practice-grp-success"></div>
              <input type="hidden" name="msg" id="msg" value="{{config('global.practice_group')}}"> 
            </div> 
            <div class="col-md-2">
              <a class="btn btn-success btn-sm mb-4 ml-5" href="javascript:void(0)" id="addPracticeGrp">Add {{config('global.practice_group')}}</a>  
            </div>
            <div class="separator-breadcrumb border-top"></div> 
          <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
              @include('Theme::layouts.flash-message')
            <div class="table-responsive">
              <table id="practiceGrpList" class="display table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>Sr No.</th>
                          <th>{{config('global.practice_group')}}</th>     
                          <th>Enable Messaging</th> 
                          <th>Quality Metrics</th>
                          <th>Threshold</th> 
                          <th>Last Modified By</th>
                          <th>Last Modified On</th>                 
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="add_practice_grp_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 635px;">
        <div class="modal-content">  
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1"><span id="spanHeading1">Add</span> {{config('global.practice_group')}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('create_org_practice_group') }}" method="POST" name="AddPracticeGrpForm" id="AddPracticeGrpForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="id" id="id"> 
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <label for="practicename">{{config('global.practice_group')}}  <span style="color:red">*</span></label>  
                                @text("practice_name", ["placeholder" => "Enter ".config('global.practice_group') ]) 
                            </div>  
                            <!-- <div class="col-md-12 form-group mb-3"> 
              					<label>
              						<yes-no name="assign_message" label-no="No" label-yes="Yes">Enable Text</yes-no>
              					</label>
              				</div>             
                            <div class="col-md-12 form-group mb-3"> 
                            <label >Quality Metrics</label><br>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-primary btn-toggle ">
                                        <input type="radio" name="quality_metrics" id="option1" value="1" autocomplete="off" > Yes
                                    </label>
                                    <label class="btn btn-outline-primary btn-toggle">
                                        <input type="radio" name="quality_metrics" id="option2" value="0" autocomplete="off"> No
                                    </label>
                                </div>
              				</div>   
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1 save_practicegrp" id="btnHeading1">Save</button>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('Practices::org-threshold')  