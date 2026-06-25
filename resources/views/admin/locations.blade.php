@extends('layouts.admin')
@section('title', 'Location Settings - Admin Console')

@section('content')
<div class="row g-4">
                
                <!-- Countries Section -->
                <div class="col-md-6">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary">Add New Country</h5>
                        <form action="{{ route('admin.countries.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-2">
                                <input type="text" name="name" class="form-control" placeholder="Country Name (e.g. India)" required>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <input type="text" name="code" class="form-control" placeholder="ISO Code (e.g. IN)" required>
                                </div>
                                <div class="col-6">
                                    <input type="text" name="currency" class="form-control" placeholder="Currency (e.g. INR)" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100">Save Country</button>
                        </form>

                        <h6 class="fw-bold text-muted small">Registered Countries</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($countries as $c)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-theme-primary fw-bold">{{ $c->name }} ({{ $c->code }})</span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm text-primary p-0 border-0 bg-transparent me-1"
                                            onclick="openEditCountry({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ $c->code }}', '{{ $c->currency }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.countries.delete', $c->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm text-danger p-0 border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted small">No countries registered.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- States Section -->
                <div class="col-md-6">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary">Add New State</h5>
                        <form action="{{ route('admin.states.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-2">
                                <select name="country_id" class="form-select" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="name" class="form-control" placeholder="State Name (e.g. Madhya Pradesh)" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="code" class="form-control" placeholder="State Code (e.g. MP)" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100">Save State</button>
                        </form>

                        <h6 class="fw-bold text-muted small">Registered States</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($states as $s)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-theme-primary fw-bold">{{ $s->name }} ({{ $s->country->name }})</span>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm text-primary p-0 border-0 bg-transparent me-1"
                                            onclick="openEditState({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ $s->code }}', {{ $s->country_id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.states.delete', $s->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm text-danger p-0 border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted small">No states registered.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Cities List & Distribution Analytics -->
                <div class="col-md-8">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary">Cities &amp; Locations Directory</h5>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover small">
                                <thead class="table-light">
                                    <tr>
                                        <th>City Name</th>
                                        <th>Slug</th>
                                        <th>State</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cities as $city)
                                        <tr>
                                            <td class="fw-bold text-theme-primary">{{ $city->name }}</td>
                                            <td class="text-muted">{{ $city->slug }}</td>
                                            <td class="text-muted">{{ $city->state->name ?? 'None' }}</td>
                                            <td>
                                                <span class="badge {{ $city->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill" style="font-size: 0.72rem; padding: 0.35em 0.75em;">
                                                    {{ $city->is_active ? 'Active' : 'Disabled' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.cities.toggle', $city->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $city->is_active ? 'btn-outline-danger' : 'btn-success' }} rounded-pill px-3 py-0.5" style="font-size: 0.72rem;">
                                                        {{ $city->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-3 text-muted">No cities registered.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Distribution Analytics -->
                <div class="col-md-4">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Location Distribution</h5>
                        <p class="text-muted small mb-4">Total active, KYC-approved companion profiles grouped by city.</p>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>City</th>
                                        <th class="text-end">Active Companions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($locationDistribution as $dist)
                                        <tr>
                                            <td class="fw-bold text-theme-primary">{{ $dist->city ?: 'Unspecified' }}</td>
                                            <td class="text-end fw-bold text-primary">{{ $dist->count }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-3 text-muted">No companions mapped to any city.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


@endsection

<!-- Edit Country Modal -->
<div class="modal fade" id="editCountryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Country</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editCountryForm" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label small fw-bold">Country Name</label>
            <input type="text" name="name" id="editCountryName" class="form-control" required>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label small fw-bold">ISO Code</label>
              <input type="text" name="code" id="editCountryCode" class="form-control" maxlength="10" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold">Currency</label>
              <input type="text" name="currency" id="editCountryCurrency" class="form-control" maxlength="10">
            </div>
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

<!-- Edit State Modal -->
<div class="modal fade" id="editStateModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit State</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editStateForm" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label small fw-bold">State Name</label>
            <input type="text" name="name" id="editStateName" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">State Code</label>
            <input type="text" name="code" id="editStateCode" class="form-control" maxlength="10">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-bold">Country</label>
            <select name="country_id" id="editStateCountry" class="form-select" required>
              @foreach($countries as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
              @endforeach
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
function openEditCountry(id, name, code, currency) {
    document.getElementById('editCountryForm').action = '/admin/countries/' + id + '/update';
    document.getElementById('editCountryName').value = name;
    document.getElementById('editCountryCode').value = code;
    document.getElementById('editCountryCurrency').value = currency;
    new bootstrap.Modal(document.getElementById('editCountryModal')).show();
}
function openEditState(id, name, code, countryId) {
    document.getElementById('editStateForm').action = '/admin/states/' + id + '/update';
    document.getElementById('editStateName').value = name;
    document.getElementById('editStateCode').value = code;
    document.getElementById('editStateCountry').value = countryId;
    new bootstrap.Modal(document.getElementById('editStateModal')).show();
}
</script>
@endsection
