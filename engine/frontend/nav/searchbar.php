<h3 class="pb-3"><?=$GLOBALS["_tasks"]?></h3>

<div class="card mb-3 tasks-search-bar">
	<div class="card-body pb-2">
		<input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2" type="text" placeholder="<?=$GLOBALS["_searchplaceholder"]?>...">
		<div class="row">
		    <div class="col-sm-12 filter-bar">
		
		            <div class="d-inline-block">
		                <?php if($isManager): ?>
		                <div id="managerSearch" rol="manager" class="btn btn-secondary words-search role-search">
		                    <span><?=$GLOBALS["_managerfilter"]?></span>
		                    <span class="count"></span>
		                </div>
		                <?php endif; ?>
		                <?php if($isWorker): ?>
		                <div id="workerSearch" rol="worker" class="btn btn-secondary words-search role-search">
		                    <span><?=$GLOBALS["_workerfilter"]?></span>
		                    <span class="count"></span>
		                </div>
		                <?php endif; ?>
		                <?php foreach ($usedStatuses as $status): ?>
		                    <div id="<?= $status[0] ?>Search" rel="<?= $status[0] ?>" class="btn btn-secondary words-search status-search">
		                        <span><?=$GLOBALS["_{$status[0]}filter"]?></span>
		                        <span class="count"></span>
		                    </div>
		                <?php endforeach; ?>
		
		                <div id="resetSearch" class="btn btn-secondary words-search-reset">Сброс<i class="fas fa-times ml-1"></i></div>
		            </div>
		    </div>
		</div>
	</div>
</div>

<script>
    // $(document).ready(function(){
    //     $(".tasks-search-bar").on('mouseenter', function () {
    //         var searchBar = $(this).find('.filter-bar');
    //         searchBar.fadeIn(500);
    //     });
    // });
</script>