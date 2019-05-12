<?php
$commentClass = [
    'comment' => 'report',
    'system' => 'col-sm-6 system',
    'report' => 'report',
    'returned' => 'report',
    'postpone' => 'report',
    'request' => 'report',
];
if (!is_null($commentViewStatus) && isset($commentViewStatus[$c['manager']])) {
    $commentViewStatusTitleManager = 'Просмотрено ' . $commentViewStatus[$c['manager']]['datetime'];
} else {
    $commentViewStatusTitleManager = 'Не просмотрено';
}

?>
<div class="mb-4 comment <?= $commentClass[$commentStatus] ?> <?= ($isNew)? 'bg-success':'' ?>" id="<?= $c['id'] ?>">
	<div class="row">
		<div class="col-1">
			<img src="/upload/avatar/<?= $c['iduser'] ?>.jpg" class="avatar mt-1">
		</div>
		<div class="col-11">
			<div class="position-relative">
				
				<span class="date">
					<?= $dc ?>
					
					
					<?php if ($isDeletable && $id == $c['iduser'] && $c['status'] == 'comment'): ?>
			            <button type="button" value="#<?= $c['id'] ?>" class="btn btn-link text-danger delc position-absolute">
			                <i class="fas fa-times"></i>
			            </button>
			        <?php endif; ?>
					
				</span>
				
				<p class="mb-1"><a href="/profile/<?= $c['iduser'] ?>/" class="font-weight-bold"><?= $nameuser ?> <?= $surnameuser ?></a></p>
				
				
				<p class="mb-2"><?= nl2br($c['comment']) ?></p>
				
				
			    <?php if (count($files) > 0): ?>
			        <?php foreach ($files as $file): ?>
			            <?php if ($file['is_deleted']): ?>
			                <p class="mt-2 mb-2 text-secondary"><s><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></s> (удален)</p>
			            <?php else: ?>
			                <p class="mt-2 mb-2 text-secondary"><a class="text-secondary" href="../../<?= $file['file_path'] ?>"><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a></p>
			            <?php endif; ?>
			        <?php endforeach; ?>
			    <?php endif; ?>
			    
			    <div class="text-right d-none">
			        <img src="/upload/avatar/<?= $c['manager'] ?>.jpg"  class="avatar mr-3" title="<?= $commentViewStatusTitleManager ?>">
			        <?php
			        foreach ($coworkers as $coworker):
			            if (!is_null($commentViewStatus) && isset($commentViewStatus[$coworker['worker_id']])) {
			                $commentViewStatusTitle = 'Просмотрено ' . $commentViewStatus[$coworker['worker_id']]['datetime'];
			            } else {
			                $commentViewStatusTitle = 'Не просмотрено';
			            }
			            ?>
			        <img src="/upload/avatar/<?= $coworker['worker_id'] ?>.jpg"  class="avatar mr-3" title="<?= $commentViewStatusTitle ?>">
			        <?php endforeach; ?>
			    </div>
			    
			    
				
			</div>
		</div>
	</div>
</div>
</div>