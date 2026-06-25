{{--
    This file is intentionally minimal.
    The application homepage is handled by HomeController@index → home.blade.php.
    Route: GET / → HomeController::index (routes/web.php)
--}}
@php
    // Redirect any direct hits to this view to the real homepage
    header('Location: ' . route('home'), true, 302);
    exit;
@endphp
