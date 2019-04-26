$(document).ready(function(){

    // $("#searchInput").on("keyup", function() {
    //     var value = $(this).val();
    //     $(".tasks").hide();
    //     $(".tasks:contains("+value+")").show();
    // });

    // $("#inworkSearch").click(function () {
    //     $(".tasks").fadeOut().filter(".inwork, .returned, .new, .overdue").fadeIn();
        // $(".cl").addClass("clicked");

        // event.defaultPrevented;
        // $(".tasks").show();

        // $(".clicked").click(function () {
        //     $(".clicked").removeClass("clicked");
        //     event.defaultPrevented;
        //     $(".tasks").show();
        // });

    // });

    // $("#postponeSearch").click(function () {
    //     $(".tasks").fadeOut().filter(".postpone").fadeIn();
    //     event.defaultPrevented;
    //     $(".tasks").show();
    //
    // });
    //
    // $("#pendingSearch").click(function () {
    //     $(".tasks").fadeOut().filter(".pending").fadeIn();
    //     event.defaultPrevented;
    //     $(".tasks").show();
    //
    // });
    //
    // $("#managerSearch").click(function () {
    //     $(".tasks").fadeOut().filter(".manager").fadeIn();
    //     event.defaultPrevented;
    //     $(".tasks").show();
    //
    // });
    //
    // $("#workerSearch").click(function () {
    //     $(".tasks").fadeOut().filter(".worker").fadeIn();
    //     event.defaultPrevented;
    //     $(".tasks").show();
    //
    // });


    var filters = {};
    var data  = {};
    var val;
    var vol;
    var searchField;
    var statuses = [];
    var roles = [];
    data.role = roles;
    // filters.status = val;
    data.status = statuses;


    var wordsSearch =  $(".words-search").click(function () {

        data.query = "";
        val = $(this).attr("rel");
        vol = $(this).attr("rol");

        if($(this).hasClass('active')){
            $(this).removeClass('active');
            if (vol !== undefined){
                roles.splice(vol);
            }
            if (val !== undefined){
                statuses.splice(val);
            }

        } else {
            $(this).addClass('active');
            if (vol !== undefined){
                roles.push(vol);
            }
            if (val !== undefined){
                statuses.push(val);
            }
        }

        console.log(data);


    });



    $( "#searchButton" ).click(function() {

        console.log(data);

        searchField = $("#searchInput").val();

        data.query = searchField;
        // if (searchField) {
            $.post("/ajax.php", {data: JSON.stringify(data), usp: $usp, it: $it, ajax: 'filter' },controlUpdate);
            function controlUpdate(data) {
                updateResults(JSON.parse(data));



                // location.reload();
            }
        // } else {
        //     $("#searchInput").addClass('border-danger');
        // }



    });

    function updateResults(data) {
        $(".tasks").hide();
        $(".tasks").append($('<div>', {

        }));

        console.log(data);


    }




});