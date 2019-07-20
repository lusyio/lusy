<script type="text/javascript" src="/assets/js/tasks.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<?php include __ROOT__ . '/engine/frontend/other/searchbar.php' ?>
<div id="taskBox">
    <a href="/task/new/" class="text-decoration-none cust add-newtask-button">
        <div class="card mb-2 add-newtask-tasks">
            <div class="card-body" style="padding: 0.8rem;">
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
    <div style="padding: 0.8rem;" class="d-none d-sm task-box">
        <div style="padding-left: 7px;">
            <div class="row sort">
                <div class="col-sm-6">
                    <span><?= $GLOBALS['_taskname'] ?></span>
                </div>
                <div class="col-sm-2">
                    <span><?= $GLOBALS['_statustasks'] ?></span>
                </div>
                <div class="col-sm-2">
                    <span><?= $GLOBALS['_deadlinetasks'] ?></span>
                </div>
                <div class="col-sm-2">
                    <span><?= $GLOBALS['_memberstasks'] ?></span>
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
    foreach ($tasks as $n):
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
        include __ROOT__ . '/engine/frontend/other/task-card.php';
    endforeach; ?>
</div>
<script>
    $(document).ready(function () {
        var action = window.location.hash.substr(1);
        if (action === 'overdue') {
            $('#overdueSearch').trigger('click');
            $(".popUpDiv").hide();
        }
        if (action === 'inwork') {
            $('#inworkSearch').trigger('click');
            $(".popUpDiv").hide();
        }
        if (action === 'pending') {
            $('#pendingSearch').trigger('click');
            $(".popUpDiv").hide();
        }
        if (action === 'postpone') {
            $('#postponeSearch').trigger('click');
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

    });
</script>
