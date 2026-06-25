<script>
function filterModalCities() {
    const input = document.getElementById('locationSearchInput');
    const filter = input.value.toLowerCase();
    const items = document.querySelectorAll('.modal-city-item');
    
    items.forEach(item => {
        const text = item.textContent || item.innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            item.style.setProperty('display', 'flex', 'important');
        } else {
            item.style.setProperty('display', 'none', 'important');
        }
    });
}

function selectManualLocation(btnElement, cityId, cityName) {
    if (btnElement) {
        btnElement.disabled = true;
        btnElement.innerHTML = '<span><span class="spinner-border spinner-border-sm me-2"></span>Selecting...</span>';
    }

    fetch("/location/select", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ city_id: cityId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to select location. Please try again.');
            if (btnElement) {
                btnElement.disabled = false;
                if (cityId === 'all') {
                    btnElement.innerHTML = `<span><i class="bi bi-globe me-2 text-muted"></i>All Locations</span><span class="badge bg-success-subtle text-success small rounded-pill">Global</span>`;
                } else {
                    btnElement.innerHTML = `<span><i class="bi bi-building me-2 text-muted"></i>${cityName}</span><span class="badge bg-secondary-subtle text-secondary small rounded-pill">Active</span>`;
                }
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred. Please try again.');
        if (btnElement) {
            btnElement.disabled = false;
            if (cityId === 'all') {
                btnElement.innerHTML = `<span><i class="bi bi-globe me-2 text-muted"></i>All Locations</span><span class="badge bg-success-subtle text-success small rounded-pill">Global</span>`;
            } else {
                btnElement.innerHTML = `<span><i class="bi bi-building me-2 text-muted"></i>${cityName}</span><span class="badge bg-secondary-subtle text-secondary small rounded-pill">Active</span>`;
            }
        }
    });
}

function detectGPSLocation() {
    const btn = document.getElementById('btnDetectLocation');
    const originalText = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Requesting GPS coordinates...';
    }

    // HTTPS or localhost compatibility verification
    const isSecure = window.location.protocol === 'https:' || 
                     ['localhost', '127.0.0.1', '::1'].includes(window.location.hostname);

    if (!isSecure) {
        console.warn("Location System: Unsecured HTTP context. Geolocation is restricted. Bypassing to IP fallback.");
        logLocationErrorBackend('insecure_context', 'GPS requested on HTTP. restricted context.');
        fallbackToIPLocation(btn, originalText);
        return;
    }

    if (!navigator.geolocation) {
        console.error('Geolocation is not supported by your browser.');
        logLocationErrorBackend('unsupported_browser', 'Geolocation API not supported.');
        fallbackToIPLocation(btn, originalText);
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            console.log(`Location System: Coordinates received: Lat: ${lat}, Lng: ${lng}`);
            logLocationSuccessBackend('coordinates_received', lat, lng);

            if (btn) {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Reverse geocoding (Nominatim)...';
            }

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
                headers: {
                    'Accept-Language': 'en',
                    'User-Agent': 'Hire-A-Friend Geolocation Agent'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Nominatim reverse geocoding failed with status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Location System: OSM Nominatim response received:", data);
                logGeocodingResponseBackend(data);

                const addr = data.address || {};
                const city = addr.city || addr.town || addr.village || addr.municipality || addr.city_district || addr.county || 'Bhopal';
                const state = addr.state || 'Madhya Pradesh';
                const country = addr.country || 'India';
                const area = addr.suburb || addr.neighbourhood || addr.road || addr.quarter || '';

                sendLocationData(city, state, country, area, lat, lng, btn, originalText);
            })
            .catch(err => {
                console.error('Reverse geocoding failed, sending coordinates only', err);
                logLocationErrorBackend('reverse_geocoding_failed', err.message);
                sendLocationData('Bhopal', 'Madhya Pradesh', 'India', '', lat, lng, btn, originalText);
            });
        },
        function(error) {
            let errorType = 'unknown_error';
            let errorMessage = error.message;
            
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorType = 'permission_denied';
                    console.warn('Geolocation permission denied.');
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorType = 'position_unavailable';
                    console.warn('GPS position unavailable.');
                    break;
                case error.TIMEOUT:
                    errorType = 'timeout';
                    console.warn('GPS request timed out.');
                    break;
            }

            logLocationErrorBackend(errorType, errorMessage);

            if (errorType === 'permission_denied') {
                showToast("Location permission was denied. Falling back to IP location...", "warning");
            } else if (errorType === 'timeout') {
                showToast("Location detection timed out. Falling back to IP location...", "warning");
            } else {
                showToast("GPS location unavailable. Falling back to IP location...", "warning");
            }

            fallbackToIPLocation(btn, originalText);
        },
        { enableHighAccuracy: true, timeout: 6000 }
    );
}

function sendLocationData(city, state, country, area, lat, lng, btn, originalText) {
    fetch("/location/detect", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            city: city,
            state: state,
            country: country,
            area: area,
            latitude: lat,
            longitude: lng
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to update detected location.');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    })
    .catch(err => {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

function fallbackToIPLocation(btn, originalText) {
    if (btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Falling back to IP detection...';
    }

    fetch("/location/detect-ip", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    })
    .catch(err => {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
}

function logLocationErrorBackend(type, message) {
    fetch("/location/log-error", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type, message: message })
    }).catch(e => console.error("Telemetry failed:", e));
}

function logLocationSuccessBackend(type, lat, lng) {
    fetch("/location/log-success", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type, latitude: lat, longitude: lng })
    }).catch(e => console.error("Telemetry failed:", e));
}

function logGeocodingResponseBackend(response) {
    fetch("/location/log-geocoding", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ response: response })
    }).catch(e => console.error("Telemetry failed:", e));
}

document.addEventListener('DOMContentLoaded', function () {
    @if(!session()->has('user_location'))
        console.log("Location System: First visit. Automatically prompting for GPS location...");
        detectGPSLocation();
    @endif
});
</script>
