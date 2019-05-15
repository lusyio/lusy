<div class="coworkers">
    <div class="card">
        <div class="card-body workers p-3">
            <div class="container p-1 container-coworker d-flex flex-wrap align-content-sm-stretch">
                <?php
                foreach ($coworkers as $coworker):
                    if (!is_null($viewStatus) && isset($viewStatus[$coworker['worker_id']])) {
                        $viewStatusTitle = 'Просмотрено ' . $viewStatus[$coworker['worker_id']]['datetime'];
                    } else {
                        $viewStatusTitle = 'Не просмотрено';
                    }
                    ?>
                    <div class="add-worker mr-1 mb-1">
                        <img title="<?= $viewStatusTitle ?>" src="/upload/avatar/<?=$coworker['worker_id']?>.jpg" class="avatar-added mr-1">
                        <a href="#" class="card-coworker"><?=$coworker['name']?> <?=$coworker['surname']?></a>
                        <span><i value="<?=$coworker['worker_id']?>" class="deleteWorker fas fa-times cancel card-coworker-delete"></i></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-1 text-justify" id="worker">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                foreach ($users as $n) { ?>
                    <div class="row">
                        <div class="col-1">
                            <img src="/upload/avatar/<?=$n['id']?>.jpg" class="avatar-added mr-1">
                        </div>
                        <div class="col">
                            <p class="mb-1 add-coworker-text" ><?php echo $n['name'] . ' ' . $n['surname'] ?></p>
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