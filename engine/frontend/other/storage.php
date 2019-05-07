<h3>Файлы  <?=$totalSize?> <?=$totalSuffix?>/100 МБ</h3>
<input id="searchFile" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2" type="text" placeholder="Ведите название файла">
<hr>
    <?php foreach ($fileList as $file): ?>
    <div class="card files col-md-12">
        <div class="card-body file-list">
            <a href="../<?= $file['file_path'] ?>" class="h6 mb-3 file-name"><?= $file['file_name'] ?></a>
            <span class="text-ligther ml-1"> <i class="fas fa-circle mr-1 ml-1"></i> <?= $file['file_size'] ?> <?= $file['sizeSuffix'] ?></span>
            <div class="row mt-1">
                <div class="col-md-1">
                    <span class="d-inline"><i class="far fa-file-pdf custom-file"></i></span>
                </div>
                <div class="col-md-10 p-1">
                    <a href="<?=$file['attachedToLink']?>" class="text-ligther"><?= $file['name'] ?> <?= $file['surname'] ?> прикрепил к <?= $file['comment_type'] ?> '<?= $file['taskName'] ?>' 21.11.2019</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<script>
$(document).ready(function() {
    $("#searchFile").on("keyup", function () {
        var value = $(this).val();
        $(".files").hide();
        $(".files:contains(" + value + ")").show();
    });

    $(".custom-file").each(function () {
        var name = $(".file-name").text();
        if (name:contains('.png')){
            console.log(name);
        }
    })
} );
</script>