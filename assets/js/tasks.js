$(document).ready(function () {

    $(".words-search").click(function () {
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
            $(this).addClass('active');
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

    $("#actualSearch").on('click', function () {
        resetSearch();
        countAll();
    });

    $('.role-search, .status-search').on('click', function () {
        $('.actual').hide();
        $('div.done').remove();
        $('div.canceled').remove();
        $(".archive").html('');
        $(".archive-search").removeClass('active');
        filterRole();
        filterTasks();
        countAll();
        pasteComma();
    });
    nameStatus();


    // function statuses() {
    //     var statusNames = [];
    //     $('.status-search').each(function () {
    //         if ($(this).hasClass('active')) {
    //             statusNames.push($(this).find('.status-name').text());
    //         }
    //         if (statusNames.length > 0) {
    //             $(statusNames).each(function () {
    //                 $(".selected-status").html("<span class=\"filter-select text-primary\">" + statusNames + "</span>" + ", ");
    //                 $("#resetSearch").show();
    //
    //             });
    //         } else {
    //             $(".selected-status").html("Актуальные");
    //             $("#resetSearch").show();
    //         }
    //     });
    //     console.log(statusNames);
    // }


    function nameStatus() {
        $('.status-search').on('click', function () {
            $("#resetSearch").show();
            var statusName = $(this).find('.status-name').text();
            if ($(this).hasClass('active')) {
                if (statusName === 'В работе') {
                    $(".inwork-status").html("<span class=\"filter-select text-primary\"><span>" + statusName + "</span></span>");
                }
                if (statusName === 'Просрочено') {
                    $(".overdue-status").html("<span class=\"filter-select  text-danger\"><span>" + statusName + "</span></span>");
                }
                if (statusName === 'Перенос срока') {
                    $(".postpone-status").html("<span class=\"filter-select  text-warning\"><span>" + statusName + "</span></span>");
                }
                if (statusName === 'На рассмотрении') {
                    $(".pending-status").html("<span class=\"filter-select  text-secondary\"><span>" + statusName + "</span></span>");
                }
            } else {
                if (statusName === 'На рассмотрении') {
                    $(".pending-status").html('');
                }
                if (statusName === 'Перенос срока') {
                    $(".postpone-status").html('');
                }
                if (statusName === 'Просрочено') {
                    $(".overdue-status").html('');
                }
                if (statusName === 'В работе') {
                    $(".inwork-status").html('');
                }
            }
            pasteComma();
        })
    }

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
                    countAll();
                    $(".load-done").show();
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
                    countAll();
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
            $('#actualSearch').removeClass('active');
            $(".archive").html($(this).find('.archive-name').text());
            $(".tasks").hide();
            loadDoneTasks();
            $("#resetSearch").show();
            $('.search-done').addClass('active');
            $('.actual').hide();
        } else {
            resetSearch();
            countAll();
        }
    });

    $(".search-cancel").on('click', function () {
        if ($(this).hasClass('active')) {
            resetSearch();
            $('#actualSearch').removeClass('active');
            $(".archive").html($(this).find('.archive-name').text());
            $(".tasks").hide();
            loadCanceledTasks();
            $("#resetSearch").show();
            $('.search-cancel').addClass('active');
            $('.actual').hide();
        } else {
            resetSearch();
            countAll();
        }
    });

    $('#searchInput').on('keyup', function () {
        filterTasks();
        countRoles();
        countStatuses();
        countAll();
    });

    var rolesNames = {};
    
    function pasteComma() {
        $('.comma').remove();
        $('.filter-select:visible:not(:empty):not(:last)').append('<span class="comma">, </span>')
    }

    function filterRole() {
        var rolename;
        $('.role-search').each(function (i) {
            var role = 'role' + i;
            if ($(this).hasClass('active')) {
                rolename = $(this).find('.role-name').text();
                rolesNames[role] = rolename;
            } else {
                delete rolesNames[role];
            }
        });
        if (Object.keys(rolesNames).length > 0) {
            $('.in').show();
            $('.out').show();
            $("#actualSearch").removeClass('active');
            $('.actual').hide();
            if ("role0" in rolesNames) {
                $(".in").html("<span>Входящие</span>");
                $("#resetSearch").show();
            } else {
                $('.in').html('');
            }
            if ("role1" in rolesNames) {
                $(".out").html("<span>Исходящие</span>");
                $("#resetSearch").show();
            } else {
                $('.out').html('');
            }
        } else {
            $('.in').hide();
            $('.out').hide();
            $("#resetSearch").show();
        }
    }

    function filterTasks() {
        $(".load-archive-page").hide();
        var text = $('#searchInput').val();
        var textRegex = new RegExp(text, 'i');
        var statuses = [];
        var statusesNames = [];
        var roles = [];
        $('.status-search').each(function () {
            if ($(this).hasClass('active')) {
                statuses.push($(this).attr('rel'));
            }
        });

        $('.role-search').each(function () {
            if ($(this).hasClass('active')) {
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
        });
        if (Object.keys(rolesNames).length === 0 && statuses.length === 0 && text === '') {
            resetSearch();
            countAll();
            $("#actualSearch").addClass('active');
        } else {
            $("#actualSearch").removeClass('active')
        }
    }

    function resetSearch() {
        doneTasksOffset = 0;
        canceledTasksOffset = 0;
        $('.status').html('');
        $('div.canceled').remove();
        $('div.done').remove();
        $(".archive").html('');
        $(".in").hide();
        $(".out").hide();
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
                $(".selected-status").html('');
            }
            $(".tasks").show();
        });
        $(".actual").show();
        $('#actualSearch').addClass('active');
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
            $('.task-box').addClass('d-none');
            $('.tasks-search-container').show();
        } else {
            $('.tasks-search-container').hide();
            $('.task-box').removeClass('d-none');
        }
        $('.count-all').html(' (' + count + ')');
    }

    countAll();
    countRoles();
    countStatuses();
});