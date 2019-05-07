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

        // data.query = "";
        val = $(this).attr("rel");
        vol = $(this).attr("rol");

        if($(this).hasClass('active')){
            $(this).removeClass('active');
            if ($(this).hasClass('active-manager')){
                $(this).removeClass('active-manager');
            }
            if ($(this).hasClass('active-worker')){
                $(this).removeClass('active-worker');
            }



        } else {
            $(this).addClass('active');
            if (vol === 'manager'){
                $(this).addClass('active-manager');
            }
            if (vol === 'worker'){
                $(this).addClass('active-worker');
            }

        }



    });



    // $( "#searchButton" ).click(function() {
    //
    //
    //     searchField = $("#searchInput").val();
    //
    //     data.query = searchField;
    //     // if (searchField) {
    //         $.post("/ajax.php", {data: JSON.stringify(data), usp: $usp, it: $it, ajax: 'filter' },controlUpdate);
    //         function controlUpdate(data) {
    //             updateResults(JSON.parse(data));
    //
    //
    //
    //             // location.reload();
    //         // }
    //     // } else {
    //     //     $("#searchInput").addClass('border-danger');
    //     // }
    //
    //
    //
    // });

    function updateResults(data) {

        $('div #taskBox').empty();
        data.forEach(function (item) {
            var $task = '<div class="col-md-12 p-0"  id="taskstop">' +
                '<div class="card mb-2 tasks' + item.status + '">' +
                '<div class="card-body tasks-list" onclick="window.location="' + item.idtask + '">' +
                '<div class="d-block mb-1 border-left-tasks <?= $borderColor[$status] ?>">' +
                '<a><h6 class="card-title mb-2"><span>' + item.name + '</span></h6></a>' +
            '<img src="/upload/avatar/2.jpg" class="avatar mr-1">' +
                '<a href="/profile/' + item.idmanager + '/">' + item.namem + ' ' + item.surnamem + '</a>' +
            '</div>' +
            '<div class="d-inline-block">' +
                '<img src="/upload/avatar/1.jpg" class="avatar mr-1">' +
                '<a class="name-manager-tasks" href="/profile/' + item.idworker + '/">' + item.namew + ' ' + item.surnamew + '</a>' +
            '</div>' +
            '<div class="d-inline-block">' +
                '<span class="icons-tasks"><i class="fas fa-comments custom-date"></i> </span>' +
            '<span class="icons-tasks"><i class="fas fa-file custom-date"></i> </span>' +
            '</div>' +
            '<div class="position-absolute date-status">' +
                '<span class="text-ligther"><i class="far fa-calendar-times custom"></i>' + item.datedone + '</span>' +
                '</div></div></div></div>';
           $('#taskBox').append($task);
        });


    }

    $('.role-search, .status-search').on('click', function () {
        filterTasks();
    });

    $('#searchInput').on('keyup' , function () {
        filterTasks();
        countRoles();
        countStatuses();
    });

    function filterTasks() {
        var text = $('#searchInput').val();
        var textRegex = new RegExp(text, 'i');
        var statuses = [];
        var roles = [];
        $('.status-search').each(function () {
            if($(this).hasClass('active')) {
                statuses.push($(this).attr('rel'));
            }
        });
        $('.role-search').each(function () {
            if($(this).hasClass('active')) {
                roles.push($(this).attr('rol'));
            }
        });
        $('.tasks').each(function () {
            var $el = $(this);
            var $hasStatus = false;
            var $hasText = false;
            var $hasRole = false;
            if ($el.find('span').text().search(textRegex) !== -1) {
                $hasText = true;
            }
            if (statuses.length === 0) {
                $hasStatus = true
            } else {
                statuses.forEach(function ($status) {
                    if ($el.hasClass($status)) {
                        $hasStatus = true;
                    }
                })
            }
            if (roles.length === 0) {
                $hasRole = true
            } else {
                roles.forEach(function ($role) {
                    if ($el.hasClass($role)) {
                        $hasRole = true;
                    }
                })
            }
            if ($hasStatus && $hasText && $hasRole) {
                $el.show()
            } else {
                $el.hide();
            }
        })
    }

    function resetSearch(){
        $(".words-search").each(function () {
            var status = $(".words-search");
            if (status.hasClass('active')) {
                $(".words-search").removeClass('active');
                $("#searchInput").val('');
                $(".tasks").show();
            }
        })
    }


    $(".progress-bar ").each(function () {
        var danger = $(this).attr('aria-valuenow');
        var danger1 = Number.parseInt(danger);
        if (danger1 >= 95) {
            $(this).next("medium").addClass('progress-danger');
        }
        if ($(this).parents("div").hasClass('done')){
            $(this).next("medium").addClass('progress-done')
        }
    });



    $("#resetSearch").on('click', function () {
        resetSearch();
    });
    
    function countStatuses() {
        $('.status-search').each(function () {
            var $status = $(this).attr('rel');
            var taskCount = $('.' + $status + ':visible').length;
            $(this).find('.count').text(' (' + taskCount + ')');
        })
    }
    function countRoles() {
        $('.role-search').each(function () {
            var $status = $(this).attr('rol');
            var roleCount = $('.' + $status + ':visible').length;
            $(this).find('.count').text(' (' + roleCount + ')');
        })
    }
    countRoles();
    countStatuses();
});