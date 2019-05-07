<?php

$totalSuffix = 'Б';
if ($totalSize > 1024 * 1024) {
    $totalSize = round($totalSize / (1024 * 1024));
    $totalSuffix = 'МБ';
} elseif ($totalSize > 1024) {
    $totalSize = round($totalSize / 1024);
    $totalSuffix = 'КБ';
}
?>

<h3>Файлы  <?=$totalSize?> <?=$totalSuffix?>/100 мб</h3>
<input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2" type="text" placeholder="Ведите название файла">
<hr>
<!--<table class="table table-hover">-->
<!--    <thead>-->
<!--    <th>File ID</th>-->
<!--    <th>Имя файла</th>-->
<!--    <th>Размер</th>-->
<!--    <th>Автор</th>-->
<!--    <th>К чему прикреплен</th>-->
<!--    </thead>-->
<!--    <tbody>-->
    <?php foreach ($fileList as $file):
    $fileSize = $file['file_size'];
    $sizeSuffix = 'Б';
    if ($fileSize > 1024 * 1024) {
        $fileSize = round($fileSize / (1024 * 1024));
        $sizeSuffix = 'МБ';
    } elseif ($fileSize > 1024) {
        $fileSize = round($fileSize / 1024);
        $sizeSuffix = 'КБ';
    }
        ?>

    <div class="card col-md-12">
        <div class="card-body file-list">
            <a href="../<?= $file['file_path'] ?>" class="h6 mb-3"><?= $file['file_name'] ?></a>
            <div class="row mt-1">
                <span class="d-inline"><i class="far fa-file-pdf custom-file"></i></span>
                <div class="col-md-4">
                    <span class="text-ligther">File size: <?= $fileSize ?> <?= $sizeSuffix ?></span>
                </div>
                <div class="col-md-3">
                    <span class="text-ligther">21.11.2019</span>
                </div>
                <div class="col-md-4 text-center">
                    <a href="<?=$file['attachedToLink']?>" class="text-ligther"><?= $file['comment_type'] ?></a>
                </div>
            </div>
        </div>
    </div>

<!--        <tr>-->
<!--            <td>--><?//= $file['file_id'] ?><!--</td>-->
<!--            <td><a href="../--><?//= $file['file_path'] ?><!--">--><?//= $file['file_name'] ?><!--</a></td>-->
<!--            <td>--><?//= $file['file_size'] ?><!--</td>-->
<!--            <td><a href="../profile/--><?//= $file['author'] ?><!--">--><?//= $file['name'] ?><!-- --><?//= $file['surname'] ?><!--</a></td>-->
<!--            <td><a href="--><?//=$file['attachedToLink']?><!--">--><?//= $file['comment_type'] ?><!--</a></td>-->
<!--        </tr>-->
    <?php endforeach; ?>
<!--    </tbody>-->
<!--</table>-->