<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="card">
        <div class="card-body" style="min-height: 204px">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="slide-dashboard">
                        Добро пожаловать в систему Lusy.io! 🤗
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="slide-dashboard">
                        Это информационные слайды 🤔
                    </div>
                </div>
                <?php if ($userData['name'] == null && $userData['surname'] == null) :  ?>
                <div class="carousel-item">
                    <div class="slide-dashboard">
                        <span>У Вас не указаны имя и фамилия</span>
                        <br>
                        <span>Давайте познакомимся! 🖖</span>
                    </div>
                </div>
                <?php endif ?>
                <div class="carousel-item">
                    <div class="slide-dashboard">
                        <span>Отличная работа! Ваша статистика задач:</span>
                        <br>
                        <span><i class="fas fa-check text-success"></i>  <?= $done ?> выполненных</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ol class="carousel-indicators position-relative">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
        <li data-target="#myCarousel" data-slide-to="3"></li>
    </ol>

</div>


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
    <div class="col-sm-4 mb-3">
        <div<?= ($inwork == 0) ? ' class="no-events"' : ''; ?>>
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
    </div>
    <div class="col-sm-4 mb-3">
        <div<?= ($overdue == 0) ? ' class="no-events"' : ''; ?>>
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
    </div>
    <div class="col-sm-4 mb-3">
        <div<?= ($pending == 0) ? ' class="no-events"' : ''; ?>>
            <a href="/tasks/#pending" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                        <span class="font-weight-bold float-left mr-2"><i
                                    class="fas fa-search text-success fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $pending ?></span> <span
                                    class="text-lowercase"><?= $_pending ?></span></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-4 mb-3">
        <div<?= ($postpone == 0) ? ' class="no-events"' : ''; ?>>
            <a href="/tasks/#postpone" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                        <span class="font-weight-bold float-left mr-2"><i
                                    class="fas fa-clock text-warning fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $postpone ?></span> <span
                                    class="text-lowercase"><?= $_postpone ?></span></p>
                    </div>
                </div>
            </a>
        </div>
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