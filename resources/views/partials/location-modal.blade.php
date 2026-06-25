<!-- Location Selector Modal -->
<div class="modal fade" id="locationSelectorModal" tabindex="-1" aria-labelledby="locationSelectorModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-3" style="border-radius: 20px; background-color: var(--surface, var(--card-bg)); border: 1px solid var(--border, var(--card-border));">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="locationSelectorModalLabel" style="color: var(--text-primary, var(--text-dark));">📍 Select Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--bs-alert-btn-close-white, none);"></button>
            </div>
            <div class="modal-body">
                <!-- Auto-detect button -->
                <button type="button" class="btn btn-primary rounded-pill w-100 py-2.5 mb-4 d-flex align-items-center justify-content-center gap-2" id="btnDetectLocation" onclick="detectGPSLocation()">
                    <i class="bi bi-crosshair"></i> Detect Current Location (GPS)
                </button>
                
                <div class="text-center text-muted small mb-3">OR SELECT CITY MANUALLY</div>
                
                <!-- Search box -->
                <div class="input-group mb-3 shadow-sm rounded-pill overflow-hidden border">
                    <span class="input-group-text border-0 bg-transparent text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="locationSearchInput" class="form-control border-0 bg-transparent px-2" placeholder="Search registered cities..." onkeyup="filterModalCities()">
                </div>
                
                <!-- Cities list -->
                @php
                    $activeCitiesForModal = \App\Models\City::where('is_active', true)->get();
                @endphp
                <div class="list-group list-group-flush rounded-3 overflow-y-auto" style="max-height: 250px;" id="modalCitiesList">
                    <button type="button" class="list-group-item list-group-item-action border-0 px-3 py-2.5 fw-medium d-flex justify-content-between align-items-center modal-city-item" style="background-color: var(--surface, var(--card-bg)); color: var(--text-primary, var(--text-dark));" onclick="selectManualLocation(this, 'all', 'All Locations')">
                        <span><i class="bi bi-globe me-2 text-muted"></i>All Locations</span>
                        <span class="badge bg-success-subtle text-success small rounded-pill">Global</span>
                    </button>
                    @forelse($activeCitiesForModal as $c)
                        <button type="button" class="list-group-item list-group-item-action border-0 px-3 py-2.5 fw-medium d-flex justify-content-between align-items-center modal-city-item" style="background-color: var(--surface, var(--card-bg)); color: var(--text-primary, var(--text-dark));" onclick="selectManualLocation(this, {{ $c->id }}, '{{ $c->name }}')">
                            <span><i class="bi bi-building me-2 text-muted"></i>{{ $c->name }}</span>
                            <span class="badge bg-secondary-subtle text-secondary small rounded-pill">Active</span>
                        </button>
                    @empty
                        <div class="text-center text-muted py-3">No active cities available.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
