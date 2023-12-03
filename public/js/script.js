document.addEventListener("DOMContentLoaded", function() {
    var ctx = document.getElementById("chart-tache").getContext('2d');

    var data = {
        labels: ['Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.', 'Dim.'],
        datasets: [{
            label: 'Tâches de la semaine',
            data: [0, 0, 0, 0, 1, 1, 2],
            backgroundColor: 'rgba(14, 115, 153, 0.7)',
            borderColor: '#0E7399B2',
            borderWidth: 2,
            tension: 0.4,
        }]
    };

    var chartOptions = {
        scales: {
            y: {
                beginAtZero: true,
                precision: 0 // Définit le nombre de décimales à afficher (0 pour des valeurs entières)
            }
        }
    };

    var myChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: chartOptions
    });
});
