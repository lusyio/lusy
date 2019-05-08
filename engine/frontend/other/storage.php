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
<h3>Файлы  <?=$normalizedCompanyFilesSize['size']?> <?=$normalizedCompanyFilesSize['suffix']?>/<?=$normalizedProvidedSpace['size']?> <?=$normalizedProvidedSpace['suffix']?></h3>
<div class="row">
    <div class="col-md-11 pr-0">
        <input id="searchFile" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2" type="text" placeholder="<?=$GLOBALS["_fileplaceholder"]?>">
    </div>
    <div class="col-md-1 pl-0">
        <div class="btn btn-light" id="editFile">
            <span>Edit</span>
        </div>
    </div>
</div>
<hr>
    <?php foreach ($fileList as $file): ?>
    <div class="card files col-sm-12">
        <div class="card-body file-list">
            <div class="row">
                <div class="col-md-11">
                    <a href="../<?= $file['file_path'] ?>" class="h6 mb-3 file-name"><?= $file['file_name'] ?></a>
                    <span class="text-ligther ml-1"> <i class="fas fa-circle mr-1 ml-1"></i> <?= $file['file_size'] ?> <?= $file['sizeSuffix'] ?></span>
                </div>
                <div class="col-md-1">
                    <span class="text-secondary"><i val="<?= $file['file_id'] ?>" class="fas fa-times-circle deleteFile d-none"></i></span>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-1">
                    <span class="d-inline"><i class="far <?= key_exists($file['extension'], $fileIcon)? $fileIcon[$file['extension']]: "fa-file" ?> custom-file"></i></span>
                </div>
                <div class="col-md-11 p-1">
                    <a href="<?=$file['attachedToLink']?>" class="text-ligther"><?= $file['name'] ?> <?= $file['surname'] ?> <?=$GLOBALS["_attachto"]?> <?= $file['comment_type'] ?> <?= (is_null($file['taskName'])) ? '' : "'{$file['taskName']}'" ?> <?= $file['date'] ?></a>
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