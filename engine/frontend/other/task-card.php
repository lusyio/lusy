<?php
if ($manager == $id) {
    if ($worker == $id) {
        $taskType = 'self';
    } else {
        $taskType = 'out';
    }
} elseif ($worker == $id) {
    $taskType = 'in';
} else {
    $taskType = 'another';
}
?>
    <div class="task-card" data-worker-id="<?= $worker ?>" data-status="<?= $status ?>" data-deadline="<?= date('Y-m-d', $datedone) ?>"
         data-order-position="<?=(isset($orderPosition))? $orderPosition++ : '0' ?>"
         data-task-type="<?= $taskType ?>">
        <div class="card mb-2 tasks <?= $status ?> <?= $mainRole ?>">
            <a href="/task/<?= $taskId ?>/" class="text-decoration-none cust">
                <div class="card-body tasks-list <?= (count($subTasks)) ? 'shadow-subtask' : '';?> ">
                <div class="d-block border-left-tasks <?= $borderColor[$status] ?> ">
                    <p class="font-weight-light text-ligther d-none"><?= $taskStatusText[$mainRole][$status] ?></p>
                    <div class="row">
                        <div class="col-sm-5 col-lg-5 col-md-12 col-12">
                            <div class="text-area-message">
                                <span class="taskname"><?= ($viewStatus) ? '' : '<span class="text-danger font-weight-bold mr-1">!</span>'; ?><?= $name; ?></span>
                            </div>
                        </div>
                        <div class="col-sm-1 pl-0">

                            <div class="d-flex fc">
                                <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                    </i><span class="ml-1"><?= $countComments ?></span>
                                    <span class="ml-1 text-primary"><?= ($countNewComments > 0) ? '+' . $countNewComments : '' ?></span>
                                </div>
                                <div class="informer d-flex">
                                    <i class="fas fa-file"></i><span class="ml-1"><?= $countAttachedFiles ?></span>
                                    <span class="ml-1 text-primary"><?= ($countNewFiles > 0) ? '+' . $countNewFiles : '' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 col-lg-2 col-md-5 col-5">
                            <?= $taskStatusText[$mainRole][$status] ?>
                        </div>
                        <div class="col-sm-2 col-lg-2 col-md-3 col-3 <?= ($status == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($status, ['inwork', 'new', 'returned']) && date("Y-m-d", $datedone) == $now) ? 'text-warning font-weight-bold' : ''; ?>">
                            <?= date('j', $datedone) ?> <?= $_months[date('n', $datedone) - 1] ?>
                        </div>
                        <div class="col-sm-2 col-lg-2 col-md-4 col-4 avatars avatars-big">
                            <div class="float-right">
                                <?php if ($manager == $worker): ?>
                                    <img src="/<?= getAvatarLink($manager) ?>" class="avatar">
                                <?php else: ?>
                                    <img src="/<?= getAvatarLink($manager) ?>" class="avatar"> |
                                    <img src="/<?= getAvatarLink($worker) ?>" class="avatar">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </a>
            <?php if (count($subTasks)): ?>
            <div class="subTaskInList">
            <?php foreach ($subTasks as $subTask):
                $subStatus = $subTask->get('status');
                $subTaskId = $subTask->get('id');
                $subMainRole = $subTask->get('mainRole');
                $subName = $subTask->get('name');
                $subCountComments = $subTask->get('countComments');
                $subCountNewComments = $subTask->get('countNewComments');
                $subCountAttachedFiles = $subTask->get('countAttachedFiles');
                $subCountNewFiles = $subTask->get('countNewFiles');
                $subDatedone = $subTask->get('datedone');
                $subManager = $subTask->get('manager');
                $subWorker = $subTask->get('worker');
                $subViewStatus = $subTask->get('viewStatus');
                if ($subManager == $id) {
                    if ($subWorker == $id) {
                        $subTaskType = 'self';
                    } else {
                        $subTaskType = 'out';
                    }
                } elseif ($subWorker == $id) {
                    $subTaskType = 'in';
                } else {
                    $subTaskType = 'another';
                }
                ?>
                <div class="sub-task-card" data-worker-id="<?= $subWorker ?>" data-status="<?= $subStatus ?>"
                     data-deadline="<?= date('Y-m-d', $subDatedone) ?>" data-order-position="<?=(isset($orderPosition))? $orderPosition++ : '0' ?>"
                     data-task-type="<?= $subTaskType ?>">
                    <a href="/task/<?= $subTaskId ?>/" class="text-decoration-none cust">
                        <div class="tasks card-footer border-0 taskbox-padding">
                            <div class="d-block" style="margin-left: 8px;">
                                <div class="row">
                                    <div class="col-sm-5 col-lg-5 col-md-12 col-12">
                                        <div class="text-area-message">
                                            <span class="taskname taskname-subtask"><span
                                                        class="<?= $textColor[$subStatus] ?> pr-1">—</span> <?= ($subViewStatus) ? '' : '<span class="text-danger font-weight-bold mr-1">!</span>'; ?><?= $subName; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 pl-0">
                                        <div class="d-flex fc">
                                            <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                                </i><span class="ml-1"><?= $subCountComments ?></span>
                                                <span class="ml-1 text-primary"><?= ($subCountNewComments > 0) ? '+' . $subCountNewComments : '' ?></span>
                                            </div>
                                            <div class="informer d-flex">
                                                <i class="fas fa-file"></i><span
                                                        class="ml-1"><?= $subCountAttachedFiles ?></span>
                                                <span class="ml-1 text-primary"><?= ($subCountNewFiles > 0) ? '+' . $subCountNewFiles : '' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 col-md-5 col-5">
                                        <?= $taskStatusText[$subMainRole][$subStatus] ?>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 col-md-3 col-3 <?= ($subStatus == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($subStatus, ['inwork', 'new', 'returned']) && date("Y-m-d", $subDatedone) == $now) ? 'text-warning font-weight-bold' : ''; ?>">
                                        <?= date('j', $subDatedone) ?> <?= $_months[date('n', $subDatedone) - 1] ?>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 col-md-4 col-4 avatars avatars-big">
                                        <div class="float-right">
                                            <?php if ($subManager == $subWorker): ?>
                                                <img src="/<?= getAvatarLink($subManager) ?>" class="avatar">
                                            <?php else: ?>
                                                <img src="/<?= getAvatarLink($subManager) ?>" class="avatar"> |
                                                <img src="/<?= getAvatarLink($subWorker) ?>" class="avatar">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
