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



    // $("#inworkSearch").on('click', function () {
    //     var value = $(this).attr("rel");
    //     console.log(value);
    //     $("#searchInput").attr({value: value});
    //     var arr = new Array(value);
    //     console.log(arr);
    //
    //
    // });




    // $("#pendingSearch").on('click', function () {
    //     var value = $(this).attr("rel");
    //     var test = $("#searchInput").val();
    //     console.log(test);
    //
    //     var arr = {
    //         search: {
    //             status: value, searchField: test,
    //         },
    //     };
    //     console.log(arr);
    //     // $("#searchInput").attr({value: value});
    //
    // });

    // $("#postponeSearch").on('click', function () {
        // var value = $(this).attr("rel");
        // var test = $("#searchInput").val();
        // var filters = {};
        // var data  = {};
        // data.query = test;
        // data.status = filters;
        // filters.status = value;
        // console.log(data);

        //     search: {
        //         status: value, searchField: test,
        //     },
        // };
        // $("#searchInput").attr({value: value});

    // });




    // $("#searchInput").on('keyup', function () {

    var filters = {};
    var data  = {};
    var val;
    var vol;
    var searchField;
    var statuses = [];
    var roles = [];
    // filters.status = val;
    data.status = filters;


    var wordsSearch =  $(".words-search").click(function () {

        if($(this).hasClass('active')){
            $(this).removeClass('active')
        } else {
            $(this).addClass('active')
        }

        data.query = "";
        val = $(this).attr("rel");
        vol = $(this).attr("rol");
        if (vol !== undefined){
            roles.push(vol);
        }
        if (val !== undefined){
            statuses.push(val);
        }
        data.status = statuses;
        data.role = roles;
        console.log(data);


    });



    $( "#searchButton" ).click(function() {

        // var test = $("#searchInput").val();

        console.log(data);

        var searchField = $("#searchInput").val();

        data.query = searchField;
        // if (searchField) {
            $.post("/ajax.php", {data: JSON.stringify(data), usp: $usp, it: $it, ajax: 'filter' },controlUpdate);
            function controlUpdate(data) {
                console.log(data);
                // location.reload();
            }
        // } else {
        //     $("#searchInput").addClass('border-danger');
        // }


    });



    // $( "#searchButton" ).click(function() {
    //
    //
    //     var value = $(".words-search").val();
    //     var test = $("#searchInput").val();
    //     var filters = {};
    //     var data  = {};
    //     data.query = test;
    //     data.status = filters;
    //     filters.status = value;
    //     console.log(data);




    //     var searchField = $("#searchInput").val();
    //
    //
    //     if (searchField) {
    //         $.post("/ajax.php", {module: 'search', searchField: searchField, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
    //         function controlUpdate(data) {
    //             location.reload();
    //         }
    //     } else {
    //         $("#searchInput").addClass('border-danger');
    //     }
    // });

});