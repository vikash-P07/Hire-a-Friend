@extends('layouts.admin')
@section('title', 'Subscriptions Control - Admin Console')

@section('content')
<!-- Subscription Analytics Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card p-3 border shadow-sm" style="background-color: var(--card-bg);">
            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Active Subscribers</small>
            <div class="fs-4 fw-bold text-theme-primary">{{ \App\Models\Subscription::where('status','active')->where('ends_at','>',now())->count() }} Partners</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 border shadow-sm" style="background-color: var(--card-bg);">
            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Monthly Recurring Revenue (MRR)</small>
            <div class="fs-4 fw-bold text-success">₹{{ number_format(\App\Models\Subscription::where('status','active')->where('ends_at','>',now())->where('interval','monthly')->join('plans','subscriptions.plan_id','=','plans.id')->sum('plans.price')) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 border shadow-sm" style="background-color: var(--card-bg);">
            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Total Plans Available</small>
            <div class="fs-4 fw-bold text-primary">{{ $plans->count() }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Create Plans Section -->
    <div class="col-md-5">
        <div class="portal-card h-100" style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); padding: 2.25rem;">
            <h5 class="fw-bold mb-3 text-theme-primary">Create Membership Plan</h5>
            <form action="{{ route('admin.plans.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Plan Name</label>
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="e.g. Platinum Partner" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pricing Price (₹)</label>
                    <input type="number" name="price" class="form-control form-control-sm" placeholder="e.g. 1999" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Billing Interval</label>
                    <select name="interval" class="form-select form-select-sm" required>
                        <option value="monthly">Monthly Cycle</option>
                        <option value="yearly">Yearly Cycle</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100">Save Plan</button>
            </form>
        </div>
    </div>

    <!-- Active Plans List -->
    <div class="col-md-7">
        <div class="portal-card h-100" style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg); padding: 2.25rem;">
            <h5 class="fw-bold mb-3 text-theme-primary">Active Subscription Offerings</h5>
                            <div class="list-group list-group-flush small">
                @forelse($plans as $plan)
                    <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3" style="background: transparent;">
                        <div>
                            <div class="fw-bold text-theme-primary fs-6">{{ $plan->name }}</div>
                            <div class="text-muted">{{ $plan->description ?? 'Custom features for platform companions.' }}</div>
                            <small class="text-primary fw-medium">{{ $plan->interval === 'monthly' ? 'Billed monthly' : 'Billed annually' }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-theme-primary fs-5">₹{{ number_format($plan->price) }}</div>
                            <div class="d-flex gap-2 mt-2 justify-content-end">
                                <button type="button" class="btn btn-sm btn-outline-primary py-0 rounded-pill px-2" style="font-size:0.72rem;"
                                    onclick="openEditPlan({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->price }}, '{{ $plan->interval }}')">
                                    Edit
                                </button>
                                <form action="{{ route('admin.plans.delete', $plan->id) }}" method="POST" onsubmit="return confirm('Delete this plan?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 rounded-pill px-2" style="font-size: 0.72rem;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">No pricing plans created yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Edit Plan Modal -->
<div class="modal fade" id="editPlanModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Subscription Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editPlanForm" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-bold">Plan Name</label>
            <input type="text" name="name" id="editPlanName" class="form-control form-control-sm" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Price (₹)</label>
            <input type="number" name="price" id="editPlanPrice" class="form-control form-control-sm" step="0.01" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Billing Interval</label>
            <select name="interval" id="editPlanInterval" class="form-select form-select-sm" required>
              <option value="monthly">Monthly Cycle</option>
              <option value="yearly">Yearly Cycle</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary rounded-pill">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@section('scripts')
<script>
function openEditPlan(id, name, price, interval) {
    document.getElementById('editPlanForm').action = '/admin/plans/' + id + '/update';
    document.getElementById('editPlanName').value = name;
    document.getElementById('editPlanPrice').value = price;
    document.getElementById('editPlanInterval').value = interval;
    new bootstrap.Modal(document.getElementById('editPlanModal')).show();
}
</script>
@endsection

@endsection
