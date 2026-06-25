@extends('layouts.admin')
@section('title', 'City Locations - Admin Console')

@section('content')
<div class="row g-4">
    <!-- Add New City Form -->
    <div class="col-md-4">
        <div class="portal-card">
            <h5 class="fw-bold mb-3 text-theme-primary">Add New City</h5>
            <form action="{{ route('admin.cities.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="cityNameInput" class="form-label small fw-semibold text-theme-primary">City Name</label>
                    <input type="text" name="name" id="cityNameInput" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Mumbai, Bangalore" required value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Create City</button>
            </form>
        </div>
    </div>

    <!-- Cities List Table -->
    <div class="col-md-8">
        <div class="portal-card">
            <h5 class="fw-bold mb-3 text-theme-primary">Configured Locations</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>City Name</th>
                            <th>Slug</th>
                            <th>Associated Users</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td><div class="fw-bold text-theme-primary">{{ $city->name }}</div></td>
                                <td><code class="small text-muted">{{ $city->slug }}</code></td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $city->users_count }} users</span></td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                            onclick="openEditCity({{ $city->id }}, '{{ addslashes($city->name) }}')">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.cities.delete', $city->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this city? It may affect users registered in it.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted small">No cities configured yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit City Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit City</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editCityForm" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-bold">City Name</label>
            <input type="text" name="name" id="editCityName" class="form-control" required>
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
function openEditCity(id, name) {
    document.getElementById('editCityForm').action = '/admin/cities/' + id + '/update';
    document.getElementById('editCityName').value = name;
    new bootstrap.Modal(document.getElementById('editCityModal')).show();
}
</script>
@endsection

@endsection
