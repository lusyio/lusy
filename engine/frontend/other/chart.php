<script src="https://www.chartjs.org/dist/2.8.0/Chart.min.js"></script>
<div class="row">
    <div class="chart-container col-5">
        <canvas id="myChart"></canvas>
    </div>
    <div class="col-7">
        <div>
            <i class="fas fa-crown text-warning"></i>
            <img src="/upload/avatar/2/2.png" class="avatar-added mr-1">
            <span>B0$$</span>
            <hr class="mt-2">
        </div>
        <div>
            <i class="fas fa-hashtag text-secondary">  1</i>
            <img src="/upload/avatar/2/2.png" class="avatar-added mr-1">
            <span>Коля</span>
        </div>
    </div>
</div>


<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',

        data: {
            datasets: [{
                backgroundColor: ['rgb(255, 0, 0, 0.5)', 'rgb(0, 255, 0, 0.5)', 'rgb(0, 0, 255, 0.5)'],
                data: [10, 20, 30]
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Петя',
                'Вася',
                'Коля'
            ]
        },

        // Configuration options go here
        options: {
            cutoutPercentage: 75,
            responsive: true,
            legend: {
                display: false,
                labels: {
                    display: false
                }
            }

        }
    });

    // Кастомная легенда в чартжс
    // var configd = {
    //     type: 'line',
    //     data: {
    //         labels: ["Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
    //         datasets: [
    //             {
    //                 label: "Компания",
    //                 backgroundColor: 'rgba(0, 0, 0,0)',
    //                 borderColor: '#7cb4f0',
    //                 data: [20, 30, 35, 40, 45, 47, 49],
    //             },
    //
    //             {
    //                 label: "Петя",
    //                 backgroundColor: 'rgba(0, 0, 0,0)',
    //                 borderColor: '#95ed6c',
    //                 data: [0, 0, 5, 15, 10, 1, 5],
    //             },
    //             {
    //                 label: "Коля",
    //                 backgroundColor: 'rgba(0, 0, 0,0)',
    //                 borderColor: '#434348',
    //                 data: [1, 2, 10, 5, 10, 13, 15],
    //             },
    //             {
    //                 label: "Lesha",
    //                 backgroundColor: 'rgba(0, 0, 0,0)',
    //                 borderColor: '#d44a5f',
    //                 data: [11, 23, 12, 5, 10, 13, 15],
    //             },
    //
    //         ]
    //     },
    //
    //
    //     options: {
    //         hover: {
    //             mode: 'nearest',
    //             intersect: true
    //         },
    //         tooltips: {
    //             mode: 'index',
    //             intersect: false
    //         },
    //         responsive: true,
    //         legend: {
    //             display: false
    //         },
    //         legendCallback: function (chart) {
    //
    //             var text = [];
    //             text.push('<ul class="' + chart.id + '-legend">');
    //             for (var i = 0; i < chart.data.datasets.length; i++) {
    //
    //                 text.push('<li><img src="/upload/avatar-1.jpg" class="avatar-added mr-1"><span id="legend-' + i + '-item" style=""   onclick="updateDataset(event, ' + '\'' + i + '\'' + ')">');
    //
    //                 if (chart.data.datasets[i].label) {
    //                     text.push(chart.data.datasets[i].label);
    //                 }
    //                 text.push('</span></li>');
    //             }
    //             text.push('</ul>');
    //             return text.join("");
    //         },
    //         elements: {
    //             line: {
    //                 tension: 0,
    //             }
    //         },
    //
    //     }
    // };
    //
    // var ctxd = document.getElementById('myChart').getContext('2d');
    //
    // window.myLineChart = new Chart(ctxd, configd);
    // $("#legend").html(window.myLineChart.generateLegend());
    //
    // var updateDataset = function (e, datasetIndex) {
    //     var ci = e.view.myLineChart;
    //     var meta = ci.getDatasetMeta(0);
    //     var result = (meta.data[datasetIndex].hidden == true) ? false : true;
    //     if (result == true) {
    //         meta.data[datasetIndex].hidden = true;
    //         $('#' + e.path[0].id).css("text-decoration", "line-through");
    //     } else {
    //         $('#' + e.path[0].id).css("text-decoration", "");
    //         meta.data[datasetIndex].hidden = false;
    //
    //     }
    //     ci.update();
    // };


</script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
