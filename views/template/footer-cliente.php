<script>
    // Theme toggle functionality
    document.querySelector('.toggle-theme').addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });

    // Chart.js for analytics
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Sales ($)',
                data: [3000, 2000, 5000, 4000, 6000],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    const base_url = '<?php echo RUTA_PRINCIPAL ?>';
</script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/admin/'; ?>js/custom.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/admin/'; ?>js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>