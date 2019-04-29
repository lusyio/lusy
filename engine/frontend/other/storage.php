<h1>Хранилище файлов</h1>
<h3>Общий объем файлов: <?=$totalSize?> байт</h3>
<table class="table table-hover">
    <thead>
    <th>File ID</th>
    <th>Имя файла</th>
    <th>Размер</th>
    <th>К чему прикреплен</th>
    </thead>
    <tbody>
    <?php foreach ($fileList as $file): ?>
        <tr>
            <td><?= $file['file_id'] ?></td>
            <td><a href="../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></td>
            <td><?= $file['file_size'] ?></td>
            <td><a href="<?=$file['attachedToLink']?>"><?= $file['comment_type'] ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>