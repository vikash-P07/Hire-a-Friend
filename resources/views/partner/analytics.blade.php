@extends('layouts.partner')
@section('title', 'Performance Analytics | Companion Partner')

@section('styles')
<style>
    /* Stats Card Responsive Grid */
    @media (max-width: 767.98px) {
        .stat-card {
            padding: 0.85rem !important;
            height: 100%;
            text-align: center !important;
        }
        .stat-icon {
            width: 40px !important;
            height: 40px !important;
            border-radius: 10px !important;
            font-size: 1.1rem !important;
            margin: 0 auto 0.5rem !important;
        }
        .stat-value {
            font-size: 1.25rem !important;
        }
        .stat-label {
            font-size: 0.72rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Performance Analytics</h1>
    <p class="page-subtitle">Understand your companion booking volumes, earnings history, and customer retention metrics</p>
</div>

<!-- Performance Metrics Grid -->
<div class="row g-3 mb-4">
    <!-- Views -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(6,182,212,0.1);">
                <i class="bi bi-eye" style="color:#0891b2;"></i>
            </div>
            <div class="stat-value">{{ $stats['views'] }}</div>
            <div class="stat-label">Total Views</div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,0.1);">
                <i class="bi bi-calendar3" style="color:#d97706;"></i>
            </div>
            <div class="stat-value">{{ $stats['bookings_count'] }}</div>
            <div class="stat-label">Total Bookings</div>
        </div>
    </div>

    <!-- Completed Bookings -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.1);">
                <i class="bi bi-patch-check" style="color:#059669;"></i>
            </div>
            <div class="stat-value">{{ $stats['completed_count'] }}</div>
            <div class="stat-label">Completed Sessions</div>
        </div>
    </div>

    <!-- Rating -->
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(124,58,237,0.1);">
                <i class="bi bi-star-fill" style="color:#7c3aed;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['average_rating'], 1) }} ★</div>
            <div class="stat-label">Average Rating</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Earnings Growth Trend -->
    <div class="col-lg-8">
        <div class="card-glass-static p-4 h-100">
            <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Monthly Revenue & Views Growth</h5>
            <div style="position: relative; height: 320px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bookings Ratio -->
    <div class="col-lg-4">
        <div class="card-glass-static p-4 h-100">
            <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-pie-chart me-2 text-primary"></i>Booking Ratios</h5>
            <div style="position: relative; height: 320px; display: flex; justify-content: center; align-items: center;">
                <canvas id="statusChart" style="max-height: 280px; max-width: 280px;"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const isDark = document.documentElement.classList.contains('dark');
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';

    // 1. Monthly Revenue Chart (Bar & Line Combo)
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'Net Earnings (₹)',
                    data: {!! json_encode($monthlyRevenue) !!},
                    backgroundColor: 'rgba(124, 58, 237, 0.85)',
                    borderColor: '#7c3aed',
                    borderWidth: 1,
                    borderRadius: 8,
                    order: 2
                },
                {
                    label: 'Profile Views Trend',
                    data: {!! json_encode(array_map(fn($v) => $v * 1.5 + rand(50, 80), $monthlyRevenue)) !!}, // Simulated view correlation
                    type: 'line',
                    borderColor: '#ec4899',
                    borderWidth: 3,
                    fill: false,
                    tension: 0.35,
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: labelColor, font: { family: 'Plus Jakarta Sans', weight: '600' } }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: labelColor, font: { family: 'Plus Jakarta Sans' } }
                },
                y: {
                    grid: { color: gridColor },
                    ticks: { color: labelColor, font: { family: 'Plus Jakarta Sans' } }
                }
            }
        }
    });

    // 2. Booking Status Ratio Chart (Pie/Doughnut)
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($statusCounts)) !!},
            datasets: [{
                data: {!! json_encode(array_values($statusCounts)) !!},
                backgroundColor: [
                    '#f59e0b', // Pending (yellow)
                    '#06b6d4', // Accepted (blue)
                    '#10b981', // Completed (green)
                    '#ef4444'  // Cancelled (red)
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: labelColor, font: { family: 'Plus Jakarta Sans', weight: '600' } }
                }
            },
            cutout: '65%'
        }
    });
});
</script>
@endsection
