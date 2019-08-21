<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html">
<?php if ($showNote): ?>
<div class="postpone-manager <?=$displayNote?>" id="inwork-postpone-manager">
    <div class="report">
        <h4 class="text-ligther mb-3"><?=$note?></h4>
        <p><?=htmlspecialchars_decode($request)?></p>
        <?php if (count($files) > 0): ?>
            <p class="">Прикрепленнные файлы:</p>
            <?php foreach ($files as $file): ?>
                <p><a class="" href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<div id="report-block" class="collapse review-block mt-2">
	<p class="font-weight-bold mb-3"><?=$GLOBALS["_writereport"]?>:</p>
	<div class="form-group mb-0 drag-n-drop">
        <div class="row">
            <div class="col">
		        <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
            </div>
        </div>
        <div class="bg-white file-name-review container-files display-none">
        </div>
        <div class="d-flex mt-3">
            <div class="mr-3">
                <button type="submit" id="sendonreview" class="btn btn-primary w-100 text-center"><i class="fas fa-file-import mr-3"></i>Отправить на рассмотрение</button>
            </div>
            <div class="mr-3">
                <?php $uploadModule = 'task'; ?>
                <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>
            </div>
            <div>
                <button type="button" id="backbutton" class="btn btn-outline-secondary w-100 text-center" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-angle-double-left mr-3"></i><?= $GLOBALS["_back"] ?></button>
            </div>

        </div>

	</div>
</div>


<div id="status-block" class="status-block-inwork-worker">
    <button type="button" id="done" class="btn btn-primary mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
    <button type="button" id="changeDate" class="btn btn-warning mt-3 mb-1 d-none" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date">Перенести срок</button>
</div>


<script>

</script>

