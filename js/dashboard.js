document.addEventListener('DOMContentLoaded', function () {
    // Animar entrada dos cartões de estatísticas
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });

    // ---------- GRÁFICOS ----------

    const { fossilData, contactData } = window.dashboardData;

    const fossilLabels = fossilData.map(item => {
        const [year, month] = item.month.split('-');
        return `${month}/${year}`;
    });
    const fossilCounts = fossilData.map(item => item.count);

    const contactLabels = contactData.map(item => {
        const [year, month] = item.month.split('-');
        return `${month}/${year}`;
    });
    const contactCounts = contactData.map(item => item.count);

    // Fósseis por mês
    const fossilCtx = document.getElementById('fossilChart').getContext('2d');
    new Chart(fossilCtx, {
        type: 'line',
        data: {
            labels: fossilLabels,
            datasets: [{
                label: 'Fósseis Inseridos',
                data: fossilCounts,
                backgroundColor: 'rgba(194, 144, 109, 0.2)',
                borderColor: 'rgba(194, 144, 109, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(194, 144, 109, 1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#5c4938',
                        font: { size: 13 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#6c757d' },
                    grid: { color: '#e0e0e0' }
                },
                x: {
                    ticks: { color: '#6c757d' },
                    grid: { display: false }
                }
            }
        }
    });

    // Pedidos de contacto por mês
    const contactCtx = document.getElementById('contactChart').getContext('2d');
    new Chart(contactCtx, {
        type: 'line',
        data: {
            labels: contactLabels,
            datasets: [{
                label: 'Pedidos de Contacto',
                data: contactCounts,
                backgroundColor: 'rgba(93, 74, 56, 0.2)',
                borderColor: 'rgba(93, 74, 56, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(93, 74, 56, 1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#5c4938',
                        font: { size: 13 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#6c757d' },
                    grid: { color: '#e0e0e0' }
                },
                x: {
                    ticks: { color: '#6c757d' },
                    grid: { display: false }
                }
            }
        }
    });
});
