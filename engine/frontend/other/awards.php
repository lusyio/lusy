<script src="/assets/js/circle-progress.min.js"></script>
<div class="row mb-4">
    <div class="col">
        <span class="h3">Прогресс достижений</span>
        <div class="progress col mt-3 mb-2 p-0 w">
            <div class="progress-bar bg-success" role="progressbar" style="width: 8%" aria-valuenow="1"
                 aria-valuemin="0"
                 aria-valuemax="100" title="Достижения"><span class="text-white ml-3">2/123</span></div>
        </div>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($nonMultipleAchievements as $name => $datetime): ?>
    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}"
                 data-value="1.00"></div>
            <div class="award-star bg-primary">
                <i class="fas fa-user"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold"><?= $GLOBALS['_' . $name] ?></h6>
        <small class="text-muted mt-2 text-award"><?= $GLOBALS['_' . $name . '_TEXT' ]?></small>
        <hr>
        <span class="badge badge-primary"><?= date('d.m.Y', $datetime) ?></span>
    </div>
    <?php endforeach; ?>
</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3>Путь Ответственного</h3>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <?php foreach ($workerPath as $card): ?>
    <?php if ($card['got']) continue; ?>
    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;<?= ($card['got'])? 'rgba(0, 123, 255, 1)' : 'rgba(0, 0, 0, .3)' ?>&quot;}"
                 data-value="<?= $card['value'] ?>"></div>
            <div class="award-star <?= ($card['got'])? 'bg-primary' : 'bg-secondary' ?>">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold"><?= $card['title'] ?></h6>
        <div class="text-muted mt-2 text-award"><?= $card['text'] ?></div>
        <hr>
        <span class="badge <?= ($card['got'])? 'badge-primary' : 'badge-secondary' ?>"><?= $card['count'] ?></span>
    </div>
    <?php endforeach; ?>

</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3>Путь Руководителя</h3>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;}"
                 data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-atom"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Манагер</h6>
        <div class="text-muted mt-2 text-award">Назначил 10 задач</div>
        <hr>
        <span class="badge badge-secondary">0/10</span>
    </div>
    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;}"
                 data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-atom"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Делегатор</h6>
        <div class="text-muted mt-2 text-award">Назначил 50 задач</div>
        <hr>
        <span class="badge badge-secondary">0/50</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;}"
                 data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-atom"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">LVL100 B0$$</h6>
        <div class="text-muted mt-2 text-award">Назначил 100 задач</div>
        <hr>
        <span class="badge badge-secondary">0/100</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;}"
                 data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-atom"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Акула бизнеса</h6>
        <div class="text-muted mt-2 text-award">Назначил 200 задач</div>
        <hr>
        <span class="badge badge-secondary">0/200</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;}"
                 data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-atom"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Великий</h6>
        <div class="text-muted mt-2 text-award">Назначил 500 задач</div>
        <hr>
        <span class="badge badge-secondary">0/500</span>
    </div>
</div>

<div class="row mt-5 mb-1">
    <div class="col">
        <h3>Разовые достижения</h3>
    </div>
</div>

<div class="d-flex text-center flex-wrap">
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-handshake"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Знакомство</h6>
        <div class="text-muted mt-2 text-award">Заполнил профиль</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-thumbs-up"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Начало карьеры</h6>
        <div class="text-muted mt-2 text-award">Завершил задачу</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-meh"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Потрачено</h6>
        <div class="text-muted mt-2 text-award">Первая просрочка</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-user-friends"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">В команде</h6>
        <div class="text-muted mt-2 text-award">Завершил задачу с соисполнителем</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-medal"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Красавчик</h6>
        <div class="text-muted mt-2 text-award">Месяц без просрочки</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Больше всех</h6>
        <div class="text-muted mt-2 text-award">Завершил больше всех задач за месяц</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Цезарь</h6>
        <div class="text-muted mt-2 text-award">20 задач в работе</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Поймал кураж</h6>
        <div class="text-muted mt-2 text-award">Назначил 30 задач за день</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
    <div class="award mt-3 mr-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Шоу талантов</h6>
        <div class="text-muted mt-2 text-award">Завершить 500 задач за месяц</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
</div>


<div class="d-none">
    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fab fa-accessible-icon"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Помощник</h6>
        <div class="text-muted mt-3">Отправил баг репорт</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>

    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-broadcast-tower"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Сигнал получен</h6>
        <div class="text-muted mt-3 text-award">Получил личное сообщение</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>

    <div class="award mr-3 mt-3">
        <div>
            <div class="circle" data-value="0.00"></div>
            <div class="award-star bg-secondary">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <h6 class="text-uppercase font-weight-bold">Сам себе босс</h6>
        <div class="text-muted mt-3">Одобрен перенос</div>
        <hr>
        <span class="badge badge-secondary">не достигнуто</span>
    </div>
</div>

<script>
    jQuery(function ($) {
        $.fn.hScroll = function (amount) {
            amount = amount || 120;
            $(this).bind("DOMMouseScroll mousewheel", function (event) {
                var oEvent = event.originalEvent,
                    direction = oEvent.detail ? oEvent.detail * -amount : oEvent.wheelDelta,
                    position = $(this).scrollLeft();
                position += direction > 0 ? -amount : amount;
                $(this).scrollLeft(position);
                event.preventDefault();
            })
        };
    });

    $(document).ready(function () {
        $('.award-container').hScroll(30);
    });


    $('.circle').circleProgress({
        size: 75,
    });
</script>


