@extends('Theme::layouts.master')
@section('main-content')
<div class="row mb-4" id="call-sub-steps">
    <div class="col-md-12">
        <div class="card12"> 
            <div class="row ">
                <div class="col-md-12 ">
                    <div class="card text-left">
                        <div class="card-body" >
                            <h4 class="card-title mb-3">Provider</h4>
                            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="provider-icon-tab" data-toggle="tab" href="#provider" role="tab" aria-controls="provider" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Provider</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="provider-type-icon-tab" data-toggle="tab" href="#provider-type" role="tab" aria-controls="provider-type" aria-selected="false"><i class="nav-icon color-icon i-Gears mr-1"></i>Provider Type</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="provider-subtype-icon-tab" data-toggle="tab" href="#provider-subtype" role="tab" aria-controls="provider-subtype" aria-selected="false"><i class="nav-icon color-icon i-Home1 mr-1"></i>Credential</a>
                                </li>
                                 <li class="nav-item">
                                    <a class="nav-link" id="provider-speciality-icon-tab" data-toggle="tab" href="#provider-speciality" role="tab" aria-controls="provider-speciality" aria-selected="false"><i class="nav-icon color-icon i-Home1 mr-1"></i>Speciality</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="provider" role="tabpanel" aria-labelledby="provider-icon-tab">
                                    @include('Providers::provider-list')
                                </div>
                                <div class="tab-pane" id="provider-type" role="tabpanel" aria-labelledby="provider-type-icon-tab">
                                     @include('Providers::provider-type-list')
                                </div>
                                <div class="tab-pane " id="provider-subtype" role="tabpanel" aria-labelledby="provider-subtype-icon-tab">
                                    @include('Providers::provider-subtype-list')
                                </div>
                                <div class="tab-pane " id="provider-speciality" role="tabpanel" aria-labelledby="provider-speciality-icon-tab">
                                    @include('Providers::provider-speciality-list')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="app"></div>
@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    var renderProvidersTable =  function() {
        var columns=[
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'practices', name: 'practices'},
            {data: 'provider_type', name: 'provider_type'},
            // {data: 'provider_subtype_id', name: 'provider_subtype_id'},
            {data: 'provider_subtype_id',  name: 'provider_subtype_id',mRender: function(data, type, full, meta){
                if(data!='' && data!='NULL' && data!=undefined  && data!='0'){ 
                    return full['sub_provider_type'];     
                }else if(data=='0'){ 
                    return 'Other';
                }else{ return ''; }
            },
            orderable: false
            },
            {data: null, mRender: function(data, type, full, meta){
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['speciality'];
                                        }else { return ""; } 
                                    },
                                    orderable: false},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'address', name: 'address'},
            {data: 'f_name', name: 'f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.l_name;
                        }else{ return '';}
                        }
                    },
            // {data: 'updated_at', name: 'updated_at'},
            {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
                   if (value === null) return "";
                        return util.viewsDateFormatWithTime(value);
                    }
                },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('providerList', "{{ route('org_providers_list') }}", columns, "{{ asset('') }}");   
    };

    var renderProvidersTypeTable =  function() {
        var columns=[
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'provider_type', name: 'provider_type'},
            {data: 'users.f_name', name: 'users.f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.users.l_name;
                        }else{ return '';}
                        }
                    },
            // 
            {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
               if (value === null) return "";
                    return util.viewsDateFormatWithTime(value);
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('providerTypeList', "{{ route('org_providers_type_list') }}", columns, "{{ asset('') }}");   
    };

    var renderProvidersSubtypeTable =  function() {
        var columns=[
                 {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                 {data: 'sub_provider_type', name: 'sub_provider_type'},
                 {data: 'provider_type', name: 'provider_type'},
                 {data: 'f_name', name: 'f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.l_name;
                        }else{ return '';}
                        }
                    },
            // 
                {data: 'updated_at',type: 'date-dd-mm-yyyy h:i:s', name: 'updated_at',"render":function (value) {
                   if (value === null) return "";
                        return util.viewsDateFormatWithTime(value);
                    }
                },
                 {data: 'action', name: 'action', orderable: false, searchable: false},
               ];
        var table = util.renderDataTable('providerSubTypeList', "{{ route('org_providers_subtype_list') }}", columns, "{{ asset('') }}");   
    };

    var renderSpecialityTable =  function() {
        var columns=[
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'speciality', name: 'speciality'},    
            {data: 'users.f_name', name: 'users.f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.users.l_name;
                        }else{ return '';}
                        }
                    },
            {data: 'updated_at', name: 'updated_at'},      
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('SpecialityList', "{{ route('org_providers_speciality_list') }}", columns, "{{ asset('') }}");   
    };

    $("form[name='AddProviderForm'] #providertype").change(function () {
        var provider_type_id = this.value;
        if(provider_type_id!=0){
        $.ajax({
            type: 'post',
            url: '/org/provider/subtypeProviders', 
            data: 'provider_type_id=' + provider_type_id,
            success: function(response) {
            var len = response.length;
            // console.log(len);
                $("form[name='AddProviderForm'] #provider_subtype_id").empty();
                if(len!=0){
                    for( var i = 0; i<len; i++){
                    $("form[name='AddProviderForm'] #subtype").show();
                    var id = response[i]['id'];
                    var name = response[i]['sub_provider_type'];
                    $("form[name='AddProviderForm'] #provider_subtype_id").append("<option value='"+id+"'>"+name+"</option>");
                    }
                }else{
                    $("form[name='AddProviderForm'] #subtype").hide();
                }
                 
            } 
        });
        }
        else{
            $("form[name='AddProviderForm'] #subtype").hide();
        }
    });
        
    function providerSubtype(val) {
       var provider_type_id = val.value;
       $.ajax({
            type: 'post',
            url: '/org/provider/subtypeProviders', 
            data: 'provider_type_id=' + provider_type_id,
            success: function(response) {
            var len = response.length;
            console.log(len);
                $("#provider_subtype_id").empty();
                if(len!=0){
                    for( var i = 0; i<len; i++){
                    $("#subtype").show();
                    var id = response[i]['id'];
                    var name = response[i]['sub_provider_type'];
                    $("#provider_subtype_id").append("<option value='"+id+"'>"+name+"</option>");
                    }
                }else{
                    $("#subtype").hide();
                }
                 
            } 
        });
    }

    $(document).ready(function() {
        renderProvidersTable();
        renderProvidersSubtypeTable();
        renderProvidersTypeTable();
        renderSpecialityTable();
        providers.init();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
// for UI 
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
           $($.fn.dataTable.tables(true)).DataTable()
              .columns.adjust();
        });
    });
</script>
@endsection
