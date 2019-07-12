<script src="https://www.chartjs.org/dist/2.8.0/Chart.min.js"></script>
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <div class="chart"></div>
            </div>
            <div class="col-sm-4">
                <p style=" font-size: 2.1em; margin-bottom: -10px; "><?= $activeCompanies; ?></p>
                <small class="text-secondary"><?= ngettext('active company', 'active companies', $activeCompanies); ?></small>
                <hr>
                <div class="mb-1"><span class="font-weight-bold mr-1"><?=$countCompanies?></span><small class="text-secondary"><?= ngettext('company', 'companies', $countCompanies); ?> зарегистрировано</small></div>
                <div class="mb-1"><span class="font-weight-bold mr-1"><?=$countUsers?></span><small class="text-secondary"><?= ngettext('user', 'users', $countUsers); ?></small></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0"><?=$countTasks?></h4>
                <small class="text-secondary"><?= ngettext('task', 'tasks', $countTasks); ?></small>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0"><?=$countComments?></h4>
                <small class="text-secondary"><?= ngettext('comment', 'comments', $countComments); ?></small>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-0"><?=$countMail?></h4>
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
    <button type="button" data-toggle="collapse" href="#feedback" role="button" aria-expanded="false"
            aria-controls="feedback" class="btn btn-link bg-white border pr-3 pl-3 mr-3"><i
                class="fas fa-lightbulb h3 mb-0 mt-2"></i>
        <p class="mb-0">Обратная связь</p></button>
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
                <button type="button" class="show-preview-modal form-control btn btn-outline-primary" data-preview-type="mail">
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
                    <h6><?= $message['name'] ?> <?= $message['surname'] ?> (id <?= $message['user_id'] ?>) из компании <?= $message['idcompany'] ?> (id <?= $message['company_id'] ?>)</h6>
                    <p><?= date('d.m.Y H:i', $message['datetime']) ?></p>
                    <p><?= $message['page_link'] ?></p>
                    <p><?= nl2br($message['message_text']) ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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


    window.onload = function() {
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

        steppedLineSettings.forEach(function(details) {
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
</script>
<script src="/assets/js/godmode.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>