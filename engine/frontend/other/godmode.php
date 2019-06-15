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
        <div class="add-article">
            <form method="post" id="addArticle" class="form-group">
                <h5 class="text-center mb-3">Добавление статьи</h5>
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

<script>
    $(document).ready(function () {
        $('#sendArticle').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                data: {
                    ajax: 'godmode',
                    module: 'addArticle',
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