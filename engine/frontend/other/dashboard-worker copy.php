<div class="row mt-3">
	<div class="col-sm-12">
		<div class="card">
			<?php
			//	inc('other','mainview');
			?>
		</div>
	</div>
</div>
<div class="row mt-3" id="inwork">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-body border-bottom">
				<span class="font-weight-bold"><i class="fas fa-bolt text-warning mr-1"></i> <?=$GLOBALS["_inprogress"]?></span>
				<div class="float-right">
					<a href="#carouselNew" role="button" data-slide="prev" class="mr-1"><i class="fas fa-angle-left"></i></a>
					<a href="#carouselNew" role="button" data-slide="next"><i class="fas fa-angle-right"></i></a>
				</div>
			</div>
			<script>
				$(document).ready(function(){
				$('.carousel').carousel({
				  interval: false
				});
				}); 
			</script>
			<div id="carouselNew" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active" data-interval="20000">
					<?php
						$tasks = DB('*','tasks','(status = "overdue" or status = "postpone") and worker = "'.$GLOBALS["id"].'" ORDER BY datedone');
						$i=1;
						foreach ($tasks as $n) {
						    Task($n['id'],$n['name'],$n['status'],$n['datedone'],'',$n['manager'],$GLOBALS["langc"],'primary');
							if ($i % 3 == 0)  {
								echo '</div><div class="carousel-item">';
							}
							$i++;
							
						}
					?>
					</div>
			  </div>
		</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-body border-bottom">
				<span class="font-weight-bold"><i class="fas fa-eye text-success mr-1"></i> <?=$GLOBALS["_pending"]?></span>
				<div class="float-right">
					<a href="#carouselPending" role="button" data-slide="prev" class="mr-1"><i class="fas fa-angle-left"></i></a>
					<a href="#carouselPending" role="button" data-slide="next"><i class="fas fa-angle-right"></i></a>
				</div>
			</div>
			<script>
				$(document).ready(function(){
				$('.carousel').carousel({
				  interval: false
				});
				}); 
			</script>
			<div id="carouselPending" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active" data-interval="20000">
					<?php
						$tasks = DB('*','tasks','worker = "'.$GLOBALS["id"].'" and status = "pending"');
						$i=1;
						foreach ($tasks as $n) {
						    Task($n['id'],$n['name'],$n['status'],$n['datedone'],'',$n['manager'],$GLOBALS["langc"],'success');
							if ($i % 3 == 0)  {
								echo '</div><div class="carousel-item">';
							}
							$i++;
							
						}
					?>
					</div>
			  </div>
		</div>
		</div>
	</div>
</div>
<script>var $a = <?php $id = $GLOBALS["id"]; echo DBOnce('id','log','recipient = "'.$id.'" order by datetime desc'); // какой последний id  в логе мы видим сейчас ?>;</script>
<script src="/assets/js/dash-work.js"></script>