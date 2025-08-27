// public/js/admin_charts.js

document.addEventListener('DOMContentLoaded', () => {
	const data = window.adminChartData;
	if (!data) return;

	Chart.defaults.font.family = "'Inter', sans-serif";
	Chart.defaults.color = "#4B5563";
	Chart.defaults.plugins.legend.labels.font = {
		family: "'Inter', sans-serif",
		size:   12
	};
	Chart.defaults.plugins.legend.labels.color = "#6B7280";
	Chart.defaults.plugins.tooltip.backgroundColor = "#FFFFFF";
	Chart.defaults.plugins.tooltip.borderColor       = "#E5E7EB";
	Chart.defaults.plugins.tooltip.borderWidth       = 1;
	Chart.defaults.plugins.tooltip.titleFont = {
		family: "'Inter', sans-serif",
		size:   14,
		weight: '600'
	};
	Chart.defaults.plugins.tooltip.titleColor = "#1F2937";
	Chart.defaults.plugins.tooltip.bodyFont  = {
		family: "'Inter', sans-serif",
		size:   12
	};
	Chart.defaults.plugins.tooltip.bodyColor = "#4B5563";

	(function renderUsageChart() {
		const ctx = document.getElementById('usageChart').getContext('2d');

		const combined = data.usageLabels
			.map((label, i) => ({ label, value: data.usageCounts[i] }))
			.sort((a, b) => b.value - a.value);

		new Chart(ctx, {
			type: 'bar',
			data: {
				labels: combined.map(x => x.label),
				datasets: [{
					data: combined.map(x => x.value),
					backgroundColor: '#2563EB',
					borderRadius: 4,
					barPercentage: 0.6,
					categoryPercentage: 0.8
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					x: {
						ticks: { color: "#4B5563" },
						grid:  { display: false }
					},
					y: {
						beginAtZero: true,
						ticks: {
							color: "#4B5563",
							stepSize: 1,                // ← force integer steps
							callback: v => v.toString() // ← strip any decimal places
						},
						grid:  { color: "#E5E7EB" }
					}
				},
				plugins: {
					legend: { display: false },
					tooltip: {
						titleFont: { family: "'Inter', sans-serif", size: 14 },
						bodyFont:  { family: "'Inter', sans-serif", size: 13 }
					}
				}
			}
		});
	})();

	(function renderTrendChart() {
		const ctx = document.getElementById('trendChart').getContext('2d');

		new Chart(ctx, {
			type: 'line',
			data: {
				labels: data.trendLabels,
				datasets: [{
					data: data.trendCounts,
					borderColor: '#2563EB',
					backgroundColor: 'rgba(37,99,235,0.1)',
					tension: 0.3,
					pointRadius: 4,
					pointBackgroundColor: '#2563EB'
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					x: {
						ticks: { color: "#4B5563" },
						grid:  { display: false }
					},
					y: {
						beginAtZero: true,
						ticks: {
							color: "#4B5563",
							stepSize: 1,
							callback: v => v.toString()
						},
						grid:  { color: "#E5E7EB" }
					}
				},
				plugins: {
					legend: { display: false },
					tooltip: {
						titleFont: { family: "'Inter', sans-serif", size: 14 },
						bodyFont:  { family: "'Inter', sans-serif", size: 13 }
					}
				}
			}
		});
	})();
});
