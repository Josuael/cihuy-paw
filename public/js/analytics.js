document.addEventListener("DOMContentLoaded", () => {

    /**
     * Helper untuk ambil data JSON dari element (script tag)
     */
    function getChartData() {
        const script = document.querySelector('script[data-analytics="true"]');
        if (!script) return null;

        return {
            months: JSON.parse(script.dataset.months || "[]"),
            loans: JSON.parse(script.dataset.loans || "[]"),
            payments: JSON.parse(script.dataset.payments || "[]")
        };
    }

    const data = getChartData();
    if (!data) return;

    const { months, loans, payments } = data;

    // CHART COLOR THEMES (DARK MODE)
    const colorLoans = "rgba(150,60,255,0.8)";
    const colorPayments = "rgba(57,207,255,0.9)";
    const gridColor = "rgba(255,255,255,0.1)";
    const textColor = "rgba(255,255,255,0.85)";


    // ==============================
    //  ADMIN: LOANS PER MONTH (BAR)
    // ==============================
    const loansChartEl = document.getElementById("loansChart");
    if (loansChartEl) {
        new Chart(loansChartEl, {
            type: "bar",
            data: {
                labels: months,
                datasets: [{
                    label: "Pinjaman Baru",
                    data: loans,
                    backgroundColor: colorLoans,
                    borderColor: "#c057ff",
                    borderWidth: 2,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: textColor } }
                },
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor }},
                    y: { ticks: { color: textColor }, grid: { color: gridColor }}
                }
            }
        });
    }


    // ===================================
    //  ADMIN: PAYMENTS PER MONTH (LINE)
    // ===================================
    const paymentsChartEl = document.getElementById("paymentsChart");
    if (paymentsChartEl) {
        new Chart(paymentsChartEl, {
            type: "line",
            data: {
                labels: months,
                datasets: [{
                    label: "Angsuran Masuk",
                    data: payments,
                    borderColor: colorPayments,
                    backgroundColor: "rgba(57,207,255,0.25)",
                    borderWidth: 3,
                    tension: 0.35,
                    pointRadius: 5,
                    pointBackgroundColor: "#39cfff",
                    pointBorderColor: "#39cfff"
                }]
            },
            options: {
                plugins: { legend: { labels: { color: textColor } } },
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor }},
                    y: { ticks: { color: textColor }, grid: { color: gridColor }}
                }
            }
        });
    }


    // ===================================
    //  KETUA: COMBINED PINJAMAN + PAYMENT
    // ===================================
    const combinedChartEl = document.getElementById("combinedChart");
    if (combinedChartEl) {
        new Chart(combinedChartEl, {
            type: "line",
            data: {
                labels: months,
                datasets: [
                    {
                        label: "Pinjaman Baru",
                        data: loans,
                        borderColor: "#c532ff",
                        backgroundColor: "rgba(197,50,255,0.25)",
                        borderWidth: 3,
                        tension: 0.35,
                        pointRadius: 5,
                        pointBackgroundColor: "#c532ff"
                    },
                    {
                        label: "Angsuran Masuk",
                        data: payments,
                        borderColor: "#39cfff",
                        backgroundColor: "rgba(57,207,255,0.25)",
                        borderWidth: 3,
                        tension: 0.35,
                        pointRadius: 5,
                        pointBackgroundColor: "#39cfff"
                    }
                ]
            },
            options: {
                plugins: { legend: { labels: { color: textColor } } },
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor }},
                    y: { ticks: { color: textColor }, grid: { color: gridColor }}
                }
            }
        });
    }

    function showToast(message, type = 'success') {
        let color = (type === 'success') ? '#7f35ff' : '#ff3558';

        const toast = document.createElement('div');
        toast.classList.add('neon-toast', 'p-3', 'mb-2', 'rounded');

        toast.style.borderLeftColor = color;

        toast.innerHTML = `
            <strong>${type.toUpperCase()}</strong><br>${message}
        `;

        document.getElementById('toastBox').appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 2500);
    }

});
