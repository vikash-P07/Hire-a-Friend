@extends('layouts.partner')
@section('title', 'Availability Schedule | Companion Partner')

@section('styles')
<style>
    /* Stack Availability Table */
    @media (max-width: 575.98px) {
        .c-table.align-middle, 
        .c-table.align-middle thead, 
        .c-table.align-middle tbody, 
        .c-table.align-middle tr, 
        .c-table.align-middle th, 
        .c-table.align-middle td {
            display: block !important;
            width: 100% !important;
        }
        .c-table.align-middle thead {
            display: none !important;
        }
        .c-table.align-middle tr {
            margin-bottom: 1rem;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 0.75rem !important;
            background: var(--surface-2);
        }
        .c-table.align-middle td {
            padding: 0.35rem 0 !important;
            border-bottom: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .c-table.align-middle td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .c-table.align-middle td input[type="time"] {
            max-width: 150px !important;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        .card-glass-static button[type="submit"] {
            width: 100% !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Availability Schedule</h1>
    <p class="page-subtitle">Configure your weekly working schedule and vacation status settings</p>
</div>

<div class="row g-4">
    <!-- LEFT: SCHEDULE BUILDER -->
    <div class="col-lg-8">
        <div class="card-glass-static p-4">
            <h5 class="fw-bold mb-4" style="color:var(--text-primary);"><i class="bi bi-calendar-range me-2" style="color:var(--brand-purple);"></i>Weekly Hours</h5>
            
            <form action="{{ route('partner.availability.update') }}" method="POST">
                @csrf
                <div class="table-responsive mb-4">
                    <table class="c-table align-middle">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Status</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daysOfWeek as $day)
                                @php
                                    $avail = $availabilities->get($day);
                                    $isAvail = $avail ? $avail->is_available : false;
                                    $start = $avail ? $avail->start_time : '09:00';
                                    $end = $avail ? $avail->end_time : '17:00';
                                @endphp
                                <tr>
                                    <td data-label="Day"><strong class="text-theme-primary">{{ Carbon\Carbon::parse('next ' . $day)->format('l') }}</strong></td>
                                    <td data-label="Status">
                                        <div class="form-check form-switch mb-0">
                                            <input type="hidden" name="availabilities[{{ $day }}][is_available]" value="0">
                                            <input class="form-check-input" type="checkbox" name="availabilities[{{ $day }}][is_available]" value="1" id="avail-switch-{{ $day }}" {{ $isAvail ? 'checked' : '' }} onchange="toggleHoursRow('{{ $day }}')">
                                            <label class="form-check-label text-muted small" for="avail-switch-{{ $day }}" id="lbl-{{ $day }}">{{ $isAvail ? 'Available' : 'Unavailable' }}</label>
                                        </div>
                                    </td>
                                    <td data-label="Start Time">
                                        <input type="time" name="availabilities[{{ $day }}][start_time]" class="form-control form-control-sm" id="start-{{ $day }}" value="{{ $start }}" style="max-width: 140px;" {{ $isAvail ? '' : 'disabled' }}>
                                    </td>
                                    <td data-label="End Time">
                                        <input type="time" name="availabilities[{{ $day }}][end_time]" class="form-control form-control-sm" id="end-{{ $day }}" value="{{ $end }}" style="max-width: 140px;" {{ $isAvail ? '' : 'disabled' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn-brand px-4 py-2.5"><i class="bi bi-check-circle me-2"></i>Save Weekly Hours</button>
            </form>
        </div>
    </div>

    <!-- RIGHT: VACATION MODE -->
    <div class="col-lg-4">
        <div class="card-glass-static p-4">
            <h5 class="fw-bold mb-3" style="color:var(--text-primary);">Vacation Settings</h5>
            <p class="text-muted small">Need to take a break? Enabling Vacation Mode will immediately hide your profile listing from customer searches. Current upcoming accepted bookings will not be cancelled.</p>
            
            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between" style="background: var(--surface-2); border-color: var(--border)!important;">
                <div>
                    <div class="fw-bold" style="font-size:0.88rem; color:var(--text-primary);">Vacation Status</div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">
                        @if($profile->vacation_mode)
                            <span class="text-danger fw-bold">Active (Listing Hidden)</span>
                        @else
                            <span class="text-success fw-bold">Inactive (Listing Active)</span>
                        @endif
                    </div>
                </div>
                <form action="{{ route('partner.availability.vacation') }}" method="POST" id="vacationForm">
                    @csrf
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="vacationSwitch" {{ $profile->vacation_mode ? 'checked' : '' }} onchange="document.getElementById('vacationForm').submit()">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleHoursRow(day) {
        const check = document.getElementById('avail-switch-' + day).checked;
        document.getElementById('start-' + day).disabled = !check;
        document.getElementById('end-' + day).disabled = !check;
        document.getElementById('lbl-' + day).textContent = check ? 'Available' : 'Unavailable';
    }
</script>
@endsection
