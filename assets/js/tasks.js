$(document).ready(function () {

    $(".words-search").click(function () {
        // data.query = "";
        var val = $(this).attr("rel");
        var vol = $(this).attr("rol");

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            if ($(this).hasClass('active-manager')) {
                $(this).removeClass('active-manager');
            }
            if ($(this).hasClass('active-worker')) {
                $(this).removeClass('active-worker');
            }

        } else {
            // if ($(".words-search").hasClass('active-manager') || $(".words-search").hasClass('active-worker')){
            //     if ($(".active-other").hasClass('active')){
            //         $(".active-other").removeClass('active');
            //         $(this).addClass('active');
            //     }
            // } else{
            //     $('.words-search').removeClass('active');
            // }
            $(this).addClass('active');
            if (vol === 'manager') {
                $(this).addClass('active-manager');
            }
            if (vol === 'worker') {
                $(this).addClass('active-worker');
            }
        }
    });

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
        countAll();
        $(".archive-search").removeClass('active');
        // if ($(this).hasClass('active') && $(this).hasClass("status-search")){
        //     $(".selected-status").html( " " + $(this).children("span").text());
        //     $("#resetSearch").show();
        // } else {
        //     $(".selected-status").html("");
        // }
        // if ($(this).hasClass('active') && $(this).hasClass("role-search")){
        //     $(".selected-role").html($(this).children("span").text());
        // }
    });
    // nameStatus();
    //
    // function nameStatus() {
    //     $('.status-search').on('click', function () {
    //         $("#resetSearch").show();
    //         if ($(this).hasClass('active')) {
    //             var statusName = $(this).find('.status-name').text();
    //             console.log(statusName);
    //             if (statusName === 'В работе') {
    //                 $(".inwork-status").html(statusName);
    //             }
    //             if (statusName === 'Просрочено') {
    //                 $(".overdue-status").html(statusName);
    //             }
    //             if (statusName === 'Перенос срока') {
    //                 $(".postpone-status").html(statusName);
    //             }
    //             if (statusName === 'На рассмотрении') {
    //                 $(".pending-status").html(statusName);
    //             }
    //             }else{
    //             console.log('ddddd');
    //         }
    //     })
    // }

    var doneTasksOffset = 0;

    function loadDoneTasks() {
        var fd = new FormData();
        fd.append('ajax', 'tasks');
        fd.append('module', 'loadDoneTasks');
        fd.append('offset', doneTasksOffset.toString());
        $.ajax({
            url: '/ajax.php',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: fd,
            success: function (data) {
                if (data) {
                    $('#taskBox').append(data);
                    $(".load-done").show()
                } else {
                    $(".load-archive-page").hide()
                }
            },
        });
    }

    $("#workzone").on('click', '#loadArchiveDone', function () {
        doneTasksOffset++;
        loadDoneTasks();
    });

    var canceledTasksOffset = 0;

    function loadCanceledTasks() {
        var fd = new FormData();
        fd.append('ajax', 'tasks');
        fd.append('module', 'loadCanceledTasks');
        fd.append('offset', canceledTasksOffset.toString());
        $.ajax({
            url: '/ajax.php',
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            data: fd,
            success: function (data) {
                if (data) {
                    $('#taskBox').append(data);
                    $(".load-canceled").show()
                } else {
                    $(".load-archive-page").hide()
                }
            },
        });
    }

    $("#workzone").on('click', '#loadArchiveCanceled', function () {
        canceledTasksOffset++;
        loadCanceledTasks();
    });

    $(".search-done").on('click', function () {
        if ($(this).hasClass('active')) {
            resetSearch();
            $(".selected-role").html($(this).find('.archive-name').text());
            $(".tasks").hide();
            loadDoneTasks();
            $("#resetSearch").show();
            $('.search-done').addClass('active');
        } else {
            $('div.done').remove();
            $(".load-archive-page").hide()
        }
    });

    $(".search-cancel").on('click', function () {
        if ($(this).hasClass('active')) {
            resetSearch();
            $(".selected-role").html($(this).find('.archive-name').text());
            $(".tasks").hide();
            loadCanceledTasks();
            $("#resetSearch").show();
            $('.search-cancel').addClass('active');
        } else {
            $('div.canceled').remove();
            $(".load-archive-page").hide()
        }
    });

    $('#searchInput').on('keyup', function () {
        filterTasks();
        countRoles();
        countStatuses();
        countAll();
    });


    function filterTasks() {
        $(".load-archive-page").hide();
        var text = $('#searchInput').val();
        var textRegex = new RegExp(text, 'i');
        var statuses = [];
        var statusesNames = [];
        var rolesNames = [];
        var roles = [];

        $('.status-search').each(function () {
            if ($(this).hasClass('active')) {
                statuses.push($(this).attr('rel'));
                statusesNames.push($(this).find('.status-name').text());
            }
        });

        if (statusesNames.length > 0) {
            $(statusesNames).each(function () {
                $(".selected-status").html(statusesNames + " ");
                $("#resetSearch").show();
            });
        } else {
            $(".selected-status").html("");
            $("#resetSearch").hide();
        }
        $('.role-search').each(function () {
            if ($(this).hasClass('active')) {
                roles.push($(this).attr('rol'));
                rolesNames.push($(this).find('.role-name').text());
            }
        });

        if (rolesNames.length > 0) {
            $(rolesNames).each(function () {
                $(".selected-role").html(rolesNames + " ");
                $("#resetSearch").show();
            });
        } else {
            $(".selected-role").html("Актуальные");
            $("#resetSearch").show();
        }

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

    function resetSearch() {
        $(".status-block").each(function () {
            $('.status').html('');
        });
        $(".words-search").each(function () {
            var status = $(this);
            $(".load-archive-page").hide();
            $("#searchInput").val('');
            $("#resetSearch").hide();
            countRoles();
            countStatuses();
            if (status.hasClass('active')) {
                $(".words-search").removeClass('active');
                $('.archive-search').removeClass('active');
                console.log($('.tasks:visible').length);
                $(".selected-role").html("Актуальные");
                $(".selected-status").html('');
                $('div.canceled').remove();
                $('div.done').remove();
            }
            $(".tasks").show();
        })
    }

    $("#resetSearch").on('click', function () {
        resetSearch();
        countAll();
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

    function countAll() {
        $(".load-archive-page").hide();
        var count = $('.tasks' + ':visible').length;
        if (count === 0) {
            $('.tasks-search-container').show();
        } else {
            $('.tasks-search-container').hide();
        }
        $('.count-all').html(' (' + count + ')');
    }

    countAll();
    countRoles();
    countStatuses();
});