

<script type="text/javascript" src="/assets/js/tasks.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<div id="taskBox">
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
	foreach ($tasks as $n):
        $idtask = $n["id"];
        $idworker = $n["worker"];
        $idmanager = $n["manager"];
        $status = $n["status"];
        $name = $n["name"];
        $namew = DBOnce('name', 'users', 'id=' . $idworker);
        $surnamew = DBOnce('surname', 'users', 'id=' . $idworker);
        $namem = DBOnce('name', 'users', 'id=' . $idmanager);
        $surnamem = DBOnce('surname', 'users', 'id=' . $idmanager);
        $datedone = $n["datedone"];
        $i++;
        ?>

            <div class="col-md-12 p-0" id="taskstop">
                <div class="card mb-2 tasks <?= $status ?>">
                    <div class="card-body tasks-list" onclick="window.location='/task/<?= $idtask ?>/';">
                        <div class="d-block mb-1 border-left-tasks <?= $borderColor[$status] ?>">
                            <a><h6 class="card-title mb-2"><span><?= $name ?></span></h6></a>
                            <img src="/upload/avatar/2.jpg" class="avatar mr-1">
                            <a href="/profile/<?= $idmanager ?>/"><?= $namem . ' ' . $surnamem ?></a>
                        </div>
                        <div class="d-inline-block">
                            <img src="/upload/avatar/1.jpg" class="avatar mr-1">
                            <a class="name-manager-tasks"
                               href="/profile/<?= $idworker ?>/"><?= $namew . ' ' . $surnamew ?></a>
                        </div>
                        <div class="d-inline-block">
                            <span class="icons-tasks"><i class="fas fa-comments custom-date"></i> </span>
                            <span class="icons-tasks"><i class="fas fa-file custom-date"></i> </span>
                        </div>
                        <div class="position-absolute date-status">
                            <span class="text-ligther"><i
                                        class="far fa-calendar-times custom"> </i> <?= $datedone ?></span>
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
