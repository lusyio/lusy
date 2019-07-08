<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex flex-wrap report-container">
            <?php
            require_once __ROOT__ . '/engine/backend/other/company.php';
            foreach ($sql as $n):
                $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
                $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')'); ?>

                <div class="card-body border-bottom border-right report-card-worker">
                    <a href="/profile/<?= $n['id'] ?>/" class="text-decoration-none">
                        <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar">
                    </a>
                    <a href="/profile/<?= $n['id'] ?>/">
                        <div class="d-inline ml-2"><span><?= $n["name"] ?> <?= $n["surname"] ?></span></div>
                    </a>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="text-muted">
                                <div>Выполнено</div>
                                <div>Просрочено</div>
                                <div>В работе</div>
                            </div>
                        </div>
                        <div class="col-3 col-lg-3 text-center">
                            <div class="count-company-tasks">
                                <span class="badge badge-company-primary"><?= $n['doneAsManager'] ?></span>
                                <span class="badge badge-company-dark"><?= $n['doneAsWorker'] ?></span>
                            </div>
                            <div><?= $overdue ?></div>
                            <div><?= $inwork ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span class="text-muted">Задачи за выбранный период:</span>
                            <div class="task-list-report">
                                <a href="#">
                                    <div class="task-card">
                                        <div class="card mb-2 tasks">
                                            <div class="card-body tasks-list">
                                                <div class="d-block border-left-tasks border-warning">
                                                    <p class="font-weight-light text-ligther d-none">Завершено</p>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div>
                                                                <span class="taskname">Написать программу вебинара</span>
                                                            </div>
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
                </div>
            <?php
            endforeach; ?>
        </div>
    </div>
</div>