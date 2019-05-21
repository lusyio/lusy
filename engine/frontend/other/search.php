<div id="searchResult">

    <?php if (isset($request) && mb_strlen($request) > 0): ?>
    <div>
        <p>Результаты по запросу: <?= $request ?></p>
    </div>

</div>
<div id="taskSearch" class="resultSet">
    <p>Задачи(<?= count($result['task']); ?>)</p>
    <?php $i = 1; ?>
    <?php foreach ($result['task'] as $resultItem): ?>
        <div class="result-item">
            <a href="../task/<?= $resultItem['id'] ?>/"><?= $i ?> <?= $resultItem['name'] ?></a>
        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
    <br>
</div>
<div id="fileSearch" class="resultSet">
    <p>Файлы(<?= count($result['file']); ?>)</p>
    <?php $i = 1; ?>
    <?php foreach ($result['file'] as $resultItem): ?>
        <div class="result-item">
            <a href="../<?= $resultItem['file_path'] ?>"><?= $i ?> <?= $resultItem['file_name'] ?></a>
        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
    <br>
</div>
<div id="commentSearch" class="resultSet">
    <p>Комментарии(<?= count($result['comment']); ?>)</p>
    <?php $i = 1; ?>
    <?php foreach ($result['comment'] as $resultItem): ?>
        <div class="result-item">
            <a href="../task/<?= $resultItem['idtask'] ?>/#"<?= $resultItem['id'] ?>><?= $i ?> <?= $resultItem['comment'] ?></a>
        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div>
        <p>Не задан запрос</p>
    </div>
<?php endif; ?>
</div>