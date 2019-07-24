<div class="row" style="margin-top: 60px;">
    <div class="col-12">
        <label class="label-tasknew" id="scrollAnchor">
            Статистика по сотрудникам за выбранный период
        </label>
        <div style="padding: 0.8rem;" class="d-sm task-box">
            <div style="padding-left: 7px;">
                <div class="row sort small text-reports">
                    <div class="col-4 text-center">
                        <span>Сотрудник <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Выполнил <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Поручил <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Просрочил <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Перенес <i class="fas fa-sort d-none"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once __ROOT__ . '/engine/backend/other/company.php';
        foreach ($sql

                 as $n):
            $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
            $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')'); ?>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 col-lg-1">
                                    <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added m-0">
                                </div>
                                <div class="col-10 col-lg-3 text-left pl-0 pr-0 worker-name-reports">
                                    <span class="mb-1 text-color-new"><?= $n["name"] ?> <?= $n["surname"] ?></span>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new done-tasks"><?= $n['doneAsWorker'] ?></div>
                                    <small class="text-muted company-tasks">Выполнил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new done-tasks-manager"><?= $n['doneAsManager'] ?></div>
                                    <small class="text-muted company-tasks">Поручил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new overdue-tasks"><?= $overdue ?></div>
                                    <small class="text-muted company-tasks">Просрочил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new postpone-tasks">15</div>
                                    <small class="text-muted company-tasks">Перенес</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>

<div class="row tasks-reports-container" style="margin-top: 60px; display: none">
    <div class="col-12">
        <label class="label-tasknew">
            Задачи сотрудника за выбранный период
        </label>
    </div>
</div>

<div class="row" style="margin-top: 40px;">
    <div class="col-12">
        <div class="tasks-list-report-empty">
        </div>
        <div class="tasks-list-report" id="tasksReport">
            <a class="text-decoration-none cust" href='#'
            <div class="task-card">
                <div class="card mb-2 tasks">
                    <div class="card-body tasks-list">
                        <div class='d-block'>
                            <div class="row">
                                <div class="col-2 col-lg-1 task-report text-center">
                                    <div class='reportIcon'>
                                        <i class=''></i>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-8">
                                    <div class="text-area-message">
                                        <span class="taskname mb-0"> Название задачи </span>
                                    </div>
                                    <span class="small mb-0"> workerFullName  </span>
                                </div>
                                <div class="col-4 col-lg-3 pl-0 text-center">
                                    <span class='report-task-text'> статус </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
</div>
