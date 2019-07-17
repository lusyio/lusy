
<div class="postpone-manager">
    <div class="report">
        <h4 class="text-ligther">Отчет:</h4>
        <?=htmlspecialchars_decode($report)?>
        <?php if (count($files) > 0): ?>
            <p class="">Прикрепленнные файлы:</p>
            <?php foreach ($files as $file): ?>
                <p><a class="" href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="text-center" id="f1">
        <span class="text-ligther align-center mb-0">Задача на рассмотрении с <?=$pendingdate?></span>
    </div>
</div>


