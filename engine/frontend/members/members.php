<div class="members">
    <div class="card p-2 position-relative">
        <div class="add-worker text-justify mr-1 mb-1 owner">
            <img title="<?= $viewStatusTitle ?>" src="/upload/avatar/<?= $coworker['worker_id'] ?>.jpg"
                 class="avatar-added mr-1">
            <a href="#"><?= $coworker['name'] ?> <?= $coworker['surname'] ?></a>
        </div>
        <hr class="m-0">
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
        <div class="members-coworkers">
            <div class="row">
                <div class="col-8 text-justify">
                    <span>Соисполнители</span>
                </div>
                <div class="col text-right">
                    <i class="fas fa-pencil-alt add-coworker editCoworkers" data-toggle="collapse"
                       data-target="#coworkersList" aria-expanded="false" aria-controls="coworkersList"></i>
                </div>
            </div>
            <hr class="mt-0 mb-1">
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
                        <img title="<?= $viewStatusTitle ?>" src="/upload/avatar/<?= $coworker['worker_id'] ?>.jpg"
                             class="avatar-added mr-1">
                        <a href="#" class="card-coworker"><?= $coworker['name'] ?> <?= $coworker['surname'] ?></a>
                        <span><i value="<?= $coworker['worker_id'] ?>"
                                 class="deleteWorker fas fa-times cancel card-coworker-delete"></i></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="p-1 text-justify collapse" id="coworkersList">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                foreach ($users as $n) { ?>
                    <div class="coworkersList-coworker">
                        <div class="row">
                            <div class="col-1">
                                <img src="/upload/avatar/<?= $n['id'] ?>.jpg" class="avatar-added mr-1">
                            </div>
                            <div class="col">
                                <a href="#"
                                   class="mb-1 add-coworker-text"><?php echo $n['name'] . ' ' . $n['surname'] ?></a>
                            </div>
                            <div class="col-2">
                                <i value="<?php echo $n['id'] ?>" class="fas fa-plus add-coworker addNewWorker"></i>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0">
                <?php } ?>
            </div>
            <div class="mt-3 text-center">
                <button class="btn btn-success btn-sm" id="confirmMembers" type="button">Принять</button>
                <!--                <button class="btn btn-outline-danger btn-sm" id="cancelMembers" type="button">Отмена</button>-->
            </div>
        </div>
    </div>
</div>
