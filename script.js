// Example chart setup
const productsCtx = document.getElementById('productsChart').getContext('2d');
new Chart(productsCtx, {
  type: 'line',
  data: {
    labels: ['1W', '2W', '3W', '4W'],
    datasets: [{
      label: 'Products Listed',
      data: [12, 15, 14, 15],
      borderColor: '#15931d',
      tension: 0.4,
      fill: false
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});

const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
  type: 'bar',
  data: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [{
      label: 'Active Orders',
      data: [7, 9, 8, 10, 7, 8, 9],
      backgroundColor: '#15931d'
    }]
  },
  options: { responsive: true, maintainAspectRatio: false }
});

const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'doughnut',
  data: {
    labels: ['Last 7 Days', 'Last 14 Days', 'Last 30 Days'],
    datasets: [{
      data: [8000, 15000, 25000],
      backgroundColor: ['#15931d', '#F9A825', '#6D4C41']
    }]
  },
  options: { responsive: true, maintainAspectRatio: false, cutout: '70%' }
});
