import Chart from 'chart.js/auto';

class DashboardAnalytics {
    constructor() {
        this.charts = {};
        this.init();
    }

    async init() {
        this.bindEvents();
        await this.initializeCharts();
    }

    async initializeCharts() {
        // Revenue Chart
        await this.loadRevenueChart();
        
        // Appointment Status Chart
        this.initializeAppointmentChart();
        
        // Pets Chart
        this.initializePetsChart();
        
        // Customers Chart
        await this.loadCustomersChart();
    }

    async loadRevenueChart() {
        try {
            const response = await fetch('/dashboard/analytics/revenue-data');
            const data = await response.json();
            
            const ctx = document.getElementById('revenueChart');
            if (ctx && data.labels && data.data) {
                this.charts.revenue = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Revenue',
                            data: data.data,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: this.getLineChartOptions('$')
                });
            }
        } catch (error) {
            console.error('Failed to load revenue data:', error);
        }
    }

    initializeAppointmentChart() {
        const ctx = document.getElementById('appointmentChart');
        if (ctx) {
            const statusData = JSON.parse(ctx.dataset.status || '[]');
            const labels = JSON.parse(ctx.dataset.labels || '[]');
            
            this.charts.appointment = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#198754', '#0dcaf0', '#ffc107', '#dc3545'],
                        borderWidth: 1
                    }]
                },
                options: this.getDoughnutOptions()
            });
        }
    }

    initializePetsChart() {
        const ctx = document.getElementById('petsChart');
        if (ctx) {
            const labels = JSON.parse(ctx.dataset.labels || '[]');
            const data = JSON.parse(ctx.dataset.data || '[]');
            
            this.charts.pets = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pets',
                        data: data,
                        backgroundColor: '#6f42c1',
                        borderColor: '#5a32a3',
                        borderWidth: 1
                    }]
                },
                options: this.getBarChartOptions('Pets')
            });
        }
    }

    async loadCustomersChart() {
        try {
            const response = await fetch('/dashboard/analytics/user-growth');
            const data = await response.json();
            
            const ctx = document.getElementById('customersChart');
            if (ctx && data.labels && data.data) {
                this.charts.customers = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Total Users',
                            data: data.data,
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32, 201, 151, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: this.getLineChartOptions('')
                });
            }
        } catch (error) {
            console.error('Failed to load customer data:', error);
        }
    }

    getLineChartOptions(currencyPrefix = '') {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return currencyPrefix + context.parsed.y.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return currencyPrefix + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };
    }

    getDoughnutOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((context.parsed / total) * 100);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        };
    }

    getBarChartOptions(label) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };
    }

    bindEvents() {
        // Date range dropdown
        const rangeDropdown = document.querySelector('[data-range-dropdown]');
        if (rangeDropdown) {
            rangeDropdown.addEventListener('change', (e) => {
                const range = e.target.value;
                window.location.href = `?range=${range}`;
            });
        }

        // Refresh button
        const refreshBtn = document.getElementById('refreshData');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.refreshData();
            });
        }

        // Export buttons
        document.querySelectorAll('[data-export]').forEach(button => {
            button.addEventListener('click', (e) => {
                const type = e.currentTarget.dataset.export;
                this.exportReport(type);
            });
        });
    }

    exportReport(type) {
        const url = `/dashboard/analytics/export/${type}`;
        window.open(url, '_blank');
    }

    async refreshData() {
        const refreshBtn = document.getElementById('refreshData');
        if (refreshBtn) {
            const originalHTML = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            refreshBtn.disabled = true;
            
            try {
                // Destroy existing charts
                Object.values(this.charts).forEach(chart => chart.destroy());
                this.charts = {};
                
                // Reinitialize charts
                await this.initializeCharts();
                
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } catch (error) {
                console.error('Refresh failed:', error);
                refreshBtn.innerHTML = originalHTML;
                refreshBtn.disabled = false;
            }
        }
    }

    destroy() {
        Object.values(this.charts).forEach(chart => chart.destroy());
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardAnalytics = new DashboardAnalytics();
});

// Cleanup
window.addEventListener('beforeunload', () => {
    if (window.dashboardAnalytics) {
        window.dashboardAnalytics.destroy();
    }
});