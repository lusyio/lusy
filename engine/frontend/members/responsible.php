<div class="responsible">
    <div class="card">
        <div class="card-body workers p-3">
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
                        <div class="col-2">
                            <i value="<?php echo $n['id'] ?>" class="fas fa-plus add-coworker changeResponsible"></i>
                        </div>
                    </div>
                    <hr class="m-0">
                <?php } ?>
            </div>
        </div>
    </div>
</div>
