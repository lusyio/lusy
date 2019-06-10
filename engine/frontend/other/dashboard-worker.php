<div class="card">
    <div class="card-body">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <p class="text-center pt-5 pb-4">Добро пожаловать в систему Lusy.io!</p>
                </div>
                <div class="carousel-item">
                    <p class="text-center pt-5 pb-4">Это информационные слайды</p>
                </div>
                <div class="carousel-item">
                    <?php
                    include 'engine/frontend/other/chart.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<ol class="carousel-indicators position-relative">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
</ol>

<div class="row">
    <div class="col-sm-4 mb-3">
        <a href="/tasks/" class="text-decoration-none">
            <div class="card">
                <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i
                                class="fas fa-tasks text-secondary fa-fw mr-1"></i></span>
                    <p class="mb-0"><span class="font-weight-bold"><?= $all ?></span> <span
                                class="text-lowercase"><?= $_alltasks ?></span></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-4 mb-3<?= ($inwork == 0) ? ' no-events' : ''; ?>">
        <a href="/tasks/#inwork" class="text-decoration-none">
            <div class="card">
                <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i
                                class="fas fa-bolt text-primary fa-fw mr-1"></i></span>
                    <p class="mb-0"><span class="font-weight-bold"><?= $inwork ?></span> <span
                                class="text-lowercase"><?= $_inprogress ?></span></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-4 mb-3<?= ($overdue == 0) ? ' no-events' : ''; ?>">
        <a href="/tasks/#overdue" class="text-decoration-none">
            <div class="card">
                <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i
                                class="fas fa-fire-alt text-danger fa-fw mr-1"></i></span>
                    <p class="mb-0"><span class="font-weight-bold"><?= $overdue ?></span> <span
                                class="text-lowercase"><?= $_overdue ?></span></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-4 mb-3<?= ($pending == 0) ? ' no-events' : ''; ?>">
        <a href="/tasks/#pending" class="text-decoration-none">
            <div class="card">
                <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i class="fas fa-search text-success fa-fw mr-1"></i></span>
                    <p class="mb-0"><span class="font-weight-bold"><?= $pending ?></span> <span
                                class="text-lowercase"><?= $_pending ?></span></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-4 mb-3<?= ($postpone == 0) ? ' no-events' : ''; ?>">
        <a href="/tasks/#postpone" class="text-decoration-none">
            <div class="card">
                <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i class="fas fa-clock text-warning fa-fw mr-1"></i></span>
                    <p class="mb-0"><span class="font-weight-bold"><?= $postpone ?></span> <span
                                class="text-lowercase"><?= $_postpone ?></span></p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body pb-0">
        <span class="font-weight-bold"><?= $_history ?></span>
        <hr class="mb-0">
        <div id="log">
            <ul class="timeline" style="bottom: 0px;">
                <?php foreach ($events as $event): ?>
                    <?php renderEvent($event); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.carousel').carousel({
            interval: 10000
        });
    });
</script>