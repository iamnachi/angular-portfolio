function prevmonth(current) {
    var now = new Date(current);
    var past = new Date(now.setMonth(now.getMonth() - 1, 1));
    //var pastDate = new Date(past);
   
    var pastMonth =  ("0" + (past.getMonth() + 1)).slice(-2);
    var pastYear =  past.getFullYear();
    var find = $('input[name="hidden-tags"]').val();
    console.log(pastMonth+" - "+pastYear);
    $.get("search.php", { month: pastMonth, year: pastYear, search: find },
    function(data, status){
        if(status == "success") {
            //alert("Data: " + data + "\nStatus: " + status);
            $("#attendance").html(data);
        }
        
    });
}

function nextmonth(current) {
    var now = new Date(current);
    var future = new Date(now.setMonth(now.getMonth() + 1, 1));
    //console.log(future);
    var futureMonth = ("0" + (future.getMonth() + 1)).slice(-2);
    var futureYear = future.getFullYear();
     var find = $('input[name="hidden-tags"]').val();
    console.log(futureMonth+" - "+futureYear);
    $.get("search.php", { month: futureMonth, year: futureYear, search: find },
    function(data, status){
        if(status == "success") {
            //alert("Data: " + data + "\nStatus: " + status);
            $("#attendance").html(data);
        }
    });
 }

 