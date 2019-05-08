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

if ($companyUsageSpacePercent > 90){
    $bguser = 'bg-danger';
    $bgall = 'bg-danger';

} else {
    $bguser = 'bg-dark';
    $bgall = 'bg-primary';
}
?>
<h3><?=$GLOBALS["_files"]?> <?=$normalizedCompanyFilesSize['size']?> <?=$normalizedCompanyFilesSize['suffix']?>/<?=$normalizedProvidedSpace['size']?> <?=$normalizedProvidedSpace['suffix']?></h3>
<div class="progress col-4 mb-2 p-0">
    <div class="progress-bar <?=$bgall?>" role="progressbar" style="width: <?= $companyUsageSpacePercent ?>%" aria-valuenow="<?= $companyUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100" title="<?=$GLOBALS["_titlecompanyusage"]?>"></div>
    <div class="progress-bar <?=$bguser?>" role="progressbar" style="width: <?= $userUsageSpacePercent ?>%" aria-valuenow="<?= $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100" title="<?=$GLOBALS["_titleuserusage"]?>"></div>
</div>
<?php
if ($normalizedCompanyFilesSize['size'] > 0){
include 'engine/frontend/other/searchbarfile.php';
}
?>
<hr>
    <?php foreach ($fileList as $file): ?>
    <div class="card files">
        <div class="card-body file-list">
            <div class="row">
                <div class="col-1 iconFiles">
                    <i class="far <?= key_exists($file['extension'], $fileIcon)? $fileIcon[$file['extension']]: "fa-file" ?> custom-file"></i>
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
                            <a href="<?=$file['attachedToLink']?>" class="text-ligther"><?= $file['name'] ?> <?= $file['surname'] ?> <?=$GLOBALS["_attachto"]?> <?= $GLOBALS['_'.$file['comment_type']] ?> <?= (is_null($file['taskName'])) ? '' : "'{$file['taskName']}'" ?> <?= $file['date'] ?></a>
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