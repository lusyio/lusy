$(document).ready(function(){

    $("#searchInput").on("keyup", function() {
        var value = $(this).val();
        $(".tasks").hide();
        $(".tasks:contains("+value+")").show();
    });

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


    $("#inworkSearch").on('click', function () {
        var value = $(this).attr("rel");
        console.log(value);
        $("#searchInput").attr({value: value});

    });

    $("#pendingSearch").on('click', function () {
        var value = $(this).attr("rel");
        console.log(value);
        $("#searchInput").attr({value: value});

    });

    $( "#searchButton" ).click(function() {
        var searchField = $("#searchInput").val();
        if (searchField) {
            $.post("/ajax.php", {module: 'search', searchField: searchField, usp: $usp, it: $it, ajax: 'task-control' },controlUpdate);
            function controlUpdate(data) {
                location.reload();
            }
        } else {
            $("#searchInput").addClass('border-danger');
        }
    });

});