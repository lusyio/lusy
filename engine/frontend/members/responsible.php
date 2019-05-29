<div class="members-responsible">
    <div class="row">
        <div class="col-8 text-justify">
            <span>Ответственный</span>
        </div>
        <div class="col text-right">
            <i class="fas fa-pencil-alt add-coworker editResponsible" data-toggle="collapse"
               data-target="#responsibleList" aria-expanded="false" aria-controls="responsibleList"></i>
        </div>
    </div>
    <hr class="mt-0 mb-1">
    <div class="members-responsible-one mb-1">
        <div class="row">
            <div class="col-1">
                <img title="<?= $viewStatusTitle ?>" src="/upload/avatar/<?= $coworker['worker_id'] ?>.jpg"
                     class="avatar-added mr-1">
            </div>
            <div class="col text-justify">
                <a href="#"><?= $coworker['name'] ?> <?= $coworker['surname'] ?></a>
            </div>
        </div>
    </div>
    <hr class="m-0">
    <div class="p-1 text-justify collapse" id="responsibleList">
        <?php
        $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
        foreach ($users as $n) { ?>
            <div class="responsible-one">
                <div class="row">
                    <div class="col-1">
                        <img src="/upload/avatar/<?= $n['id'] ?>.jpg" class="avatar-added mr-1">
                    </div>
                    <div class="col">
                        <a href="#"
                           class="mb-1 add-coworker-text"><?php echo $n['name'] . ' ' . $n['surname'] ?></a>
                    </div>
                    <div class="col-2">
                        <i value="<?php echo $n['id'] ?>"
                           class="fas fa-exchange-alt add-coworker changeResponsible"></i>
                    </div>
                </div>
            </div>
            <hr class="m-0">
        <?php } ?>
    </div>
</div>