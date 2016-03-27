$(document).ready(function() {
    console.log( "ready!" );
    
    var filter = '';
    var total = 0;
    var page=1;
     // init bootpag
    
        //$('#example').DataTable();
        
        $('#example').DataTable( {
            "lengthMenu": [[2,5], [2,5]],
            "sPaginationType": "full_numbers",
            "processing": true,
            "serverSide": true,
            "ajax": 'index.php?r=book/mybooks',
            "columns": [
                { "title":"Reservation Number","data": "_id.$id" },
                { "title":"Book","data": "book.name" },
                { "title":"Date","data": "date" },
                { "title":"Status","data": "status" }
            ],
            "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    if(data==1) return "In Use";
                    else return "Returned"
                },
                "targets": 3
            },
            ]
        } );
        //initBooks();
});