@extends('Theme::layouts.master')
@section('main-content')
<div class="row mb-4" id="call-sub-steps">
    <div class="col-md-12">
        <div class="card12"> 
            <div class="row ">
                <div class="col-md-12 ">
                    <div class="card text-left">
                        <div class="card-body" >
                            <h4 class="card-title mb-3">Surgery</h4>
                            <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="provider-icon-tab" data-toggle="tab" href="#provider" role="tab" aria-controls="provider" aria-selected="true"><i class="nav-icon color-icon i-Telephone mr-1"></i>Surgery</a>
                                </li>
                                
                                <li class="nav-item"> 
                                    <a class="nav-link" id="categorys-icon-tab" data-toggle="tab" href="#categorys" role="tab" aria-controls="categorys" aria-selected="false"><i class="nav-icon color-icon i-Home1 mr-1"></i>Category</a>
                                </li>

                                <li class="nav-item"> 
                                    <a class="nav-link" id="sub-category-icon-tab" data-toggle="tab" href="#sub-category" role="tab" aria-controls="sub-category" aria-selected="false"><i class="nav-icon color-icon i-Home1 mr-1"></i>Sub Category</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myIconTabContent">
                                <div class="tab-pane show active" id="provider" role="tabpanel" aria-labelledby="provider-icon-tab">
                                    @include('Medication::surgery-list')
                                </div>
                                
                                <div class="tab-pane " id="categorys" role="tabpanel" aria-labelledby="categorys-icon-tab">
                                    @include('Medication::category-list')
                                </div>

                                <div class="tab-pane " id="sub-category" role="tabpanel" aria-labelledby="sub-category-icon-tab">
                                    @include('Medication::sub-category-list')
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
    var renderSurgeryTable =  function() {
        var columns= [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'category', name: 'category'},
            {data: 'sub_category', name: 'sub_category'},
            {data: 'duration', name: 'duration'},
            {data: 'users',
                mRender: function(data, type, full, meta){
                if(data!='' && data!='NULL' && data!=undefined){
                l_name = data['l_name'];
                if(data['l_name'] == null && data['f_name'] == null){
                l_name = '';
                return '';
                }
                else
                {

                return data['f_name'] + ' ' + l_name;
                }
                } else { 
                    return ''; 
                }    
                },orderable: false
                }, 
            {data: 'updated_at',name:'updated_at',
                render: function(data, type, row) {
                var date = new Date(data);
                return date.toLocaleDateString(); // Formats the date according to the user's locale
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
        var table = util.renderDataTable('MedicationList', "{{ route('org_medication_list') }}", columns, "{{ asset('') }}");   
    };


    var renderCategoryTable =  function() {
        var columns=[
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'category', name: 'category'},    
            {data: 'users.f_name', name: 'users.f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.users.l_name;
                        }else{ return '';} 
                        }
                    },
            {data: 'updated_at', name: 'updated_at',
                render: function(data, type, row) {
                var date = new Date(data);
                return date.toLocaleDateString(); // Formats the date according to the user's locale
                }
            },      
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('CategoryList', "{{ route('org_medication_category_list') }}", columns, "{{ asset('') }}");   
    };


    var renderSubcategoryTable =  function() {
        var columns=[
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'category', name: 'category'}, 
            {data: 'sub_category', name: 'sub_category'},    
            {data: 'users.f_name', name: 'users.f_name',render: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return data + ' ' + full.users.l_name;
                        }else{ return '';} 
                        }
                    },
            {data: 'updated_at', name: 'updated_at',
                render: function(data, type, row) {
                var date = new Date(data);
                return date.toLocaleDateString(); // Formats the date according to the user's locale
                }
            },      
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('SubCategoryList', "{{ route('org_medication_subcategory_list') }}", columns, "{{ asset('') }}");   
    };


    $(document).ready(function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });

    // Explicitly initialize the tabs if needed
    $('#myIconTab a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
        renderSurgeryTable();
        renderCategoryTable();
        renderSubcategoryTable();
        // providers.init();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 

        $("#AddCategory").click(function(){
            $("#AddCategoryForm")[0].reset(),
            $(".invalid-feedback").text(""),
            $(".form-control").removeClass("is-invalid"),
            $("#add_category_modal").modal("show");
        });

        
        $("#addSubcategory").click(function(){
            $("#AddSubcategoryForm")[0].reset(),
            $(".invalid-feedback").text(""),
            $(".form-control").removeClass("is-invalid"),
            $("#add_subcategory_modal").modal("show");
        });
// for UI 
        // $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        //    $($.fn.dataTable.tables(true)).DataTable()
        //       .columns.adjust();
        // });
    });
</script>
@endsection
