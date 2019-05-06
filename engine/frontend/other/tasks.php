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
	$i = 0;
    global $pdo;
    global $_months;
    $countAttachedFilesQuery = "SELECT COUNT(*) as count FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=:idtask) OR (u.comment_type='comment' AND c.idtask=:idtask)";
    $dbh = $pdo->prepare($countAttachedFilesQuery);
	foreach ($tasks as $n):
        $idtask = $n["id"];
        $idworker = $n["worker"];
        $idmanager = $n["manager"];
        $status = $n["status"];
        $name = $n["name"];
		$countcomments = DBOnce('COUNT(*) as count','comments','status="comment" and idtask='.$idtask);
        $dbh->execute(array(':idtask' => $idtask));
        $countAttachedFiles = $dbh->fetchColumn(0);
        $datedone = $n["datedone"];
        $deadLineDay = date('j', strtotime($n['datedone']));
        $deadLineMonth = mb_substr($_months[date('n', strtotime($n['datedone']))], 0,3);

        $datedone = $n["datedone"];
        $role = '';
        if ($id == $idworker) {
            $role .= ' worker';
        }
        if ($id == $idmanager) {
            $role .= ' manager';
        }
        $i++;
        ?>

        <div class="task-card">
            <div class="card mb-2 tasks <?= $status ?><?= $role ?>">
                <div class="card-body tasks-list" onclick="window.location='/task/<?= $idtask ?>/';">
                    <div class="d-block border-left-tasks <?= $borderColor[$status] ?>">
                        <a><h5 class="card-title mb-2"><span><?= $name ?></span></h5></a>
                        <p class="font-weight-light">Новая задача. Ознакомьтесь.</p>
                        <div class="row">

	                        <div class="col-sm-8 d-inline-flex">
                                <div class="progress position-relative w-custom h-100  mr-1">
                                    <div class="progress-bar bg-secondary-custom rounded" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    <medium class="justify-content-center d-flex position-absolute w-100 h-100"><div class="p-custom"><i class="far fa-calendar-times text-ligther-custom"></i><span class="text-ligther-custom ml-2">Дедлайн: </span><span><?= $deadLineDay ?>, <?= $deadLineMonth ?></span></div></medium>
                                </div>
<!--		                        <div class="informer d-inline p-2 rounded mr-1"><i class="far fa-calendar-times text-ligther"></i><span class="text-ligther ml-2">Дедлайн: </span><span>--><?//= $datedone ?><!--</span></div>-->
		                        <div class="informer p-2 rounded mr-1"><i class="fas fa-comments"></i><span class="ml-2"><?=$countcomments?></span></div>
		                        <div class="informer p-2 rounded"><i class="fas fa-file"></i><span class="ml-2">4</span></div>

	                        </div>
	                        <div class="col-sm-4">
		                        <div class="float-right">
									<a class="name-manager-tasks" href="/profile/<?=$idmanager?>/"><img src="/upload/avatar/<?=$idmanager?>.jpg" class="avatar mr-1"></a>
									<a class="name-manager-tasks" href="/profile/<?=$idworker?>/"><img src="/upload/avatar/<?=$idworker?>.jpg" class="avatar mr-1"></a>
		                        </div>
	                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


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
var $usp = <?php echo $id + 345;  // айдишник юзера ?>; var $it = '<?=$idtask?>';
</script>
