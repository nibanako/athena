var avgChart;
var hddChart;
var ramChart

$(function() {
    var avgctx = $("#avg");
    avgChart = new Chart(avgctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: 'rgb(91, 192, 222)',
                borderColor: 'rgb(91, 192, 222)',
                fill: false
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var hddctx = $("#hdd");
    hddChart = new Chart(hddctx, {
        type: 'pie',
        data: {
            labels: ["Usado", "Libre"],
            datasets: [{
                data: [0, 100],
                backgroundColor: [
                    'rgb(91, 192, 222)',
                    'rgb(128, 128, 128)'
                ],
            }]
        },
        options: {
            legend: {
                display: false
            },
        }
    });

    var ramctx = $("#ram");
    ramChart = new Chart(ramctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: 'rgb(91, 192, 222)',
                borderColor: 'rgb(91, 192, 222)',
                fill: false
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: 100
                    }
                }]
            }
        }
    });

    setInterval(
        function() {
            $.ajax({
                'url': '/servers/avg/' + $('#serverId').val(),
                success: function(data) {
                    avgChart.data.labels.push(data.avg.time);
                    avgChart.data.datasets.forEach((dataset) => {
                        dataset.data.push(data.avg.mark);
                    });
                    avgChart.update();
                }
            });

            $.ajax({
                'url': '/servers/ramUsage/' + $('#serverId').val(),
                success: function(data) {
                    ramChart.data.labels.push(data.ram.time);
                    ramChart.data.datasets.forEach((dataset) => {
                        dataset.data.push(data.ram.mark);
                    });
                    ramChart.update();
                }
            });

            $.ajax({
                'url': '/servers/hddUsage/' + $('#serverId').val(),
                success: function(data) {
                    hddChart.data.datasets.forEach((dataset) => {
                        dataset.data = new Array(data.hddUse.used, data.hddUse.remain);
                    });
                    hddChart.update();
                }
            });
        },
        5000
    );
});