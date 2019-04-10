<div class="text-center" id="f1">
<p class="text-ligther">Задача на рассмотрении с <?=date("d.m", strtotime(DBOnce('datepostpone','tasks','id='.$idtask)))?></p>
<h3 class="mt-4 mb-4"><?=$GLOBALS["_pending"]?></h3>


<div id="status-block">

	<!-- <p class="text-ligther"><?=$text?></p>
	<h1 class="mt-4"><?=$head?></h1> -->
	<button id ="workreturn" class="btn btn-primary mt-3 mb-3"><?=$GLOBALS["_return"]?></button>
<!-- <form method="post"> -->
	<!-- <input type="text" name="idtask" value="<?= $idtask?>"> -->
	<button id ="workdone" class="btn btn-primary mt-3 mb-3"><?=$GLOBALS["_done"]?></button>
	<!-- </form> -->
</div>
</div>