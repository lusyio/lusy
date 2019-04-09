<div class="text-center" id="f1">
<p class="text-ligther">Задача на рассмотрении с <?=date("d.m", strtotime(DBOnce('datepostpone','tasks','id='.$idtask)))?></p>
<h3 class="mt-4 mb-4"><?=$GLOBALS["_pending"]?></h3>

</div>