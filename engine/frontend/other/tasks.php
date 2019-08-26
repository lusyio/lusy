<script type="text/javascript" src="/assets/js/tasks.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<?php include __ROOT__ . '/engine/frontend/other/searchbar.php' ?>
<div id="taskBox">
    <a href="/task/new/" class="text-decoration-none cust add-newtask-button">
        <div class="card mb-2 add-newtask-tasks">
            <div class="card-body taskbox-padding">
                <div class="row">
                    <div class="col text-center">
                        <span class="mr-2">
                            <small class="text-muted-reg">Добавить новую задачу</small>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <div class="d-none d-sm task-box taskbox-padding">
        <div class="taskbox-padding-left">
            <div class="row sort">
                <div class="col-sm-6">
                    <span id="nameOrder" class="btn btn-secondary sort">
                        <span id="nameOrderText"><?= $GLOBALS['_taskname'] ?> <i id="nameOrderIcon" class="fas fa-sort"></i></span>
                    </span>
                </div>
                <div class="col-sm-2 dropdown status-dropdown d-flex justify-content-center">
                    <button id="statusDropdownButton" class="btn btn-sm btn-secondary dropdown-toggle sort" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-default-name="<?= $GLOBALS['_statustasks'] ?>">
                        <?= $GLOBALS['_statustasks'] ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="statusDropdownButton">
                        <a class="dropdown-item status-dropdown-item<?= (in_array('new', $statuses)) ? '' : ' disabled' ?>" href="#" id="newLink" data-status="new"><?= $GLOBALS["_newfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('inwork', $statuses)) ? '' : ' disabled' ?>" href="#" id="inworkLink" data-status="inwork"><?= $GLOBALS["_inworkfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('returned', $statuses)) ? '' : ' disabled' ?>" href="#" data-status="returned"><?= $GLOBALS["_returnedfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('overdue', $statuses)) ? '' : ' disabled' ?>" href="#"  id="overdueLink" data-status="overdue"><?= $GLOBALS["_overduefilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('postpone', $statuses)) ? '' : ' disabled' ?>" href="#" id="postponeLink" data-status="postpone"><?= $GLOBALS["_postponefilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('pending', $statuses)) ? '' : ' disabled' ?>" href="#" id="pendingLink" data-status="pending"><?= $GLOBALS["_pendingfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('planned', $statuses)) ? '' : ' disabled' ?>" href="#" data-status="planned"><?= $GLOBALS["_plannedfilter"] ?></a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item status-dropdown-item" href="#" data-status="0">Очистить фильтр</a>

                    </div>
                </div>
                <div class="col-sm-2">
                    <span id="dateOrder" class="btn btn-secondary sort">
                        <span id="dateOrderText"><?= $GLOBALS['_deadlinetasks'] ?> <i id="dateOrderIcon" class="fas fa-sort"></i></span>
                    </span>
                </div>
                <div class="col-sm-2 dropdown worker-dropdown">
                        <button id="workerDropdownButton" class="btn btn-sm btn-secondary dropdown-toggle sort" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-default-name="<?= $GLOBALS['_memberstasks'] ?>">
                            <?= $GLOBALS['_memberstasks'] ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="workerDropdownButton">
                            <?php foreach ($workersName as $wId => $wName): ?>
                                <a class="dropdown-item worker-dropdown-item" href="#" data-worker-id="<?= $wId ?>"><?= $wName ?></a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item worker-dropdown-item" href="#" data-worker-id="0">Очистить фильтр</a>

                        </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $borderColor = [
        'new' => 'border-primary',
        'inwork' => 'border-primary',
        'overdue' => 'border-danger',
        'postpone' => 'border-warning',
        'pending' => 'border-warning',
        'returned' => 'border-primary',
        'done' => 'border-success',
        'canceled' => 'border-secondary',
        'planned' => 'border-info',
    ];
    $textColor = [
        'new' => 'text-primary',
        'inwork' => 'text-primary',
        'overdue' => 'text-danger',
        'postpone' => 'text-warning',
        'pending' => 'text-warning',
        'returned' => 'text-primary',
        'done' => 'text-success',
        'canceled' => 'text-secondary',
        'planned' => 'text-info',
    ];
    $taskStatusText = [
        'manager' => [
            'new' => $GLOBALS['_tasknewmanager'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
            'planned' => $GLOBALS['_plannedlist'],
        ],
        'ceo' => [
            'new' => $GLOBALS['_tasknewmanager'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
            'planned' => $GLOBALS['_plannedlist'],
        ],
        'worker' => [
            'new' => $GLOBALS['_tasknewworker'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
            'planned' => $GLOBALS['_plannedlist'],
        ],
    ]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
    $orderPosition = 1;
    foreach ($tasks as $task) {
        $status = $task->get('status');
        $taskId = $task->get('id');
        $mainRole = $task->get('mainRole');
        $subTasks = $task->get('subTasks');
        $name = $task->get('name');
        $countComments = $task->get('countComments');
        $countNewComments = $task->get('countNewComments');
        $countAttachedFiles = $task->get('countAttachedFiles');
        $countNewFiles = $task->get('countNewFiles');
        $datedone = $task->get('datedone');
        $manager = $task->get('manager');
        $worker = $task->get('worker');
        $viewStatus = $task->get('viewStatus');
        include __ROOT__ . '/engine/frontend/other/task-card.php';
    }
    ?>
</div>
<script>
    $(document).ready(function () {

        $(".progress-bar ").each(function () {
            var danger = $(this).attr('aria-valuenow');
            var danger1 = Number.parseInt(danger);
            if (danger1 >= 95) {
                $(this).next("medium").addClass('progress-danger');
            }
            if ($(this).parents("div").hasClass('done')) {
                $(this).next("medium").html('<i class="fas fa-check p-1"></i>' + '<?=$GLOBALS["_donelist"]?>').addClass('progress-done p-2');
            }
        });

        $('#tasks').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "order": [[3, "asc"]]
        });

        $('.worker-dropdown-item').on('click', function (e) {
            e.preventDefault();
            var workerId = $(this).data('worker-id');
            $('.worker-dropdown-item').removeClass('active');
            if (workerId == 0) {
                $('#workerDropdownButton').text($('#workerDropdownButton').data('default-name'));
                $('#workerDropdownButton').removeClass('active');
            } else {
                $(this).addClass('active');
                var workerName = $(this).text();
                $('#workerDropdownButton').text(workerName);
                $('#workerDropdownButton').addClass('active');

            }
            filterTaskByStatusAndWorker();
        });

        $('.status-dropdown-item').on('click', function (e) {
            e.preventDefault();
            var status = $(this).data('status');
            $('.status-dropdown-item').removeClass('active');
            if (status == 0) {
                $('#statusDropdownButton').text($('#statusDropdownButton').data('default-name'));
                $('#statusDropdownButton').removeClass('active');
            } else {
                $(this).addClass('active');
                var statusName = $(this).text();
                $('#statusDropdownButton').text(statusName);
                $('#statusDropdownButton').addClass('active');
            }
            filterTaskByStatusAndWorker();
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
            orderByName();
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
            orderByDate();
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

        function filterTaskByStatusAndWorker() {
            var status = 0;
            if ($('.status-dropdown-item.active').length) {
                status = $('.status-dropdown-item.active').data('status');
            }
            var workerId = 0;
            if ($('.worker-dropdown-item.active').length) {
                workerId = $('.worker-dropdown-item.active').data('worker-id');
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
            console.log(status);
            console.log(workerId);
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

    });
</script>
