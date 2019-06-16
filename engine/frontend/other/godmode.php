<script src="https://www.chartjs.org/dist/2.8.0/Chart.min.js"></script>
<div class="card">
    <div class="card-body">
        <h5 class="text-center mb-3">Кол-во событий в системе за 24 часа</h5>
        <div class="chart mb-3"></div>

        <ul>
            <li>Общая статистика по системе (кол-во компаний, платных/бесплатных, пользователей, задач, комментариев и тд)</li>
            <li>Отладка - учет ошибок php и sql</li>
            <li>Добавление статей на сайт</li>
            <li>Управление тарифами и лимитами</li>
            <li>Управление ачивками</li>
        </ul>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <h5 class="text-center mb-3">Статьи</h5>
        <div class="articles-list">
            <?php foreach ($articlesList as $article): ?>
            <div class="article mb-2" data-article-id="<?=$article['article_id']?>">
                <p class="article-name font-weight-bold"><?=$article['article_name']?></p>
                <p class="article-category"><?=$article['category']?></p>
                <p class="article-date"><?= date('Y-m-d', $article['publish_date']); ?></p>
                <p class="article-url font-italic"><?=$article['url']?></p>
                <div class="article-description text-muted"><?=$article['description']?></div>
                <button class="btn btn-outline-primary show-article-text btn-sm">Показать/скрыть полный текст</button>
                <button class="btn btn-outline-warning edit-article btn-sm">Редактировать</button>
                <div class="article-text mt-3 d-none">
                    <?=$article['article_text']?>
                </div>
                <button class="btn btn-outline-primary show-article-text article-text btn-sm d-none">Показать/скрыть полный текст</button>
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
                <button id="sendArticle" class="btn btn-primary">Добавить</button>
            </form>
        </div>
    </div>
</div>
<div class="card mt-3">
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
                <textarea class="form-control mb-1" id="mailHeader" rows="6"><?php include $emailTemplatesDir . 'content-header.php'; ?></textarea>
                <button class="form-control btn btn-outline-warning">Сохранить</button>
            </div>
            <div class="mail-edit">
                <label for="mailBody">Body</label>
                <input id="bodyFileName" class="body-filename" value="" hidden>
                <input class="form-control mb-1 body-filename" value="" disabled>
                <textarea class="form-control mb-1" id="mailBody"rows="8"></textarea>
                <button class="form-control btn btn-outline-warning">Сохранить</button>
            </div>
            <div class="mail-edit">
                <label for="mailFooter">content-footer.php</label>
                <textarea class="form-control mb-1" id="mailFooter" rows="6"><?php include $emailTemplatesDir . 'content-footer.php'; ?></textarea>
                <button class="form-control btn btn-outline-warning">Сохранить</button>
            </div>


        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.edit-mail-body').on('click', function () {
           $('#mailBody').val($(this).closest('.mail-template').find('.mail-body').html());
           $('.body-filename').val($(this).closest('.mail-template').find('.mail-template-name').text() );
           $('#bodyFileName').val($(this).closest('.mail-template').find('.mail-template-name').text() );
        });


        $('.edit-article').on('click', function (e) {
            e.preventDefault();
           var $parentDiv = $(this).closest('.article');
           $('#articleId').val($parentDiv.data('article-id'));
           $('#articleTitle').val($parentDiv.find('.article-name').text().trim());
           $('#articleUrl').val($parentDiv.find('.article-url').text().trim());
           $('#articleCategory').val($parentDiv.find('.article-category').text().trim());
           $('#articleDescription').val($parentDiv.find('.article-description').html().trim());
           $('#articleText').val($parentDiv.find('.article-text').html().trim());
           $('#articleDate').val($parentDiv.find('.article-date').text().trim());
        });

        $('.show-article-text').on('click', function (e) {
            e.preventDefault();
           var text = $(this).closest('.article').find('.article-text');
           if (text.hasClass('d-none')) {
               text.removeClass('d-none');
           } else {
               text.addClass('d-none')
           }
        });
        $('#sendArticle').on('click', function (e) {
            e.preventDefault();
            if ($('#articleId').val() == '0') {
                var module = 'addArticle'
            } else {
                var module = 'updateArticle'
            }
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                data: {
                    ajax: 'godmode',
                    module: module,
                    articleId: $('#articleId').val(),
                    articleTitle: $('#articleTitle').val(),
                    articleUrl: $('#articleUrl').val(),
                    articleCategory: $('#articleCategory').val(),
                    articleDescription: $('#articleDescription').val(),
                    articleText: $('#articleText').val(),
                    articleDate: $('#articleDate').val(),
                },
                success: function (response) {
                    if (response === '1') {
                        $('#addArticle')[0].reset();
                    } else {
                        console.log('Error');
                        console.log(response);
                    }
                }
            });
        })
    });
    function createConfig(details, data) {
        var hours = [];
        for (var i = 0; i < 24; i++) {
            hours.push(i + ':00');
        }
        return {
            type: 'line',
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
                            beginAtZero: true
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
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>