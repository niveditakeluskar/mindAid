@extends('Theme::layouts.master')
@section('page-css')

@endsection

@section('main-content')
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <h3 class="card-title mb-3">Add Services</h3>
            
                <form class="form-inline" method="post" action="{{ route('AddServices') }}"> 
                    @csrf
                    <label for="service_name" class="col-md-2">Service Name:</label>
                    <input type="text" class="form-control col-md-3" id="service_name" placeholder="Enter Service Name" name="service_name">   
                    <label for="status" class="col-md-2">Status:</label>
                    <select class="custom-select form-control col-md-3" name="status">
                        <option value="1">Active</option>
                        <option value="0">In Active</option>
                    </select>        
                    <button type="submit" class="btn btn-info " name="service">Save</button> 
                </form>
            </div>
        </div>
    </div>
</div>             
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <h3 class="card-title mb-3">Add Sub Services</h3>
                <form class="form-inline" method="post" action="{{ route('AddServices') }}"> 
                    @csrf
                    <label for="service_name" class="col-md-2">Service</label>
                    <select class="custom-select form-control col-md-3" name="services_id">
                        @foreach($service as $value)
                            <option value="{{ $value->id }}">{{ $value->service_name }}</option>
                        @endforeach
                    </select>     
                    <label for="status" class="col-md-2">Sub Service:</label>
                    <input type="text" class="form-control col-md-3" id="sub_services" placeholder="Enter Sub Service Name" name="sub_services">    
                    <button type="submit" class="btn btn-info ">Save</button> 
                </form>
            </div>
        </div>
    </div>
</div>   
 

<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card text-left">
            <div class="card-body">
            <h4 class="card-title mb-3">Services</h4>
            <div class="table-responsive">
                    <table id="mailList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Service Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        @foreach($service as $value)
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>{{ $value->service_name }}</td>
                            <td>
                                <a href="/updatemailtemplate/{{ $value->id }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/deletemailtemplate/{{ $value->id }}" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>  
            </div>
        </div>
    </div>
   



    <div class="col-md-6 mb-4">
        <div class="card text-left">
            <div class="card-body">
            <h4 class="card-title mb-3">Sub Services</h4>
                <div class="table-responsive">
                    <table id="mailList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Service Name</th>
                            <th>Sub Service Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; ?>
                        @foreach($sub_service as $values)
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>{{ $values->services->service_name }}</td>
                            <td>{{ $values->sub_services }}</td>
                            <td>
                                <a href="/updatemailtemplate/{{ $values->id }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="/deletemailtemplate/{{ $values->id }}" class="btn btn-danger btn-sm">Delete</a>
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

@endsection