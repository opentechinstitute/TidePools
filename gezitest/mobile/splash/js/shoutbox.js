
$(function() {
    
    //populating shoutbox the first time
    refresh_shoutbox();
    // recurring refresh every 15 seconds
    setInterval("refresh_shoutbox()", 1500);

    $("#submit").click(function() {
        // getting the values that user typed
        var name    = $("#name").val();
        var message = $("#message").val();
        // forming the queryString
        var data            = 'name='+ name +'&message='+ message;

        // ajax call
        $.ajax({
            type: "POST",
            url: "shout.php",
            data: data,
            success: function(html){ // this happen after we get result
                $("#shout").slideToggle(1, function(){
                    $(this).html(html).slideToggle(1);
                    $("#message").val("");
                });
          }
        });    
        return false;
    });
});

function refresh_shoutbox() {
    var data = 'refresh=1';
    
    $.ajax({
            type: "POST",
            url: "shout.php",
            data: data,
            success: function(html){ // this happen after we get result
            
                console.log(html);
                
                $("#announce").html(html);
                
            }
        });
}

