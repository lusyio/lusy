<script type="text/javascript" src="/assets/js/tasks.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<?php include 'engine/frontend/other/searchbar.php' ?>
<div id="taskBox">
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
        ],
    ]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
    foreach ($tasks as $n):
        if (isset($_COOKIE[$n['idtask']]) && $_COOKIE[$n['idtask']] < strtotime($n['lastCommentTime'])) {
            $hasNewComments = true;
        } else {
            $hasNewComments = false;
        }
        if (!is_null($n['viewStatus']) && isset($n['viewStatus'][$n['idmanager']])) {
            $viewStatusTitleManager = 'Просмотрено ' . $n['viewStatus'][$n['idmanager']]['datetime'];
        } else {
            $viewStatusTitleManager = 'Не просмотрено';
        }
        if (is_null($n['viewStatus']) || !isset($n['viewStatus'][$id])) {
            $isTaskRead = false;
        } else {
            $isTaskRead = true;
        }

        include 'engine/frontend/other/task-card.php';
    endforeach; ?>
</div>
<script>
    $(document).ready(function () {
        var action = window.location.hash.substr(1);
        if (action === 'overdue') {
            $('#overdueSearch').trigger('click');
            $(".popUpDiv").hide();
        }
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
                        doneTasksOffset++;
                        $('#taskBox').append(data);
                    } else {
                        // что-то сделать, если нет задач в архиве
                    }
                },
            });
        }

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
                    } else {
                        // что-то сделать, если нет задач в архиве
                    }
                },
            });
        }
    });
</script>
