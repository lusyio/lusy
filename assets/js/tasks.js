var archiveTasksOffset = 0;

function loadArchiveTasks(status, orderField, order, workerId, taskType) {
    var fd = new FormData();
    fd.append('ajax', 'tasks');
    fd.append('module', 'loadArchiveTasks');
    if (status === 'done') {
        fd.append('status', 'done');
    } else if (status === 'canceled') {
        fd.append('status', 'canceled');
    }
    if (taskType === 'in') {
        fd.append('taskType', 'in');
    } else if (taskType === 'out') {
        fd.append('taskType', 'out');
    }
    fd.append('offset', archiveTasksOffset.toString());
    fd.append('orderField', orderField);
    fd.append('order', order);
    fd.append('workerId', workerId);
    $.ajax({
        url: '/ajax.php',
        type: 'POST',

        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        data: fd,
        success: function (response) {
            if (response) {
                if (response['tasks'] !== '') {
                    $('#taskBox').append(response['tasks']);
                }
                if (response['hasNextPage']) {
                    $(".load-done").show();
                } else {
                    $(".load-done").hide();
                }
            } else {
                $(".load-archive-page").hide()
            }
        },
    });
}

function filterTaskByStatusWorkerAndType() {
    var status = 0;
    if ($('.status-dropdown-item.active').length) {
        status = $('.status-dropdown-item.active').data('status');
    }
    var workerId = 0;
    if ($('.worker-dropdown-item.active').length) {
        workerId = $('.worker-dropdown-item.active').data('worker-id');
    }
    var type = 0;
    if ($('.task-type-dropdown-item.active').length) { // здесь условие активного элемента фильтра
        type = $('.task-type-dropdown-item.active').data('task-type'); // здесь получаем значение - оно дложно быть из списка: 'in', 'out', 'self', 'another'
    }
    var taskFilter = '.task-card';
    var subTaskFilter = '.sub-task-card';
    $('.task-card, .sub-task-card').hide();
    if (status) {
        taskFilter = taskFilter + '[data-status=' + status + ']';
        subTaskFilter = subTaskFilter + '[data-status=' + status + ']';
    }
    if (workerId) {
        taskFilter = taskFilter + '[data-worker-id=' + workerId + ']';
        subTaskFilter = subTaskFilter + '[data-worker-id=' + workerId + ']';
    }
    if (type) {
        taskFilter = taskFilter + '[data-task-type=' + type + ']';
        subTaskFilter = subTaskFilter + '[data-task-type=' + type + ']';
        if (type === 'out') {
            taskFilter = taskFilter + ',' + taskFilter + '[data-task-type=self]';
            subTaskFilter = subTaskFilter + ',' + subTaskFilter + '[data-task-type=self]';
        }
    }
    $(taskFilter).show();
    $(subTaskFilter).show();
    $(subTaskFilter).parents('.task-card').show();
}

function orderByName() {
    if ($('#nameOrder').hasClass('asc')) {
        var ascOrder = true
    } else if ($('#nameOrder').hasClass('desc')) {
        var ascOrder = false
    }
    orderSubTaskByName(ascOrder);

    var elements = $('.task-card');
    var target = $('#taskBox');

    elements.sort(function (a, b) {
        var an = $(a).find('.taskname').text(),
            bn = $(b).find('.taskname').text();

        if (an && bn) {
            if (ascOrder) {
                return an.toUpperCase().localeCompare(bn.toUpperCase());
            } else {
                return bn.toUpperCase().localeCompare(an.toUpperCase());
            }
        }
        return 0;
    });
    elements.detach().appendTo(target);
}

function orderSubTaskByName(ascOrder) {

    $('.subTaskInList').each(function () {
        var elements = $(this).find('.sub-task-card');
        var target = $(this);

        elements.sort(function (a, b) {
            var an = $(a).find('.taskname').text(),
                bn = $(b).find('.taskname').text();

            if (an && bn) {
                if (ascOrder) {
                    return an.toUpperCase().localeCompare(bn.toUpperCase());
                } else {
                    return bn.toUpperCase().localeCompare(an.toUpperCase());
                }
            }
            return 0;
        });
        elements.detach().appendTo(target);
    })
}

function orderByDate() {
    if ($('#dateOrder').hasClass('asc')) {
        var ascOrder = true
    } else if ($('#dateOrder').hasClass('desc')) {
        var ascOrder = false
    }
    orderSubTasksByDate(ascOrder);

    var elements = $('.task-card');
    var target = $('#taskBox');

    elements.sort(function (a, b) {
        var an = $(a).data('deadline'),
            bn = $(b).data('deadline');
        if ($(a).find('.sub-task-card').length) {
            var aFirstSubTask = $(a).find('.sub-task-card')[0];
            if (ascOrder && $(aFirstSubTask).data('deadline') < an) {
                an = $(aFirstSubTask).data('deadline');
            } else if (!ascOrder && $(aFirstSubTask).data('deadline') > an) {
                an = $(aFirstSubTask).data('deadline');
            }
        }
        if ($(b).find('.sub-task-card').length) {
            var bFirstSubTask = $(b).find('.sub-task-card')[0];
            if (ascOrder && $(bFirstSubTask).data('deadline') < bn) {
                bn = $(bFirstSubTask).data('deadline');
            } else if (!ascOrder && $(bFirstSubTask).data('deadline') > bn) {
                bn = $(bFirstSubTask).data('deadline');
            }
        }

        if (an && bn) {
            if (ascOrder) {
                return an.toUpperCase().localeCompare(bn.toUpperCase());
            } else {
                return bn.toUpperCase().localeCompare(an.toUpperCase());
            }
        }
        return 0;
    });
    elements.detach().appendTo(target);
}

function orderSubTasksByDate(ascOrder) {
    $('.subTaskInList').each(function () {
        var elements = $(this).find('.sub-task-card');
        var target = $(this);

        elements.sort(function (a, b) {
            var an = $(a).data('deadline'),
                bn = $(b).data('deadline');
            if (an && bn) {
                if (ascOrder) {
                    return an.toUpperCase().localeCompare(bn.toUpperCase());
                } else {
                    return bn.toUpperCase().localeCompare(an.toUpperCase());
                }
            }
            return 0;
        });
        elements.detach().appendTo(target);
    })
}

function filterTasks() {
    $(".load-archive-page").hide();
    var text = $('#searchInput').val();
    var textRegex = new RegExp(text, 'i');
    $('.tasks').each(function () {
        var $el = $(this);
        var $hasText = false;
        if ($el.find('span').text().search(textRegex) !== -1) {
            $hasText = true;
        }
        if ($hasText) {
            $el.show()
        } else {
            $el.hide();
        }
    });
}

function checkForEmptyTaskList() {
    if ($('.task-card:visible').length > 0) {
        $('#emptyTasksFilter').addClass('d-none');
    } else {
        $('#emptyTasksFilter').removeClass('d-none');
    }
}

$(document).ready(function () {
    $('.worker-dropdown-item').on('click', function (e) {
        e.preventDefault();
        var workerId = $(this).data('worker-id');
        if (workerId == 0 || $(this).hasClass('active')) {
            $('#workerDropdownButton').text($('#workerDropdownButton').data('default-name'));
            $('#workerDropdownButton').removeClass('active');
            $('.worker-dropdown-item').removeClass('active');
        } else {
            $('.worker-dropdown-item').removeClass('active');
            $(this).addClass('active');
            var workerName = $(this).text();
            $('#workerDropdownButton').text(workerName);
            $('#workerDropdownButton').addClass('active');

        }
        filterTaskByStatusWorkerAndType();
        checkForEmptyTaskList();
    });

    $('.status-dropdown-item').on('click', function (e) {
        e.preventDefault();

        $('.task-card[data-status="done"], .sub-task-card[data-status="done"]').remove();
        $('.task-card[data-status="canceled"], .sub-task-card[data-status="canceled"]').remove();

        var status = $(this).data('status');
        if (status == 0 || $(this).hasClass('active')) {
            $('#statusDropdownButton').text($('#statusDropdownButton').data('default-name'));
            $('#statusDropdownButton').removeClass('active');
            $('.status-dropdown-item').removeClass('active');
            if (status == 0) {
                $('.task-type-dropdown-item').removeClass('active');
            }
        } else {
            $('.status-dropdown-item').removeClass('active');
            $(this).addClass('active');
            var statusName = $(this).text();
            $('#statusDropdownButton').text(statusName);
            $('#statusDropdownButton').addClass('active');
        }

        filterTaskByStatusWorkerAndType();
        if ($('#dateOrder').hasClass('asc') || $('#dateOrder').hasClass('desc')) {
            orderByDate();
        } else if ($('#nameOrder').hasClass('asc') || $('#nameOrder').hasClass('desc')) {
            orderByName();
        }

        if ((status === 'done' || status === 'canceled') && $(this).hasClass('active')) {
            archiveTasksOffset = 0;
            var order = '';
            var orderField = '';
            if ($('#dateOrder').hasClass('asc')) {
                order = 'asc';
                orderField = 'date';
            } else if ($('#dateOrder').hasClass('desc')) {
                order = 'desc';
                orderField = 'date';
            } else if ($('#nameOrder').hasClass('asc')) {
                order = 'asc';
                orderField = 'name';
            } else if ($('#nameOrder').hasClass('desc')) {
                order = 'desc';
                orderField = 'name';
            }
            var workerId = 0;
            if ($('.worker-dropdown-item.active').length) {
                workerId = $('.worker-dropdown-item.active').data('worker-id');
            }
            var taskType = 0;
            if ($('#taskIn').hasClass('active')) {
                taskType = 'in';
            } else if ($('#taskOut').hasClass('active')) {
                taskType = 'out';
            }
            if (status === 'done' || status === 'canceled') {
                $(".task-card").hide();
                loadArchiveTasks(status, orderField, order, workerId, taskType);
            }
        } else {
            checkForEmptyTaskList();
        }
    });

    $('.task-type-dropdown-item').on('click', function (e) {
        e.preventDefault();
        var taskType = $(this).data('task-type');
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            if ($('.status-dropdown-item.active').length == 0) {
                $('#statusDropdownButton').removeClass('active');
            }
        } else {
            $('.task-type-dropdown-item').removeClass('active');
            $(this).addClass('active');
        }
        filterTaskByStatusWorkerAndType();
        checkForEmptyTaskList();
    });

    $('#nameOrder').on('click', function (e) {
        e.preventDefault();
        $('#dateOrder').removeClass('asc').removeClass('desc');
        $('#dateOrderIcon').removeClass('fas fa-sort-down').removeClass('fas fa-sort-up').addClass('fas fa-sort');

        if ($(this).hasClass('asc')) {
            $(this).removeClass('asc').addClass('desc');
            $('#nameOrderIcon').removeClass('fas fa-sort-up').addClass('fas fa-sort-down')
        } else {
            $(this).removeClass('desc').addClass('asc');
            $('#nameOrderIcon').removeClass('fas fa-sort-down').addClass('fas fa-sort-up')
        }
        if ($('#doneLink').hasClass('active')) {
            $('#doneLink').removeClass('active');
            $('#doneLink').trigger('click');
        } else if ($('#canceledLink').hasClass('active')) {
            $('#canceledLink').removeClass('active');
            $('#canceledLink').trigger('click');
        } else {
            orderByName();
        }

    });

    $('#dateOrder').on('click', function (e) {
        e.preventDefault();
        $('#nameOrder').removeClass('asc').removeClass('desc');
        $('#nameOrderIcon').removeClass('fas fa-sort-down').removeClass('fas fa-sort-up').addClass('fas fa-sort');

        if ($(this).hasClass('asc')) {
            $(this).removeClass('asc').addClass('desc');
            $('#dateOrderIcon').removeClass('fas fa-sort-up').addClass('fas fa-sort-down')

        } else {
            $(this).removeClass('desc').addClass('asc');
            $('#dateOrderIcon').removeClass('fas fa-sort-down').addClass('fas fa-sort-up')
        }
        if ($('#doneLink').hasClass('active')) {
            $('#doneLink').removeClass('active');
            $('#doneLink').trigger('click');
        } else if ($('#canceledLink').hasClass('active')) {
            $('#canceledLink').removeClass('active');
            $('#canceledLink').trigger('click');
        } else {
            orderByDate();
        }
    });

    var action = window.location.hash.substr(1);
    if (action === 'overdue') {
        $('#overdueLink').trigger('click');
        location.hash = '';
    }
    if (action === 'inwork') {
        $('#inworkLink').trigger('click');
        location.hash = '';
    }
    if (action === 'pending') {
        $('#pendingLink').trigger('click');
        location.hash = '';
    }
    if (action === 'postpone') {
        $('#postponeLink').trigger('click');
        location.hash = '';
    }
    if (action === 'planned') {
        $('#plannedLink').trigger('click');
        location.hash = '';
    }

    $('#searchInput').on('keyup', function () {
        filterTasks();
    });

    $('#clearAllFilters').on('click', function (e) {
        e.preventDefault();
        $('#workerDropdownButton').text($('#workerDropdownButton').data('default-name'));
        $('#workerDropdownButton').removeClass('active');
        $('.worker-dropdown-item').removeClass('active');

        $('#statusDropdownButton').removeClass('active');
        $('#statusDropdownButton').text($('#statusDropdownButton').data('default-name'));
        $('.status-dropdown-item').removeClass('active');
        $('.task-type-dropdown-item').removeClass('active');
        filterTaskByStatusWorkerAndType();
        if ($('#dateOrder').hasClass('asc') || $('#dateOrder').hasClass('desc')) {
            orderByDate();
        } else if ($('#nameOrder').hasClass('asc') || $('#nameOrder').hasClass('desc')) {
            orderByName();
        }
        checkForEmptyTaskList();
    });
});
