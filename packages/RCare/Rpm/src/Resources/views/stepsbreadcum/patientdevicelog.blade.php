@include('Theme::layouts.flash-message')			
<div class="alert" id="message" style="display: none"></div>

<!-- <div class="breadcrusmb">
    <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Users</h4>
		</div>
		 <div class="col-md-1">
		 <a class="" href="javascript:void(0)" id="addUser"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add User"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add User"></i></a>  
		</div>
    </div>
   <!- - ss - ->             
</div> -->
<!-- <div class="separator-breadcrumb border-top"></div> -->
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="40px">Sr No.</th>
                            <th width="100px">Timestamp</th>
                            <th width="100px">Heart Rate</th>
                            <th width="100px">SpO2</th>
                            <th width="100px">Perfusion Index</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=1;$i<=10; $i++)
                        <tr>
                            <td width="40px">{{$i}}</td>
                            <td width="100px">11/20/2019 8:41 PM</td>
                            <td width="100px">66</td>
                            <td width="100px">96</td>
                            <td width="100px">40.3</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
                </div>

            </div>
        </div>
    </div>
</div>