<a href="/task/<?= $n['idtask'] ?>/" class="text-decoration-none cust">
    <div class="task-card">
        <div class="card mb-2 tasks <?= $n['status'] ?><?= $n['classRole'] ?>">
            <div class="card-body tasks-list <?= ($isTaskRead)?'':'alert-primary'; ?>">
                <div class="d-block border-left-tasks <?= $borderColor[$n['status']] ?> ">

                    <p class="font-weight-light text-ligther d-none"><?= $taskStatusText[$n['mainRole']][$n['status']] ?></p>

                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="card-title mb-3"><span><?= $n['name'] ?></span></h5>
                            <div class="d-inline-flex w-100">
                                <div class="w-custom">
                                    <div class="progress position-relative h-100  mr-1">
                                        <div class="progress-bar bg-secondary-custom rounded" role="progressbar" style="width: <?= $n['dateProgress'] ?>%" aria-valuenow="<?= $n['dateProgress'] ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                                        <medium class="justify-content-center d-flex position-absolute w-100 h-100">
                                            <div class="p-custom"><i class="far fa-calendar-times text-ligther-custom">
                                                </i><span class="text-ligther-custom ml-2"><?=$GLOBALS["_deadlinelist"]?> </span><span><?= $n['deadLineDay'] ?> <?= $n['deadLineMonth'] ?></span>
                                            </div>
                                        </medium>
                                    </div>
                                </div>
                                <div class="informer p-2 rounded mr-1 <?= ($hasNewComments)?'bg-success':''; ?>"><i class="fas fa-comments">
                                    </i><span class="ml-2"><?=$n['countcomments']?></span>
                                </div>
                                <div class="informer p-2 rounded">
                                    <i class="fas fa-file"></i><span class="ml-2"><?=$n['countAttachedFiles']?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 d-flex" style="align-items: center">
                            <div class="font-weight-light text-ligther"><?= $taskStatusText[$n['mainRole']][$n['status']] ?></div>
                        </div>
                        <div class="col-sm-3 d-flex" style="align-items: center; justify-content: flex-end;">
                            <div class="float-right">
                                <img src="/<?=getAvatarLink($n['idmanager'])?>" title="<?= $viewStatusTitleManager ?>" class="avatar mr-1"> |
                                <?php
                                foreach ($n['coworkers'] as $coworker):
                                    if ($coworker == '') {
                                        continue;
                                    }
                                    if (!is_null($n['viewStatus']) && isset($n['viewStatus'][$coworker])) {
                                        $viewStatusTitle = 'Просмотрено ' . $n['viewStatus'][$coworker]['datetime'];
                                    } else {
                                        $viewStatusTitle = 'Не просмотрено';
                                    }
                                    ?>
                                    <img src="/<?=getAvatarLink($coworker)?>" title="<?= $viewStatusTitle ?>" class="avatar mr-1">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>