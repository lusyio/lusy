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
        'done' =>  $GLOBALS['_donelist'],
        'canceled' => $GLOBALS['_canceledlist'],
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
    ],
]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
	foreach ($tasks as $n):
        if (isset($_COOKIE[$n['idtask']]) && $_COOKIE[$n['idtask']] < strtotime($n['lastCommentTime'])) {
            $hasNewComments = true;
        } else {
            $hasNewComments = false;
        }
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
        ?>
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
	                        		<img src="/upload/avatar/<?=$n['idmanager']?>.jpg" title="<?= $viewStatusTitleManager ?>" class="avatar mr-1"> |
                                    <?php
                                    foreach ($n['coworkers'] as $coworker):
                                        if (!is_null($n['viewStatus']) && isset($n['viewStatus'][$coworker])) {
                                            $viewStatusTitle = 'Просмотрено ' . $n['viewStatus'][$coworker]['datetime'];
                                        } else {
                                            $viewStatusTitle = 'Не просмотрено';
                                        }
                                        ?>
									<img src="/upload/avatar/<?=$coworker?>.jpg" title="<?= $viewStatusTitle ?>" class="avatar mr-1">
                                    <?php endforeach; ?>
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
<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function() {
        cometApi.start({dev_id: 2553, user_id:<?=$id?>, user_key: '<?=$cometHash?>', node: "app.comet-server.ru"});
        subscribeToMessagesNotification();
        onlineStatusCheckIn('<?=$cometTrackChannelName?>');


    $(".progress-bar ").each(function () {
        var danger = $(this).attr('aria-valuenow');
        var danger1 = Number.parseInt(danger);
        if (danger1 >= 95) {
            $(this).next("medium").addClass('progress-danger');
        }
        if ($(this).parents("div").hasClass('done')){
            $(this).next("medium").html('<i class="fas fa-check p-1"></i>' + '<?=$GLOBALS["_donelist"]?>').addClass('progress-done p-2');
        }
    });

    $('#tasks').DataTable({
	    "paging":   false,
	    "searching": false,
	    "info": false,
	    "order": [[ 3, "asc" ]]
    });
} );
var $usp = <?php echo $id + 345;  // айдишник юзера ?>;
</script>
