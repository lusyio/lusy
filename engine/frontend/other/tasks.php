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

    <?php if (count($tasks) > 0): ?>
        <div class="d-sm task-box taskbox-padding">
            <div class="row sort">
                <div class="col-6 col-sm-6 col-lg-6 col-xl-6 order-1 order-md-1 order-lg-0 order-xl-0 order-sm-1 pr-0 pl-sort">
                    <span id="nameOrder" class="btn btn-sm btn-secondary sort-task align-text-top">
                        <span id="nameOrderText"><?= $GLOBALS['_taskname'] ?> <i id="nameOrderIcon"
                                                                                 class="fas fa-sort"></i></span>
                    </span>
                </div>
                <div class="col-6 col-sm-6 col-lg-2 col-xl-2 order-3 order-sm-3 order-md-3 order-lg-0 order-xl-0 text-lg-right text-xl-left pl-sort pr-0 dropdown status-dropdown">
                    <button id="statusDropdownButton" class="btn btn-sm btn-secondary text-area-message dropdown-toggle sort-task"
                            type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            data-default-name="<?= $GLOBALS['_statustasks'] ?>">
                        <?= $GLOBALS['_statustasks'] ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right sort-task-dropdown"
                         aria-labelledby="statusDropdownButton">
                        <a class="dropdown-item task-type-dropdown-item<?= ($hasIncomeTasks) ? '' : '' ?>" href="#"
                           id="taskIn" data-task-type="in"><?= $GLOBALS["_workerfilter"] ?></a>
                        <a class="dropdown-item task-type-dropdown-item<?= ($hasOutcomeTasks) ? '' : '' ?>" href="#"
                           id="taskOut" data-task-type="out"><?= $GLOBALS["_managerfilter"] ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('new', $statuses)) ? '' : ' disabled' ?>"
                           href="#" id="newLink" data-status="new"><?= $GLOBALS["_newfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('inwork', $statuses)) ? '' : ' disabled' ?>"
                           href="#" id="inworkLink" data-status="inwork"><?= $GLOBALS["_inworkfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('returned', $statuses)) ? '' : ' disabled' ?>"
                           href="#" data-status="returned"><?= $GLOBALS["_returnedfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('overdue', $statuses)) ? '' : ' disabled' ?>"
                           href="#" id="overdueLink" data-status="overdue"><?= $GLOBALS["_overduefilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('postpone', $statuses)) ? '' : ' disabled' ?>"
                           href="#" id="postponeLink" data-status="postpone"><?= $GLOBALS["_postponefilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('pending', $statuses)) ? '' : ' disabled' ?>"
                           href="#" id="pendingLink" data-status="pending"><?= $GLOBALS["_pendingfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= (in_array('planned', $statuses)) ? '' : ' disabled' ?>"
                           href="#" id="plannedLink" data-status="planned"><?= $GLOBALS["_plannedfilter"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= ($countArchiveDoneTasks > 0) ? '' : ' disabled' ?>"
                           href="#" id="doneLink" data-status="done"><?= $GLOBALS["_completesearchbar"] ?></a>
                        <a class="dropdown-item status-dropdown-item<?= ($countArchiveCanceledTasks > 0) ? '' : ' disabled' ?>"
                           href="#" id="canceledLink" data-status="canceled"><?= $GLOBALS["_canceledsearchbar"] ?></a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item status-dropdown-item" href="#" data-status="0">Очистить фильтр</a>

                    </div>
                </div>
                <div class="col-6 col-sm-6 col-lg-2 col-xl-2 pr-0 order-2 order-md-2 order-lg-0 order-xl-0 pl-2">
                    <span id="dateOrder" class="btn btn-sm btn-secondary sort-task align-text-top">
                        <span id="dateOrderText"><?= $GLOBALS['_deadlinetasks'] ?> <i id="dateOrderIcon"
                                                                                      class="fas fa-sort"></i></span>
                    </span>
                </div>
                <div class="col-6 col-sm-6 col-lg-2 col-xl-2 order-sm-4 order-4 order-md-4 order-lg-0 order-xl-0  pr-0 pl-sort dropdown worker-dropdown">
                    <button id="workerDropdownButton" class="btn btn-sm btn-secondary text-area-message dropdown-toggle sort-task"
                            type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            data-default-name="<?= $GLOBALS['_memberstasks'] ?>">
                        <?= $GLOBALS['_memberstasks'] ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right sort-task-dropdown"
                         aria-labelledby="workerDropdownButton">
                        <?php foreach ($workersName as $wId => $wName): ?>
                            <a class="dropdown-item worker-dropdown-item" href="#"
                               data-worker-id="<?= $wId ?>"><?= $wName ?></a>
                        <?php endforeach; ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item worker-dropdown-item" href="#" data-worker-id="0">Очистить фильтр</a>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($tasks) == 0): ?>
        <div class="card mb-2" id="emptyTasks">
            <div class="card-body taskbox-padding">
                <div class="row">
                    <div class="col text-center">
                        <span class="mr-2">
                            Нет задач
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card mb-2  d-none" id="emptyTasksFilter">
        <div class="card-body taskbox-padding">
            <div class="row">
                <div class="col text-center search-empty">
                        <span class="mr-2">
                            Нет задач по выбранным фильтрам
                        </span>
                    <button class="btn btn-outline-secondary" id="clearAllFilters">Очистить фильтры</button>
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
