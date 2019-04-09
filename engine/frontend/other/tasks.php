<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<div class="card">
<table class="table table-hover" id="tasks">
  <thead>
    <tr>
      <th scope="col"><?=$GLOBALS["_taskname"]?></th>
      <th scope="col"><?=$GLOBALS["_taskworker"]?></th>
      <th scope="col"><?=$GLOBALS["_taskmanager"]?></th>
      <th scope="col"><?=$GLOBALS["_deadline"]?></th>
      <th scope="col"><?=$GLOBALS["_status"]?></th>
    </tr>
  </thead>
  <tbody>
<?php 
	$i = 0;
	foreach ($tasks as $n) {
	$idtask = $n["id"];
	$idworker = $n["worker"];
	$idmanager = $n["manager"];
	$status = $n["status"];
	$name = $n["name"];
	$namew = DBOnce('name','users','id='.$idworker);
	$surnamew = DBOnce('surname','users','id='.$idworker);
	$namem = DBOnce('name','users','id='.$idmanager);
	$surnamem = DBOnce('surname','users','id='.$idmanager);
	$datedone = $n["datedone"];
	$i++;
 ?>	
	<tr id="z_<?=$i?>">
		<td>
			<a href="/task/<?=$idtask?>/"><span class="card-title mb-3 border-primary"><?=$name?></span></a>
		</td>
		<td>
			<a href="/profile/<?=$worker?>"><?=$namew.' '.$surnamew?></a>
		</td>
		<td>
			<a href="/profile/<?=$manager?>"><?=$namem.' '.$surnamem?></a>
		</td>
		<td><?=$datedone?></td>
		<td><?=$GLOBALS["_$status"]?></td>
	</tr>
<?php }?>
  </tbody>
</table>
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
</script>