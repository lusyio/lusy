<script type="text/javascript" src="/assets/js/tasks.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<div id="taskBox">
    <?php include 'engine/frontend/nav/searchbar.php' ?>
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
        'done' => '',
        'canceled' => '',
    ],
    'worker' => [
        'new' => $GLOBALS['_tasknewworker'],
        'inwork' => $GLOBALS['_inprogresslist'],
        'overdue' => $GLOBALS['_overduelist'],
        'postpone' => $GLOBALS['_postponelist'],
        'pending' => $GLOBALS['_pendinglist'],
        'returned' => $GLOBALS['_returnedlist'],
        'done' => '',
        'canceled' => '',
    ],
]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
	foreach ($tasks as $n): ?>
    <a href="/task/<?= $n['idtask'] ?>/" class="text-decoration-none cust">
        <div class="task-card">
            <div class="card mb-2 tasks <?= $n['status'] ?><?= $n['classRole'] ?>">
                <div class="card-body tasks-list">
                    <div class="d-block border-left-tasks <?= $borderColor[$n['status']] ?>">
                        <h5 class="card-title mb-2"><span><?= $n['name'] ?></span></h5>
                        <p class="font-weight-light">Новая задача. Ознакомьтесь.</p>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="d-inline-flex w-100">
                                    <div class="w-custom">
                                        <div class="progress position-relative h-100  mr-1">
                                            <div class="progress-bar bg-secondary-custom rounded" role="progressbar" style="width: <?= $n['dateProgress'] ?>%" aria-valuenow="<?= $n['dateProgress'] ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                                            <medium class="justify-content-center d-flex position-absolute w-100 h-100">
                                                <div class="p-custom"><i class="far fa-calendar-times text-ligther-custom">
                                                    </i><span class="text-ligther-custom ml-2">Дедлайн: </span><span><?= $n['deadLineDay'] ?> <?= $n['deadLineMonth'] ?></span>
                                                </div>
                                            </medium>
                                        </div>
                                    </div>
			                        <div class="informer p-2 rounded mr-1"><i class="fas fa-comments">
                                        </i><span class="ml-2"><?=$n['countcomments']?></span>
                                    </div>
			                        <div class="informer p-2 rounded">
                                        <i class="fas fa-file"></i><span class="ml-2"><?=$n['countAttachedFiles']?></span>
                                    </div>
		                        </div>
	                        </div>
	                        <div class="col-sm-4">
		                        <div class="float-right">
	                        		<img src="/upload/avatar/<?=$n['idmanager']?>.jpg" class="avatar mr-1">
									<img src="/upload/avatar/<?=$n['idworker']?>.jpg" class="avatar mr-1">
		                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
</div>
<script>
$(document).ready(function() {
    $('#tasks').DataTable({
	    "paging":   false,
	    "searching": false,
	    "info": false,
	    "order": [[ 3, "asc" ]]
    });
} );
var $usp = <?php echo $id + 345;  // айдишник юзера ?>;
</script>
