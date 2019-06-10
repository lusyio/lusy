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

<script>
    function createConfig(details, data) {
        return {
            type: 'line',
            data: {
                labels: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00'],
                datasets: [{
                    steppedLine: details.steppedLine,
                    data: [12, 19, 3, 5, 2, 3],
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