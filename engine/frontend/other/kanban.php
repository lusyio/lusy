<style>
    .block-kanban {
        min-width: 272px;
        margin-right: 10px;
    }

    .bg-kanban {
        background-color: #f3f3f3;
    }
    .navblock {
        position: absolute;
        left: 75px;
    }
    .col-sm-9 {
        width: 100% !important;
        flex: 100% !important;
        max-width: 100%;
        background: #fafafa;
    }
</style>
<div style="overflow-x: scroll;min-height: 600px">

    <div class="d-flex">

        <?php for ($i = 1; $i <= 10; $i++) : ?>

            <div class="block-kanban">
                <div class="bg-kanban p-2" style="border-radius: 5px">
                    <div class="card-body">
                        <strong>Статус задач <?= $i; ?></strong>
                    </div>

                    <?php $p = 0; ?>
                    <?php foreach ($new as $n) : ?>
                    <a href="/tasks/<?= $n['id']; ?>/" target="_blank">
                        <div class="card mb-3">
                            <div class="card-body">
                                <strong><?= $n['name']; ?></strong>
                            </div>
                        </div>
                    </a>
                        <?php $p++;
                        if ($p > $i) {
                            break;
                        }
                        ?>

                    <?php endforeach; ?>
                </div>
            </div>

        <?php endfor; ?>
    </div>

</div>