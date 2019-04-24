$(document).ready(function(){

    $("#searchInput").on("keyup", function() {
        var value = $(this).val();
        $(".tasks").hide();
        $(".tasks:contains("+value+")").show();
        console.log(value);
    });

});