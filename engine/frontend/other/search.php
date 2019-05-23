<div class="search-container">
    <div id="searchResult">
        <?php if (isset($request) && mb_strlen($request) > 0): ?>
        <div class="text-center text-reg mt">
            <p class="pt-2">Результат по запросу &#128270: <?= $request ?></p>
        </div>
    </div>
    <div class="search-result">
        <div class="card">
            <div class="card-body">
                <div id="taskSearch" class="resultSet">
                    <p class="text-reg">Задачи (<?= count($result['task']); ?>)</p>
                    <hr>
                    <?php $i = 1; ?>
                    <?php foreach ($result['task'] as $resultItem): ?>
                        <a class="search-row" href="../task/<?= $resultItem['id'] ?>/">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['name'] ?></span>
<!--                                <br>-->
<!--                                <span class="search-content">--><?//= $i ?><!--</span>-->
                            </div>
                        </a>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
                <div id="fileSearch" class="resultSet">
                    <p class="text-reg">Файлы (<?= count($result['file']); ?>)</p>
                    <hr>
                    <?php $i = 1; ?>
                    <?php foreach ($result['file'] as $resultItem): ?>
                        <a class="search-row" href="../<?= $resultItem['file_path'] ?>">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['file_name'] ?></span>
<!--                                <br>-->
<!--                                <span class="search-content">--><?//= $i ?><!--</span>-->
                            </div>
                        </a>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                    <br>
                </div>
                <div id="commentSearch" class="resultSet">
                    <p class="text-reg">Комментарии (<?= count($result['comment']); ?>)</p>
                    <hr>
                    <?php $i = 1; ?>
                    <?php foreach ($result['comment'] as $resultItem): ?>
                        <a class="search-row" href="../task/<?= $resultItem['idtask'] ?>/#"<?= $resultItem['id'] ?>>
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['comment'] ?></span>
<!--                                <br>-->
<!--                                <span class="search-content">--><?//= $i ?><!-- </span>-->
                            </div>
                        </a>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="search-empty">
                        <p>По запросу ничего не найдено.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>