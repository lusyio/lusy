
    <div class="task-card">
        <div class="card mb-2 tasks  <?= $n['status'] ?><?= $n['classRole'] ?>">
            <a href="/task/<?= $n['idtask'] ?>/" class="text-decoration-none cust">
                <!--             класс для подзадач   shadow-subtask-->
                <div class="card-body tasks-list">
                <div class="d-block border-left-tasks <?= $borderColor[$n['status']] ?> ">
                    <p class="font-weight-light text-ligther d-none"><?= $taskStatusText[$n['mainRole']][$n['status']] ?></p>
                    <div class="row">
                        <div class="col-sm-5 col-12">
                            <div class="text-area-message">
                                <span class="taskname"><?= ($isTaskRead) ? '' : '<span class="text-danger font-weight-bold mr-1">!</span>'; ?><?= $n['name']; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-1 pl-0">

                            <div class="d-flex fc">
                                <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                    </i><span class="ml-1"><?= $n['countcomments'] ?></span>
                                    <span class="ml-1 text-primary"><?= ($n['countNewComments'] > 0) ? '+' . $n['countNewComments'] : '' ?></span>
                                </div>
                                <div class="informer d-flex">
                                    <i class="fas fa-file"></i><span class="ml-1"><?= $n['countAttachedFiles'] ?></span>
                                    <span class="ml-1 text-primary"><?= ($n['countNewFiles'] > 0) ? '+' . $n['countNewFiles'] : '' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-5">
                            <?= $taskStatusText[$n['mainRole']][$n['status']] ?>
                        </div>
                        <div class="col-sm-2 col-3 <?= ($n['status'] == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($n['status'], ['inwork', 'new', 'returned']) && date("Y-m-d", $n['datedone']) == $now) ? 'text-warning font-weight-bold' : ''; ?>">
                            <?= $n['deadLineDay'] ?> <?= $n['deadLineMonth'] ?>
                        </div>
                        <div class="col-sm-2 col-4 avatars">
                            <div>
                                <img src="/<?= getAvatarLink($n['idmanager']) ?>" class="avatar"> |
                                <img src="/<?= getAvatarLink($n['idworker']) ?>" class="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>

            <a href="#" class="text-decoration-none d-none cust">
            <div class="card-footer border-0" style="padding: 0.8rem;">
                <div class="d-block">
                    <div class="row">
                        <div class="col-sm-5 col-12">
                            <div class="text-area-message">
                                <span class="taskname" style="padding-left: 9px;"><span class="text-warning pr-1">—</span> Тест подзадачи</span>
                            </div>
                        </div>
                        <div class="col-sm-1 pl-0">
                            <div class="d-flex fc">
                                <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                    </i><span class="ml-1">1</span>
                                    <span class="ml-1 text-primary"></span>
                                </div>
                                <div class="informer d-flex">
                                    <i class="fas fa-file"></i><span class="ml-1">0</span>
                                    <span class="ml-1 text-primary"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-5">
                            Выполнено
                        </div>
                        <div class="col-sm-2 col-3  ">
                            1 января
                        </div>
                        <div class="col-sm-2 col-4 avatars">
                            <div>
                                <img src="/upload/avatar/2/2-alter.jpg" class="avatar"> |
                                <img src="/upload/avatar/2/4-alter.jpg" class="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
