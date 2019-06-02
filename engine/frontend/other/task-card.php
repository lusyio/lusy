<a href="/task/<?= $n['idtask'] ?>/" class="text-decoration-none cust">
    <div class="task-card">
        <div class="card mb-2 tasks  <?= $n['status'] ?><?= $n['classRole'] ?>">
            <div class="card-body tasks-list">
                <div class="d-block border-left-tasks <?= $borderColor[$n['status']] ?> ">
                    <p class="font-weight-light text-ligther d-none"><?= $taskStatusText[$n['mainRole']][$n['status']] ?></p>
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <span class="taskname"><?= ($isTaskRead)?'':'<span class="text-danger font-weight-bold mr-1">!</span>'; ?><?= $n['name'] ?></span>
                                    <div style=" color: #c6c9dc; font-size: 12px;    margin-top: 5px; ">
                                        <span class="mr-3">#<?= $n['idtask'] ?></span>
                                        <span class="mr-3"><i class="fas fa-comments"></i><span class="ml-1"><?=$n['countcomments']?></span></span>
                                        <span class="mr-3"><i class="fas fa-file"></i><span class="ml-1"><?=$n['countAttachedFiles']?></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-5" style=" margin-top: 10px; ">
                            <?= $taskStatusText[$n['mainRole']][$n['status']] ?>
                        </div>
                        <div style=" margin-top: 10px; " class="col-sm-2 col-3 <?= ($n['status']=='overdue')?'text-danger font-weight-bold':''; ?> <?= ($n['status']=='inwork' and $n['datedone']==$now)?'text-warning font-weight-bold':''; ?>">
                            <?= $n['deadLineDay'] ?> <?= $n['deadLineMonth'] ?>
                        </div>
                        <div class="col-sm-2 col-4 avatars" style=" margin-top: 10px; ">
                            <div>
                                <img src="/<?=getAvatarLink($n['idmanager'])?>" title="<?= $viewStatusTitleManager ?>" class="avatar"> |
                                <img src="/<?=getAvatarLink($n['idworker'])?>" title="<?= $viewStatusTitleManager ?>" class="avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>