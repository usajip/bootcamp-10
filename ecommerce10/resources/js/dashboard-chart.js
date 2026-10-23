import Chart from 'chart.js/auto';

const canvas = document.getElementById('orderChart');
const dataElement = document.getElementById('order-chart-data');

if (canvas && dataElement) {
    const chartData = JSON.parse(dataElement.textContent);

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Orders',
                    data: chartData.data.orders,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointBorderColor: '#fff',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Revenue',
                    data: chartData.data.revenue,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    fill: true,
                    tension: 0.4,
                }
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${context.parsed.y}`,
                    },
                },
            },
            scales: {
                x: {
                    display: true,
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
}

const statusCanvas = document.getElementById('orderStatusChart');
const statusDataElement = document.getElementById('order-status-chart-data');

if (statusCanvas && statusDataElement) {
    const statusChartData = JSON.parse(statusDataElement.textContent);

    new Chart(statusCanvas, {
        type: 'doughnut',
        data: {
            labels: statusChartData.labels,
            datasets: [
                {
                    label: 'Order Status',
                    data: statusChartData.data,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
        }
    });
}
