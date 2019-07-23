
    <div class="task-card">
        <div class="card mb-2 tasks  <?= $n['task']['status'] ?><?= $n['task']['classRole'] ?>">
            <a href="/task/<?= $n['task']['idtask'] ?>/" class="text-decoration-none cust">
                <!--             класс для подзадач   shadow-subtask-->
                <div class="card-body tasks-list">
                <div class="d-block border-left-tasks <?= $borderColor[$n['task']['status']] ?> ">
                    <p class="font-weight-light text-ligther d-none"><?= $taskStatusText[$n['task']['mainRole']][$n['task']['status']] ?></p>
                    <div class="row">
                        <div class="col-sm-5 col-12">
                            <div class="text-area-message">
                                <span class="taskname"><?= ($isTaskRead) ? '' : '<span class="text-danger font-weight-bold mr-1">!</span>'; ?><?= $n['task']['name']; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-1 pl-0">

                            <div class="d-flex fc">
                                <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                    </i><span class="ml-1"><?= $n['task']['countcomments'] ?></span>
                                    <span class="ml-1 text-primary"><?= ($n['task']['countNewComments'] > 0) ? '+' . $n['task']['countNewComments'] : '' ?></span>
                                </div>
                                <div class="informer d-flex">
                                    <i class="fas fa-file"></i><span class="ml-1"><?= $n['task']['countAttachedFiles'] ?></span>
                                    <span class="ml-1 text-primary"><?= ($n['task']['countNewFiles'] > 0) ? '+' . $n['task']['countNewFiles'] : '' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-5">
                            <?= $taskStatusText[$n['task']['mainRole']][$n['task']['status']] ?>
                        </div>
                        <div class="col-sm-2 col-3 <?= ($n['task']['status'] == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($n['task']['status'], ['inwork', 'new', 'returned']) && date("Y-m-d", $n['task']['datedone']) == $now) ? 'text-warning font-weight-bold' : ''; ?>">
                            <?= $n['task']['deadLineDay'] ?> <?= $n['task']['deadLineMonth'] ?>
                        </div>
                        <div class="col-sm-2 col-4 avatars">
                            <div>
                                <img src="/<?= getAvatarLink($n['task']['idmanager']) ?>" class="avatar"> |
                                <img src="/<?= getAvatarLink($n['task']['idworker']) ?>" class="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>
            <?php if (isset($n['subTasks'])): ?>
            <?php foreach ($n['subTasks'] as $subTask): ?>
            <a href="/task/<?= $subTask['idtask'] ?>/" class="text-decoration-none cust">
            <div class="card-footer border-0" style="padding: 0.8rem;">
                <div class="d-block">
                    <div class="row">
                        <div class="col-sm-5 col-12">
                            <div class="text-area-message">
                                <span class="taskname" style="padding-left: 9px;"><span class="<?= $textColor[$subTask['status']] ?> pr-1">—</span> <?= $subTask['name']; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-1 pl-0">
                            <div class="d-flex fc">
                                <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                    </i><span class="ml-1"><?= $subTask['countcomments'] ?></span>
                                    <span class="ml-1 text-primary"><?= ($subTask['countNewComments'] > 0) ? '+' . $subTask['countNewComments'] : '' ?></span>
                                </div>
                                <div class="informer d-flex">
                                    <i class="fas fa-file"></i><span class="ml-1"><?= $subTask['countAttachedFiles'] ?></span>
                                    <span class="ml-1 text-primary"><?= ($subTask['countNewFiles'] > 0) ? '+' . $subTask['countNewFiles'] : '' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-5">
                            <?= $taskStatusText[$subTask['mainRole']][$subTask['status']] ?>
                        </div>
                        <div class="col-sm-2 col-3 <?= ($subTask['status'] == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($subTask['status'], ['inwork', 'new', 'returned']) && date("Y-m-d", $subTask['datedone']) == $now) ? 'text-warning font-weight-bold' : ''; ?>">
                            <?= $subTask['deadLineDay'] ?> <?= $subTask['deadLineMonth'] ?>
                        </div>
                        <div class="col-sm-2 col-4 avatars">
                            <div>
                                <img src="/<?= getAvatarLink($subTask['idmanager']) ?>" class="avatar"> |
                                <img src="/<?= getAvatarLink($subTask['idworker']) ?>" class="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
