<div class="search-container">
    <div id="searchResult">
        <?php if (isset($request) && mb_strlen($request) > 0): ?>
        <div class="text-center text-reg mt">
            <p class="pt-2">&#128270 <?= $GLOBALS['_resultsearchtopsidebar'] ?> "<?= $request ?>"</p>
        </div>
    </div>
    <div class="search-result">
        <div class="card">
            <div class="card-body">
                <div id="taskSearch" class="resultSet">
                    <p class="text-reg"><?= $GLOBALS['_taskssearchtopsidebar'] ?>(<?= count($result['task']); ?>)</p>
                    <hr>
                    <?php foreach ($result['task'] as $resultItem): ?>
                        <a class="search-row" href="../task/<?= $resultItem['id'] ?>/">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['name'] ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div id="fileSearch" class="resultSet">
                    <p class="text-reg"><?= $GLOBALS['_filessearchtopsidebar'] ?> (<?= count($result['file']); ?>)</p>
                    <hr>
                    <?php foreach ($result['file'] as $resultItem): ?>
                        <a class="search-row" href="../<?= $resultItem['file_path'] ?>">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['file_name'] ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div id="commentSearch" class="resultSet">
                    <p class="text-reg"><?= $GLOBALS['_commentssearchtopsidebar'] ?> (<?= count($result['comment']); ?>)</p>
                    <hr>
                    <?php foreach ($result['comment'] as $resultItem): ?>
                        <a class="search-row" href="../task/<?= $resultItem['idtask'] ?>/#<?= $resultItem['id'] ?>">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['comment'] ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="search-empty">
                        <p><?= $GLOBALS['_emptysearchtopsidebar'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>