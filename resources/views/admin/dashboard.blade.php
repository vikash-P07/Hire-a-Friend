@extends('layouts.admin')
@section('title', 'Administrative Overview - Super Admin Console')

@section('styles')
<style>
    /* Premium Dashboard Styles */
    .dashboard-header-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 140px;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: rgba(124, 58, 237, 0.3);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: transparent;
        transition: var(--transition);
    }

    .stat-card:hover::before {
        background: var(--primary-color);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-card-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: var(--text-muted);
    }

    .stat-card-icon-box {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: var(--transition);
    }

    .stat-card:hover .stat-card-icon-box {
        transform: scale(1.08);
    }

    .stat-card-value {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.1;
        font-family: 'Jost', sans-serif;
    }

    .stat-card-trend {
        font-size: 0.78rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }

    .trend-up {
        color: var(--success-color);
    }

    .trend-down {
        color: var(--danger-color);
    }

    /* Grid & Cards Section */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1199.98px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    .premium-panel {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 1.75rem;
        transition: var(--transition);
    }

    .premium-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--card-border);
        padding-bottom: 1rem;
    }

    .premium-panel-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }



    /* Live Feed Item styles */
    .live-feed-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .live-feed-item {
        display: flex;
        gap: 1rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .live-feed-item::after {
        content: '';
        position: absolute;
        left: 20px;
        top: 40px;
        width: 2px;
        height: calc(100% - 30px);
        background: var(--card-border);
    }

    .live-feed-item:last-child::after {
        display: none;
    }

    .live-feed-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--badge-bg-subtle);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
        z-index: 2;
    }

    .live-feed-content {
        flex-grow: 1;
    }

    .live-feed-title {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
        margin-bottom: 0.15rem;
    }

    .live-feed-desc {
        color: var(--text-muted);
        font-size: 0.82rem;
        margin-bottom: 0.25rem;
    }

    .live-feed-time {
        font-size: 0.72rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>
@endsection

@section('content')

<!-- Header Panel -->
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h2 class="fw-bold mb-1" style="font-family: 'Jost', sans-serif;">Super Admin Dashboard</h2>
        <p class="text-muted mb-0">Unified operations dashboard, analytics center, and platform configuration dashboard.</p>
    </div>
</div>


<!-- statistics Cards -->
<div class="dashboard-header-stats">
    <!-- Card 1: Users -->
    <div class="stat-card reveal-up">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Users</span>
            <div class="stat-card-icon-box bg-primary-subtle text-primary">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div>
            <div class="stat-card-value counter-animate">{{ $stats['total_users'] }}</div>
            <div class="stat-card-trend trend-up">
                <i class="bi bi-graph-up-arrow"></i>
                <span>+12.4% this month</span>
            </div>
        </div>
    </div>
    <!-- Card 2: Partners -->
    <div class="stat-card reveal-up">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Partners</span>
            <div class="stat-card-icon-box bg-info-subtle text-info">
                <i class="bi bi-person-badge-fill"></i>
            </div>
        </div>
        <div>
            <div class="stat-card-value counter-animate">{{ $stats['total_partners'] }}</div>
            <div class="stat-card-trend trend-up">
                <i class="bi bi-graph-up-arrow"></i>
                <span>+8.2% this month</span>
            </div>
        </div>
    </div>
    <!-- Card 3: Bookings -->
    <div class="stat-card reveal-up">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Bookings</span>
            <div class="stat-card-icon-box bg-warning-subtle text-warning">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
        </div>
        <div>
            <div class="stat-card-value counter-animate">{{ $stats['total_bookings'] }}</div>
            <div class="stat-card-trend trend-up">
                <i class="bi bi-graph-up-arrow"></i>
                <span>+18.7% this month</span>
            </div>
        </div>
    </div>
    <!-- Card 4: Revenue -->
    <div class="stat-card reveal-up">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Revenue</span>
            <div class="stat-card-icon-box bg-success-subtle text-success">
                <i class="bi bi-currency-rupee"></i>
            </div>
        </div>
        <div>
            <div class="stat-card-value counter-animate" data-prefix="₹">{{ $stats['total_revenue'] }}</div>
            <div class="stat-card-trend trend-up">
                <i class="bi bi-graph-up-arrow"></i>
                <span>+24.1% this month</span>
            </div>
        </div>
    </div>
    <!-- Card 5: Withdrawals -->
    <div class="stat-card reveal-up">
        <div class="stat-card-header">
            <span class="stat-card-title">Pending Withdrawals</span>
            <div class="stat-card-icon-box bg-danger-subtle text-danger">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
        <div>
            <div class="stat-card-value counter-animate" data-prefix="₹">{{ $stats['pending_withdrawals'] }}</div>
            <div class="stat-card-trend trend-down">
                <i class="bi bi-graph-down-arrow"></i>
                <span>-5.3% this week</span>
            </div>
        </div>
    </div>
    <!-- Card 6: Active Cities -->
    <div class="stat-card reveal-up">
        <div class="stat-card-header">
            <span class="stat-card-title">Active Cities</span>
            <div class="stat-card-icon-box bg-secondary-subtle" style="color: var(--secondary-color);">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
        </div>
        <div>
            <div class="stat-card-value counter-animate">{{ $stats['active_cities'] }}</div>
            <div class="stat-card-trend trend-up">
                <i class="bi bi-graph-up-arrow"></i>
                <span>+2 new locations</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Line Chart: Revenue -->
    <div class="col-xl-8">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <span class="premium-panel-title"><i class="bi bi-graph-up"></i> Revenue Analytics (INR)</span>
                <span class="text-muted small">Updated 10m ago</span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="superRevenueChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Doughnut Chart: Booking Distribution -->
    <div class="col-xl-4">
        <div class="premium-panel h-100">
            <div class="premium-panel-header">
                <span class="premium-panel-title"><i class="bi bi-pie-chart"></i> Booking Analytics</span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="superBookingChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Line Chart: User Growth -->
    <div class="col-xl-6">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <span class="premium-panel-title"><i class="bi bi-person-up"></i> User Growth Analytics</span>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="superUserGrowthChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Bar Chart: Partner Registration -->
    <div class="col-xl-6">
        <div class="premium-panel">
            <div class="premium-panel-header">
                <span class="premium-panel-title"><i class="bi bi-shield-check"></i> Partner Growth Trends</span>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="superPartnerGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables & Live Feed Grid -->
<div class="dashboard-grid" id="reports">
    
    <!-- Table Container -->
    <div class="premium-panel">
        <div class="premium-panel-header">
            <span class="premium-panel-title"><i class="bi bi-clock-history"></i> Latest Bookings</span>
            <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-link text-primary p-0 fw-bold" style="font-size: 0.85rem; text-decoration: none;">View All</a>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle table-hover border-0">
                <thead class="table-light">
                    <tr>
                        <th class="border-0">ID</th>
                        <th class="border-0">Customer</th>
                        <th class="border-0">Companion</th>
                        <th class="border-0">Booking Date</th>
                        <th class="border-0">Amount</th>
                        <th class="border-0">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.bookings') }}'">
                            <td class="text-muted fw-semibold">#{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-img-placeholder" style="width: 28px; height: 28px; font-size: 0.72rem;">
                                        {{ strtoupper(substr($booking->customer->name, 0, 1)) }}
                                    </div>
                                    <span class="text-theme-primary fw-bold" style="font-size: 0.88rem;">{{ $booking->customer->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-theme-primary" style="font-size: 0.88rem;">{{ $booking->partner->name }}</span>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td><span class="fw-bold text-theme-primary" style="font-size: 0.88rem;">₹{{ number_format($booking->total_amount) }}</span></td>
                            <td>
                                <span class="badge {{ $booking->status === 'completed' ? 'bg-success-subtle text-success' : ($booking->status === 'cancelled' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }} rounded-pill" style="font-size: 0.72rem; padding: 0.35em 0.75em;">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small">No bookings registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Live Activity Feed Column -->
    <div class="premium-panel">
        <div class="premium-panel-header">
            <span class="premium-panel-title"><i class="bi bi-activity"></i> Live Activity Feed</span>
        </div>

        <div class="live-feed-list">
            @forelse($liveActivity as $log)
                <div class="live-feed-item">
                    <div class="live-feed-avatar">
                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="live-feed-content">
                        <div class="live-feed-title">{{ $log->action }}</div>
                        <div class="live-feed-desc">{{ $log->description }}</div>
                        <div class="live-feed-time">
                            <i class="bi bi-clock"></i>
                            <span>{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-muted small py-4 text-center">No system actions logged.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Define colors matching Theme
        let isDarkMode = document.documentElement.classList.contains('dark');
        
        let gridColor = isDarkMode ? '#1f293d' : '#e2e8f0';
        let labelColor = isDarkMode ? '#94a3b8' : '#64748b';

        // 1. Revenue line Chart
        const revCtx = document.getElementById('superRevenueChart').getContext('2d');
        let revGradient = revCtx.createLinearGradient(0, 0, 0, 300);
        revGradient.addColorStop(0, 'rgba(124, 58, 237, 0.35)');
        revGradient.addColorStop(1, 'rgba(124, 58, 237, 0)');

        const revenueChart = new Chart(revCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Monthly revenue (INR)',
                    data: {!! json_encode($monthlyRevenue) !!},
                    borderColor: '#7c3aed',
                    backgroundColor: revGradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#7c3aed',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: gridColor },
                        ticks: { color: labelColor }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: labelColor }
                    }
                }
            }
        });

        // 2. Booking Distribution Chart
        const bookCtx = document.getElementById('superBookingChart').getContext('2d');
        const bookingChart = new Chart(bookCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($bookingStatusCounts)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($bookingStatusCounts)) !!},
                    backgroundColor: ['#f59e0b', '#7c3aed', '#10b981', '#ef4444'],
                    borderWidth: isDarkMode ? 3 : 1,
                    borderColor: isDarkMode ? '#111625' : '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            boxWidth: 12,
                            color: labelColor
                        } 
                    } 
                }
            }
        });

        // 3. User Growth Chart
        const userCtx = document.getElementById('superUserGrowthChart').getContext('2d');
        let userGradient = userCtx.createLinearGradient(0, 0, 0, 250);
        userGradient.addColorStop(0, 'rgba(6, 182, 212, 0.35)');
        userGradient.addColorStop(1, 'rgba(6, 182, 212, 0)');

        const userGrowthChart = new Chart(userCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Total Customers',
                    data: {!! json_encode($userGrowth) !!},
                    borderColor: '#06b6d4',
                    backgroundColor: userGradient,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#06b6d4'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: gridColor },
                        ticks: { color: labelColor }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: labelColor }
                    }
                }
            }
        });

        // 4. Partner Growth Trends Chart
        const partnerCtx = document.getElementById('superPartnerGrowthChart').getContext('2d');
        const partnerGrowthChart = new Chart(partnerCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Active Partners Added',
                    data: [5, 9, 14, 18, 25, {{ $stats['total_partners'] > 20 ? $stats['total_partners'] : 28 }}],
                    backgroundColor: '#ec4899',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: gridColor },
                        ticks: { color: labelColor }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: labelColor }
                    }
                }
            }
        });

        // Theme Toggle Listener to dynamically swap chart grid/label colors
        const darkModeToggle = document.getElementById('darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function() {
                setTimeout(() => {
                    let darkNow = document.documentElement.classList.contains('dark');
                    let newGridColor = darkNow ? '#1f293d' : '#e2e8f0';
                    let newLabelColor = darkNow ? '#94a3b8' : '#64748b';

                    [revenueChart, userGrowthChart, partnerGrowthChart].forEach(c => {
                        c.options.scales.y.grid.color = newGridColor;
                        c.options.scales.y.ticks.color = newLabelColor;
                        c.options.scales.x.ticks.color = newLabelColor;
                        c.update();
                    });

                    bookingChart.options.plugins.legend.labels.color = newLabelColor;
                    bookingChart.data.datasets[0].borderColor = darkNow ? '#111625' : '#ffffff';
                    bookingChart.data.datasets[0].borderWidth = darkNow ? 3 : 1;
                    bookingChart.update();
                }, 50);
            });
        }
    });


</script>
@endsection
