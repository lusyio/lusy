

<h3>Файлы  <?=$totalSize?> байт/100 мб</h3>
<input id="searchFile" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2" type="text" placeholder="Ведите название файла">
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
    <?php foreach ($fileList as $file): ?>

    <div class="card files col-md-12">
        <div class="card-body file-list">
            <a href="../<?= $file['file_path'] ?>" class="h6 mb-3 file-name"><?= $file['file_name'] ?></a>
            <span class="text-ligther ml-1"> . <?= $file['file_size'] ?> байт </span>
            <div class="row mt-1">
                <div class="col-md-1">
                    <span class="d-inline"><i class="far fa-file-pdf custom-file"></i></span>
                </div>
                <div class="col-md-10 p-1">
                    <a href="<?=$file['attachedToLink']?>" class="text-ligther"><?= $file['name'] ?> <?= $file['surname'] ?> прикрепил к <?= $file['comment_type'] ?> 'тест' 21.11.2019</a>
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

<script>
$(document).ready(function() {
    $("#searchFile").on("keyup", function () {
        var value = $(this).val();
        $(".files").hide();
        $(".files:contains(" + value + ")").show();
    });

    $(".custom-file").each(function () {


    })
} );
</script>