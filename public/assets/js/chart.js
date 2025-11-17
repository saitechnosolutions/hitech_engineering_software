let chartElement = document.getElementById('myChart');

let months = JSON.parse(chartElement.dataset.months);
let sales = JSON.parse(chartElement.dataset.sales)

var myChart = new Chart(chartElement, {
    type: 'pie', // line, pie, doughnut, radar etc
    data: {
        labels: months,
        datasets: [{
            label: 'Monthly Sales',
            data: sales,
            borderWidth: 2,
        }]
    }
});
