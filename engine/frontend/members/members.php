<div class="members">
    <div class="card p-2">
        <?php
        foreach ($coworkers as $coworker):
            if (!is_null($viewStatus) && isset($viewStatus[$coworker['worker_id']])) {
                $viewStatusTitle = 'Просмотрено ' . $viewStatus[$coworker['worker_id']]['datetime'];
            } else {
                $viewStatusTitle = 'Не просмотрено';
            }
            ?>
            <div class="add-worker text-justify mr-1 mb-1">
                <img title="<?= $viewStatusTitle ?>" src="/upload/avatar/<?=$coworker['worker_id']?>.jpg" class="avatar-added mr-1">
                <a href="#" ><?=$coworker['name']?> <?=$coworker['surname']?></a>
            </div>
            <hr class="m-0">
        <?php endforeach; ?>
        <div class="members-responsible">
            <div class="row">
                <div class="col-8 text-justify">
                    <span>Ответственный</span>
                </div>
                <div class="col text-right">
                    <i class="fas fa-pencil-alt add-coworker editResponsible"></i>
                </div>
            </div>
            <hr class="m-0">
            <div class="p-1 text-justify">
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
                    </div>
                <?php } ?>
            </div>
        </div>
        <hr class="m-0">
        <div class="members-coworkers">
            <div class="row">
                <div class="col-8 text-justify">
                    <span>Соисполнители</span>
                </div>
                <div class="col text-right">
                    <i class="fas fa-pencil-alt add-coworker editCoworkers"></i>
                </div>
            </div>
            <hr class="m-0">
            <div class="p-1 text-justify"">
                <?php
                foreach ($coworkers as $coworker):
                if (!is_null($viewStatus) && isset($viewStatus[$coworker['worker_id']])) {
                    $viewStatusTitle = 'Просмотрено ' . $viewStatus[$coworker['worker_id']]['datetime'];
                } else {
                    $viewStatusTitle = 'Не просмотрено';
                }
                ?>
                    <div class="row">
                        <div class="col-1">
                            <img title="<?= $viewStatusTitle ?>" src="/upload/avatar/<?=$coworker['worker_id']?>.jpg" class="avatar-added mr-1">
                        </div>
                        <div class="col">
                            <p class="mb-1 add-coworker-text" ><?=$coworker['name']?> <?=$coworker['surname']?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <hr class="m-0">
        <div class="input-group-append mt-1">
            <button class="btn btn-outline-secondary btn-sm" id="confirmMembers" type="button">Принять</button>
            <button class="btn btn-outline-danger btn-sm" id="cancelMembers" type="button">Отмена</button>
        </div>
        </div>
    </div>
</div>
