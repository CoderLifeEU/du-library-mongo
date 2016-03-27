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
            "ajax": 'index.php?r=book/adminbooks',
            "columns": [
                { "title":"Reservation Number","data": "_id.$id" },
                { "title":"Book","data": "book.name" },
                { "title":"Date","data": "date" },
                { "title":"Status","data": "status" },
                { "title":"Actions","data": "status" }
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        if(data==1) return "In Use";
                        else return "Returned"
                    },
                    "targets": 3
                },
                {
                    "render": function ( data, type, row ) {
                        if(data==1) return "<div class='text-center'><a data-id='"+row._id.$id+"' class='btn btn-success btn-return'>&nbsp;&nbsp;Return&nbsp;&nbsp;</a></div>";
                        else return "<div class='text-center'><a class='btn btn-success' disabled='disabled'>Returned</a></div>"
                    },
                    "targets": 4
                },
            
            ]
        } );
        
        $('body').on( 'click', '.btn-return', function () {
        
            var btn = $(this);
            var id = btn.data("id");
            console.log(btn,id);
            
            $.ajax({
                url: "index.php?r=book/togglestatus",
                data:{id:id,status:0},
                dataType: "json",
                success: function(result){
                    btn.attr('disabled','disabled');
                    btn.text('Returned');       
            }});
        
    } );
    
        //initBooks();
});