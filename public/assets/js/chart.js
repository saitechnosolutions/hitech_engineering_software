  // Get canvas element
    let canvas = document.getElementById('twoWeekChart');

    // Read dataset values from Blade
    let lastWeek = JSON.parse(canvas.dataset.lastweek);
    let currentWeek = JSON.parse(canvas.dataset.currentweek);

    // Get context
    let ctx = canvas.getContext("2d");

    // Create chart
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
                {
                    label: 'Last Week',
                    data: lastWeek,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Current Week',
                    data: currentWeek,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
