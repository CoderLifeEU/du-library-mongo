$(document).ready(function() {
    console.log( "ready!" );
    
    var filter = '';
    var total = 0;
    var page=1;
     // init bootpag
     function initBootpag ()
     {
        $('#page-selection').bootpag({
            total: total
        }).on("page", function(event, /* page number here */ num){
            page=num;
            getBooks(num);
            refreshHandlers();
             //$("#content").html("Insert content"); // some ajax content loading...
        });
     }
        
        
        function getBooks(page)
        {
            console.log(page);
            console.log("Trying to get books");
            $.ajax({
                url: "index.php?r=book/get",
                data:{page:page},
                dataType: "json",
                success: function(result){
                    //console.log(result);
                    renderBooks(result);
                    //$("#div1").html(result);
            }});
        }
        
        function renderBooks(books)
        {
            console.log("BOOKS:");
            console.log(books.length);
            var source   = $("#entry-template").html();
            var template = Handlebars.compile(source);
            var context = {books: books};
            var html    = template(context);
            $("#content").html(html);
            refreshHandlers();
        }
        
        function initBooks()
        {
            $.ajax({
                url: "index.php?r=book/count",
                data:{filter:filter},
                dataType: "json",
                success: function(result){
                    //console.log(result);
                    if(result.status=="true")
                    {
                        total = result.data;
                        initBootpag();
                        getBooks(1);
                    }
            }});
        }
        
        
        function reserveBook(event)
        {
            event.preventDefault();
            var btn = $(this);
            var id = btn.data("id");
            console.log(btn,id);
            
            $.ajax({
                url: "index.php?r=book/reserve",
                data:{id:id},
                dataType: "json",
                success: function(result){
                    //console.log(result);
                    if(result.status==true)
                    {
                        console.log(result);
                        getBooks(page);
                    }
                    else
                    {
                        alert("Not possible to reserve book");
                    }
            }});
        }
        
        function checkBook()
        {
            var btn = $(this);
            console.log("Time to check");
            console.log(btn);
        }
        
        function refreshHandlers()
        {
            console.log("Time to refresh handlers");
            $('[data-toggle="tooltip"]').tooltip();
        }
        
        $('body').on('click','.btn-reserve',reserveBook);
        
        initBooks();
});