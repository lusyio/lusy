<?php
$fileIcon = [
        'png' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'jpg' => 'fa-file-image',
        'bmp' => 'fa-file-image',
        'pdf' => 'fa-file-pdf',
        'txt' => 'fa-file-alt',
        'doc' => 'far fa-file-word',
        'docx' => 'far fa-file-word',
    ];
?>
<h3>Файлы <?=$normalizedCompanyFilesSize['size']?> <?=$normalizedCompanyFilesSize['suffix']?>/<?=$normalizedProvidedSpace['size']?> <?=$normalizedProvidedSpace['suffix']?></h3>
<?php
include 'engine/frontend/nav/searchbarfile.php'
?>
<hr>
    <?php foreach ($fileList as $file): ?>
    <div class="card files col-12">
        <div class="card-body file-list">
            <div class="row">
                <div class="col-1">
                    <span class="d-inline"><i class="far <?= key_exists($file['extension'], $fileIcon)? $fileIcon[$file['extension']]: "fa-file" ?> custom-file"></i></span>
                </div>
                <div class="col-11 pl-0">
                    <div class="row">
                        <div class="col-md-11">
                            <a href="../<?= $file['file_path'] ?>" class="h6 mb-3 file-name"><?= $file['file_name'] ?></a>
                            <span class="text-ligther ml-1"> <i class="fas fa-circle mr-1 ml-1"></i> <?= $file['file_size'] ?> <?= $file['sizeSuffix'] ?></span>
                        </div>
                        <div class="col-md-1">
                            <span class="text-danger"><i val="<?= $file['file_id'] ?>" class="fas fa-times-circle deleteFile d-none"></i></span>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col">
                            <a href="<?=$file['attachedToLink']?>" class="text-ligther"><?= $file['name'] ?> <?= $file['surname'] ?> <?=$GLOBALS["_attachto"]?> <?= $file['comment_type'] ?> <?= (is_null($file['taskName'])) ? '' : "'{$file['taskName']}'" ?> <?= $file['date'] ?></a>
                        </div>
                    </div>
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

    $("#editFile").on('click', function () {
        if ($(".deleteFile").hasClass('d-none')){
            $(".deleteFile").removeClass('d-none');
        } else {
            $(".deleteFile").addClass('d-none');
        }

    });

    $(".deleteFile").on('click', function () {
        var fileId = $(this).attr('val');
        var file = $(this).parents(".files");
        $.post("/ajax.php", {module: 'deleteFile', fileId: fileId, usp: $usp, ajax: 'storage' },controlUpdate);
        function controlUpdate(data){
            if(data) {
                alert(data);
            }else{
                file.hide();
            }
        }

    })

} );
var $usp = <?php echo $id + 345;  // айдишник юзера ?>;
</script>