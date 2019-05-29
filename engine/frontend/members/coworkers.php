<div class="coworkers">
    <div class="card">
        <div class="card-body workers p-3">
            <div class="p-1 text-justify" id="worker">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                foreach ($users as $n) { ?>
                    <div class="row">
                        <div class="col-1">
                            <img src="/upload/avatar/<?= $n['id'] ?>.jpg" class="avatar-added mr-1">
                        </div>
                        <div class="col">
                            <p class="mb-1 add-coworker-text"><?php echo $n['name'] . ' ' . $n['surname'] ?></p>
                        </div>
                        <div class="col-2">
                            <i value="<?php echo $n['id'] ?>" class="fas fa-plus add-coworker addNewWorker"></i>
                        </div>
                    </div>
                    <hr class="m-0">
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var coworkersId = new Map();
        var selectedId;
        $(".addNewWorker").on('click', function () {
            var selectedName = $(this).parent().siblings('.col').text();
            selectedId = $(this).attr('value');
            coworkersId.set(selectedId, selectedId);
            console.log(coworkersId);
            if ($(this).closest('.coworkersList-coworker').hasClass('bg-coworker')) {
            } else {
                $(".container-coworker").append("<div class=\"add-worker mr-1 mb-1\">\n" +
                    "                        <img val=\"" + selectedId + "\" src=\"/upload/avatar/" + selectedId + ".jpg\"\n" +
                    "                             class=\"avatar-added mr-1\">\n" +
                    "                        <a href=\"#\" class=\"card-coworker\">" + selectedName + "</a>\n" +
                    "                        <span><i value=\'" + selectedId + "\'\n" +
                    "                                 class=\"deleteWorker fas fa-times cancel card-coworker-delete\"></i></span>\n" +
                    "                    </div>");
            }
            $(this).closest('.coworkersList-coworker').addClass('bg-coworker');


            // $(".tooltip-avatar").prepend("<span class=\"mb-0\"><img src=\"/upload/avatar/" + selectedId + ".jpg\" alt=\"worker image\" class=\"avatar mr-1 ml-1\"></span>");
        });
    });

</script>