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

if ($companyUsageSpacePercent > 90) {
    $bguser = 'bg-danger';
    $bgall = 'bg-danger';

} else {
    $bguser = 'bg-dark';
    $bgall = 'bg-primary';
}
?>
<h3><?= $GLOBALS["_files"] ?> <?= $normalizedCompanyFilesSize['size'] ?> <?= $normalizedCompanyFilesSize['suffix'] ?>
    /<?= $normalizedProvidedSpace['size'] ?> <?= $normalizedProvidedSpace['suffix'] ?></h3>
<div class="progress col-4 mb-2 p-0">
    <div class="progress-bar <?= $bguser ?>" role="progressbar" style="width: <?= $userUsageSpacePercent ?>%"
         aria-valuenow="<?= $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100"
         title="<?= $GLOBALS["_titleuserusage"] ?>"></div>
    <div class="progress-bar <?= $bgall ?>" role="progressbar"
         style="width: <?= $companyUsageSpacePercent - $userUsageSpacePercent ?>%"
         aria-valuenow="<?= $companyUsageSpacePercent - $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100"
         title="<?= $GLOBALS["_titlecompanyusage"] ?>"></div>
</div>
<?php
if ($normalizedCompanyFilesSize['size'] > 0) {
    include 'engine/frontend/other/searchbarfile.php';
}
if ($userTotalFilesSize == 0): ?>
    <hr>
    <div class="search-container">
        <div id="searchResult">
            <div class="card">
                <div class="card-body search-empty">
                    <p><?= $GLOBALS['_emptyliststorage'] ?></p>
                    <p><?= $GLOBALS['_emptylistsnotystorage'] ?></p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php foreach ($fileList as $file): ?>
    <div class="card files">
        <div class="card-body file-list">
            <div class="row">
                <div class="col-2 col-lg-1 iconFiles">
                    <i class="far <?= key_exists($file['extension'], $fileIcon) ? $fileIcon[$file['extension']] : "fa-file" ?> custom-file"></i>
                </div>
                <div class="col pl-0">
                    <a href="../<?= $file['file_path'] ?>"
                       class="h6 mb-3 file-name"><?= $file['file_name'] ?></a>
                    <span class="text-ligther ml-1"> <i
                                class="fas fa-circle mr-1 ml-1"></i> <?= $file['file_size'] ?> <?= $file['sizeSuffix'] ?></span>
                    <span class="text-ligther ml-1"> <i
                                class="fas fa-circle mr-1 ml-1"></i> <?= $file['date'] ?></span>
                    <span data-toggle="tooltip" data-placement="bottom" title="Удалить файл"
                          class="text-ligther deleteFile float-right position-absolute" style="right: 10px; top: 0;">
                        <i val="<?= $file['file_id'] ?>" class="fas fa-times-circle delete-file-icon"></i>
                    </span>
                    <?php if ($file['comment_type'] != 'conversation'): ?>
                        <a href="<?= $file['attachedToLink'] ?>"
                           class="text-ligther">
                            <?= $file['name'] ?> <?= $file['surname'] ?> <?= $GLOBALS["_attachto"] ?> <?= $GLOBALS['_' . $file['comment_type']] ?> <?= (is_null($file['taskName'])) ? '' : "'{$file['taskName']}'" ?>
                        </a>
                    <?php else: ?>
                        <span class="text-ligther">
                                <?= $file['name'] ?> <?= $file['surname'] ?> <?= $GLOBALS["_attachto"] ?> <?= $GLOBALS['_' . $file['comment_type']] ?> <?= (is_null($file['taskName'])) ? '' : "'{$file['taskName']}'" ?>
                            </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $(document).ready(function () {
        $("#searchFile").on("keyup", function () {

            // переопределяем метод contains для регистроНЕзависимого поиска
            $.expr[":"].contains = $.expr.createPseudo(function (arg) {
                return function (elem) {
                    return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
                };
            });

            var value = $(this).val();
            $(".files").hide();
            $(".file-name:contains(" + value + ")").closest('.files').show();
        });

        $('.files').on('click', function () {
            $(this).find('.deleteFile').fadeIn();
        });
        $('.files').hover(
            function () {
                $(this).find('.deleteFile').stop().show();
            }, function () {
                $(this).find('.deleteFile').stop().hide();

            });

        $(".deleteFile").on('click', function () {
            var fileId = $(this).find('.delete-file-icon').attr('val');
            var file = $(this).parents(".files");
            console.log(fileId);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    module: 'deleteFile',
                    fileId: fileId,
                    ajax: 'storage'
                },
                success: function (data) {
                    if (data) {
                        console.log(data);
                    } else {
                        file.fadeOut(750);
                    }
                }
            });
        })
    });
</script>