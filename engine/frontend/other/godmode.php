<script type="text/javascript" src="/assets/js/Chart.min.js"></script>
<div class="card premiumCard mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="d-flex">
                    <h2 class="mb-0 mr-3"
                        style="font-size: 48px;font-weight: 700;line-height: 1;"><?= $countCompaniesRegToday ?></h2>

                    <div>
                        <small class="mb-0 d-block">регистраций</small>
                        <small class="mb-0">за сегодня</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="d-flex text-center">
                    <?php foreach ($companyRegsDays as $n) : ?>
                        <div class="mr-5">
                            <small class="mb-0 d-block"><?= $n['date']; ?></small>
                            <small class="mb-0 font-weight-bold"><?= $n['count']; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($lastTenCompanyes as $n) : ?>
    <button class="btn btn-light bg-white w-100 text-left mb-2" type="button" data-toggle="collapse"
            data-target="#collapseCompany<?= $n['id']; ?>" aria-expanded="false"
            aria-controls="collapseCompany<?= $n['id']; ?>">
        <div class="row">
            <div class="col-7 font-weight-bold">
                <?= $n['idcompany']; ?>
            </div>
            <div class="col-1 text-secondary">
                <i class="fas fa-users mr-2"></i><?=countUsers($n['id']);?>
            </div>
            <div class="col-1 text-secondary">
                <i class="fas fa-clipboard mr-2"></i><?=countTasks($n['id']);?>
            </div>
            <div class="col-3 text-right text-secondary">
                <?= date("d.m в H:i",$n['datareg']); ?>
            </div>
        </div>
    </button>
    <div class="collapse mb-3" id="collapseCompany<?= $n['id']; ?>">
        <div class="card card-body">
            <?php $users = getUsersFromCompany($n['id']);
            foreach ($users as $u) :?>
            <div class="row">
                <div class="col-6">
                    <p><i class="fas fa-user mr-2"></i><?=$u['name'];?></p>
                </div>
                <div class="col-6 text-right">
                    был в сети <?=$u['activity'];?>
                </div>
            </div>
            <?php endforeach;?>
            <div class="pl-4">
                <div class="vertical-line">
                    <?php $events = lastEvents($n['id']);
                    foreach ($events as $e) :?>
                    <p><span><i class="far fa-dot-circle icon-not-complete"></i></span><?=$e['action'];?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<div class="card mb-3 pt-4">
    <div class="card-body pb-0">
        <div class="row">
            <div class="col-sm-8">
                <div class="chart"></div>
            </div>
            <div class="col-sm-4">
                <p class="text-godmode"><?= $activeCompanies; ?></p>
                <small class="text-secondary"><?= ngettext('active company', 'active companies', $activeCompanies); ?></small>
                <hr>
                <div class="mb-1"><span class="font-weight-bold mr-1"><?= $countCompanies ?></span>
                    <small class="text-secondary"><?= ngettext('company', 'companies', $countCompanies); ?>
                        зарегистрировано
                    </small>
                </div>
                <div class="mb-1"><span class="font-weight-bold mr-1"><?= $countUsers ?></span>
                    <small class="text-secondary"><?= ngettext('user', 'users', $countUsers); ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0"><?= $countTasks ?></h4>
                <small class="text-secondary"><?= ngettext('task', 'tasks', $countTasks); ?></small>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0"><?= $countComments ?></h4>
                <small class="text-secondary"><?= ngettext('comment', 'comments', $countComments); ?></small>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0"><?= $countMail ?></h4>
                <small class="text-secondary"><?= ngettext('message', 'messages', $countMail); ?></small>
            </div>
        </div>
    </div>
</div>
<div class="card d-none">
    <div class="card-body">
        <h5 class="text-center mb-3">Кол-во событий в системе за 24 часа</h5>
        <div class="chart mb-3"></div>

        <ul>
            <li>Общая статистика по системе (кол-во компаний, платных/бесплатных, пользователей, задач, комментариев и
                тд)
            </li>
            <li>Отладка - учет ошибок php и sql</li>
            <li>Добавление статей на сайт</li>
            <li>Управление тарифами и лимитами</li>
            <li>Управление ачивками</li>
        </ul>
    </div>
</div>
<div class="d-flex mt-3">
    <button type="button" data-toggle="collapse" href="#articles" role="button" aria-expanded="false"
            aria-controls="articles" class="btn btn-link bg-white border pr-3 pl-3 mr-3"><i
                class="fas fa-file-alt h3 mb-0 mt-2"></i>
        <p class="mb-0">Статьи</p></button>
    <button type="button" data-toggle="collapse" href="#emails" role="button" aria-expanded="false"
            aria-controls="emails" class="btn btn-link bg-white border pr-3 pl-3 mr-3"><i
                class="fas fa-envelope h3 mb-0 mt-2"></i>
        <p class="mb-0">Письма</p></button>
    <button type="button" data-toggle="collapse" href="#promocodes" role="button" aria-expanded="false"
            aria-controls="feedback" class="btn btn-link bg-white border pr-3 pl-3 mr-3"><i
                class="fas fa-percentage h3 mb-0 mt-2"></i>
        <p class="mb-0">Промокоды</p></button>
    <button type="button" data-toggle="collapse" href="#companies" role="button" aria-expanded="false"
            aria-controls="feedback" class="btn btn-link bg-white border pr-3 pl-3 mr-3"><i
                class="fas fa-user-tie h3 mb-0 mt-2"></i>
        <p class="mb-0">Компании</p></button>
    <a href="/knowledge/" class="btn btn-link bg-white border pr-3 pl-3 mr-3"><i
                class="fas fa-book h3 mb-0 mt-2"></i>
        <p class="mb-0">База знаний</p></a>
</div>
<div class="card mt-3 collapse" id="articles">
    <div class="card-body">
        <h5 class="text-center mb-3">Статьи</h5>
        <div class="articles-list">
            <?php foreach ($articlesList as $article): ?>
                <div class="article mb-2" data-article-id="<?= $article['article_id'] ?>">
                    <p class="article-name font-weight-bold"><?= $article['article_name'] ?></p>
                    <p class="article-category"><?= $article['category'] ?></p>
                    <p class="article-date"><?= date('Y-m-d', $article['publish_date']); ?></p>
                    <p class="article-url font-italic"><?= $article['url'] ?></p>
                    <div class="article-description text-muted"><?= $article['description'] ?></div>
                    <button class="btn btn-outline-primary show-article-text btn-sm">Показать/скрыть полный текст
                    </button>
                    <button class="btn btn-outline-warning edit-article btn-sm">Редактировать</button>
                    <div class="article-text mt-3 d-none">
                        <?= $article['article_text'] ?>
                    </div>
                    <button class="btn btn-outline-primary show-article-text article-text btn-sm d-none">Показать/скрыть
                        полный текст
                    </button>
                </div>
                <hr>
            <?php endforeach; ?>
        </div>
        <div class="add-article">
            <form method="post" id="addArticle" class="form-group">
                <h5 class="text-center mb-3">Добавление статьи</h5>
                <input class="form-control mb-1" id="articleId" type="hidden" placeholder="id" value="0">
                <input class="form-control mb-1" id="articleTitle" type="text" placeholder="Заголовок">
                <input class="form-control mb-1" id="articleUrl" placeholder="ЧПУ">
                <input class="form-control mb-1" id="articleCategory" placeholder="Категория">
                <textarea class="form-control mb-1" id="articleDescription" placeholder="Краткое описание"></textarea>
                <textarea class="form-control mb-1" id="articleText" placeholder="Полный текст"></textarea>
                <input type="date" id="articleDate" class="form-control mb-1">
                <label for="imgSmall">Миниатюра</label>
                <input class="form-control" type="file" id="imgSmall" name="img_small">
                <label for="imgFull">Полное изображение</label>
                <input class="form-control" type="file" id="imgFull" name="img_full">
                <button type="button" class="show-preview-modal btn btn-outline-primary" data-preview-type="article">
                    Предварительный просмотр
                </button>
                <button id="sendArticle" class="btn btn-primary">Добавить</button>
            </form>
        </div>
    </div>
</div>
<div class="card mt-3 collapse" id="emails">
    <div class="card-body">
        <h5 class="text-center mb-3">Шаблоны e-mail</h5>
        <div class="mail-templates-list">
            <?php foreach ($emailTemplates as $template): ?>
                <?php if ($template == '.' || $template == '..' || preg_match('~footer~', $template) || preg_match('~header~', $template)) continue; ?>
                <div class="mail-template mt-3">
                    <h6 class="mail-template-name"><?= $template ?></h6>
                    <div class="border border-primary">
                        <div class="mail-header">
                            <?php include $emailTemplatesDir . 'content-header.php'; ?>
                        </div>
                        <div class="mail-body">
                            <?php include $emailTemplatesDir . $template; ?>
                        </div>
                        <div class="mail-footer">
                            <?php include $emailTemplatesDir . 'content-footer.php'; ?>
                        </div>
                    </div>
                    <button class="btn btn-outline-warning edit-mail-body">Редактировать</button>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
        <div class="mail-template-edit">
            <div class="mail-edit">
                <label for="mailBody">content-header.php</label>
                <textarea class="form-control mb-1" id="mailHeader"
                          rows="6"><?php include $emailTemplatesDir . 'content-header.php'; ?></textarea>
                <button class="form-control btn btn-outline-warning">Сохранить</button>
            </div>
            <div class="mail-edit">
                <label for="mailBody">Body</label>
                <input id="bodyFileName" class="body-filename" value="" hidden>
                <input class="form-control mb-1 body-filename" value="" disabled>
                <textarea class="form-control mb-1" id="mailBody" rows="8"></textarea>
                <button type="button" class="show-preview-modal form-control btn btn-outline-primary"
                        data-preview-type="mail">
                    Предварительный просмотр
                </button>
                <button class="form-control btn btn-outline-warning">Сохранить</button>
            </div>
            <div class="mail-edit">
                <label for="mailFooter">content-footer.php</label>
                <textarea class="form-control mb-1" id="mailFooter"
                          rows="6"><?php include $emailTemplatesDir . 'content-footer.php'; ?></textarea>
                <button class="form-control btn btn-outline-warning">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<div class="card mt-3 collapse" id="feedback">
    <div class="card-body">
        <h5 class="text-center mb-3">Сообщения обратной связи</h5>
        <div class="mail-templates-list">
            <?php foreach ($feedback as $message): ?>
                <div class="mail-template mt-3">
                    <h4><?= $message['message_id'] ?>. <?= $message['message_title'] ?></h4>
                    <h5><?= $message['cause'] ?></h5>
                    <h6><?= $message['name'] ?> <?= $message['surname'] ?> (id <?= $message['user_id'] ?>) из
                        компании <?= $message['idcompany'] ?> (id <?= $message['company_id'] ?>)</h6>
                    <p><?= date('d.m.Y H:i', $message['datetime']) ?></p>
                    <p><?= $message['page_link'] ?></p>
                    <p><?= nl2br($message['message_text']) ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="card mt-3 collapse" id="promocodes">
    <div class="card-body">
        <h5 class="text-center mb-3">Промокоды</h5>
        <div class="promocodes-list">
            <table class="table table-hover">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Промокод</th>
                    <th scope="col">Дни</th>
                    <th scope="col">Срок действия</th>
                    <th scope="col">Многоразовый</th>
                    <th scope="col">Использован</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($promocodes as $code): ?>
                    <tr class="promo-row" data-promo-id="<?= $code['promocode_id']; ?>"
                        data-date="<?= date('Y-m-d', $code['valid_until']); ?>"
                        data-multiple="<?= $code['is_multiple']; ?>" data-used="<?= $code['used']; ?>"
                        data-days="<?= $code['days_to_add']; ?>"
                        data-promo-name="<?= $code['promocode_name']; ?>">
                        <th scope="row"><?= $code['promocode_name']; ?></th>
                        <td><?= $code['days_to_add']; ?></td>
                        <td><?= date('d.m.Y', $code['valid_until']); ?></td>
                        <td><?= ($code['is_multiple']) ? 'Да' : 'Нет'; ?></td>
                        <td><?= ($code['used']) ? 'Да' : 'Нет'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="row float-right">
            <button class="btn btn-primary mr-3" id="addPromoButton"><i class="fas fa-plus"></i></button>
        </div>
    </div>
</div>
<div class="card mt-3 collapse" id="companies">
    <div class="card-body">
        <h5 class="text-center mb-3">Компании</h5>
        <?php foreach ($companiesInfo as $company): ?>
            <div class="border-bottom">
                <h6><?= $company['idcompany']; ?></h6>
                <p><?= ($company['full_company_name'] != '') ? 'Полное название: ' . $company['full_company_name'] . ', ' : '' ?>
                    id: <?= $company['id']; ?></p>
                <p>Описание компании: <?= $company['description']; ?></p>
                <p>Сайт: <?= $company['site']; ?></p>
                <p>Зарегистрирована: <?= date('d.m.Y', $company['datareg']); ?>
                    (<?= floor((time() - $company['datareg']) / (3600 * 24)) ?> <?= ngettext('day', 'days', floor((time() - $company['datareg']) / (3600 * 24))) ?>
                    )</p>
                <p>Задач создано: <?= $company['allTasks']; ?></p>
                <p>Задач в работе: <?= $company['activeTasks']; ?></p>
                <p>Сотрудников: <?= $company['activeUsers']; ?></p>

            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="promoModal" tabindex="-1" role="dialog" aria-labelledby="promoModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promoModalLabel">Управление промокодом</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="promocodeId" class="" value="">
                <input type="hidden" id="promocodeNameHidden" class="" value="">
                <div class="input-group mt-3">
                    <input type="text" id="promocodeName" class="form-control" value="">
                </div>
                <small class="text-muted text-muted-reg">
                    Промокод
                </small>
                <div class="input-group mt-3">
                    <input type="number" id="promoDays" class="form-control" value="">
                </div>
                <small class="text-muted text-muted-reg">
                    Количество добавляемых дней
                </small>
                <div class="input-group mt-3">
                    <input type="date" id="validUntilDate" class="form-control" value="">
                </div>
                <small class="text-muted text-muted-reg">
                    Срок действия
                </small>
                <div class="input-group mt-3 pb-1">
                    <input class="new-checkbox" type="checkbox" id="multiple">
                </div>
                <small class="text-muted text-muted-reg">
                    Многопользовательский
                </small>
                <div class="input-group mt-3 pb-1">
                    <input class="new-checkbox" type="checkbox" id="used">
                </div>
                <small class="text-muted text-muted-reg">
                    Использован
                </small>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="showCompanies">Активировать для компании</button>
                <button type="button" class="btn btn-primary" id="savePromo"></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
            <div class="modal-footer d-none" id="companiesBlock">
                <select class="form-control" id="activatePromoCompany">
                    <option value="" hidden></option>
                    <option value="0">Все компании</option>
                    <?php foreach ($companiesList as $company): ?>
                        <option value="<?= $company['id']; ?>"><?= $company['idcompany']; ?>
                            , <?= $company['full_company_name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-primary" id="activatePromo">Активировать</button>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function createConfig(details, data) {
        var hours = [];
        for (var i = 0; i < 24; i++) {
            hours.push(i + ':00');
        }
        return {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [{
                    steppedLine: details.steppedLine,
                    data: <?= json_encode($eventsCount); ?>,
                    borderColor: details.color,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
                title: {
                    display: false,
                    text: details.label,
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            }
        };
    }


    window.onload = function () {
        var container = document.querySelector('.chart');

        var data = [
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor()
        ];

        var steppedLineSettings = [{
            steppedLine: false,
            label: 'Кол-во событий в системе за 24 часа',
            color: window.chartColors.red
        }];

        steppedLineSettings.forEach(function (details) {
            var div = document.createElement('div');
            div.classList.add('chart-container');

            var canvas = document.createElement('canvas');
            div.appendChild(canvas);
            container.appendChild(div);

            var ctx = canvas.getContext('2d');
            var config = createConfig(details, data);
            new Chart(ctx, config);
        });
    };

    $(document).ready(function () {
        $('.promo-row').on('click', function () {
            var promoId = $(this).data('promo-id');
            var promoName = $(this).data('promo-name');
            var promoDays = $(this).data('days');
            var promoDate = $(this).data('date');
            var promoMultiple = $(this).data('multiple');
            var promoUsed = $(this).data('used');
            $('#promocodeId').val(promoId);
            $('#promocodeName').val(promoName);
            $('#promocodeNameHidden').val(promoName);
            $('#promoDays').val(promoDays);
            $('#validUntilDate').val(promoDate);
            $('#multiple').prop('checked', promoMultiple);
            $('#used').prop('checked', promoUsed);
            $('#savePromo').text('Сохранить изменения');
            $('#promoModal').modal('show');
        });

        $('#promoModal').on('hide.bs.modal', function () {
            $('#promocodeId').val('');
            $('#promocodeName').val('');
            $('#promocodeNameHidden').val('');
            $('#promoDays').val('');
            $('#validUntilDate').val('');
            $('#multiple').prop('checked', false);
            $('#used').prop('checked', false);
            $('#savePromo').text('');
            $('#companiesBlock').addClass('d-none');
        });

        $('#addPromoButton').on('click', function () {
            $('#savePromo').text('Добавить промокод');
            $('#promoModal').modal('show');
        })

        $('#savePromo').on('click', function () {
            var promoId = $('#promocodeId').val();
            var promoName = $('#promocodeName').val();
            ;
            var promoDays = $('#promoDays').val();
            ;
            var promoDate = $('#validUntilDate').val();
            var promoMultiple = +$('#multiple').prop('checked');
            var promoUsed = +$('#used').prop('checked');

            var fd = new FormData;
            fd.append('ajax', 'godmode');
            fd.append('promocodeId', promoId);
            fd.append('promocodeName', promoName);
            fd.append('promocodeDays', promoDays);
            fd.append('promocodeDate', promoDate);
            fd.append('promocodeMultiple', promoMultiple);
            fd.append('promocodeUsed', promoUsed);
            if (promoId === '') {
                // Добавляем новый промокод
                fd.append('module', 'addPromocode');
            } else {
                //Обновляем существующий промокод
                fd.append('module', 'updatePromocode');
            }
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    console.log(response);
                    if (response === '1') {
                        location.reload();
                    }
                }
            });
        });

        $('#showCompanies').on('click', function () {
            $('#companiesBlock').removeClass('d-none');
        });

        $('#activatePromo').on('click', function () {
            var promocodeNameHidden = $('#promocodeNameHidden').val();
            var companyId = $('#activatePromoCompany').val();

            var fd = new FormData;
            fd.append('ajax', 'godmode');
            fd.append('module', 'activatePromocode');
            fd.append('promocodeName', promocodeNameHidden);
            fd.append('companyId', companyId);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    console.log(response);
                    location.reload();
                }
            });
        });
    });
</script>
<script src="/assets/js/godmode.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
