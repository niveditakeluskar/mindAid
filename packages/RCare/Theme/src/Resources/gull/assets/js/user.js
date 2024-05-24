 


 $(function () {
      var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('show') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'f_name', name: 'f_name'},
            {data: 'email', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });



