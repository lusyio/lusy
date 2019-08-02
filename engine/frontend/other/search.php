<div class="search-container">
    <div id="searchResult">
        <?php if (isset($request) && mb_strlen($request) > 0 && (count($result['task']) > 0 || count($result['file']) > 0 || count($result['comment']) > 0)): ?>
        <div class="text-center mt">
            <h5 class="pt-2">&#128270 <?= $GLOBALS['_resultsearchtopsidebar'] ?> "<?= $request ?>"</h5>
        </div>
    </div>
    <div class="search-result">
        <div class="card">
            <div class="card-body">
                <div id="taskSearch" class="resultSet <?= (count($result['task']) == 0)? 'd-none': '' ?>">
                    <h5 class="text-search"><?= $GLOBALS['_taskssearchtopsidebar'] ?> (<?= count($result['task']); ?>
                        )</h5>
                    <hr>
                    <?php foreach ($result['task'] as $resultItem): ?>
                        <a class="search-row" href="../task/<?= $resultItem['id'] ?>/">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['name'] ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div id="fileSearch" class="resultSet mt-2 <?= (count($result['file']) == 0)? 'd-none': '' ?>">
                    <h5 class="text-search"><?= $GLOBALS['_filessearchtopsidebar'] ?> (<?= count($result['file']); ?>
                        )</h5>
                    <hr>
                    <?php foreach ($result['file'] as $resultItem): ?>
                        <a class="search-row" href="../<?= $resultItem['file_path'] ?>">
                            <div class="result-item">
                                <span class="search-header"><?= $resultItem['file_name'] ?></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div id="commentSearch" class="resultSet mt-2 <?= (count($result['comment']) == 0)? 'd-none': '' ?>">
                    <h5 class="text-search"><?= $GLOBALS['_commentssearchtopsidebar'] ?>
                        (<?= count($result['comment']); ?>)</h5>
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
                    <div class="card">
                        <div class="card-body search-empty">
                            <p><?= $GLOBALS['_emptysearchtopsidebar'] ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>