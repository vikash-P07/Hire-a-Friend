<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Partner\DashboardController as PartnerDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/companions', [CompanionController::class, 'index'])->name('companions.index');
Route::get('/companions/{id}', [CompanionController::class, 'show'])->name('companions.show');
Route::post('/coupons/validate', [CompanionController::class, 'validateCoupon'])->name('coupons.validate');
Route::get('/page/{slug}', [HomeController::class, 'viewCmsPage'])->name('cms.page');
Route::post('/location/select', [HomeController::class, 'selectLocation'])->name('location.select');
Route::post('/location/detect', [HomeController::class, 'detectLocation'])->name('location.detect');
Route::post('/location/detect-ip', [HomeController::class, 'detectIpLocation'])->name('location.detect-ip');
Route::post('/location/log-error', [HomeController::class, 'logLocationError'])->name('location.log-error');
Route::post('/location/log-success', [HomeController::class, 'logLocationSuccess'])->name('location.log-success');
Route::post('/location/log-geocoding', [HomeController::class, 'logGeocoding'])->name('location.log-geocoding');

// Chatbot Routes
Route::post('/chatbot/send', [\App\Http\Controllers\ChatbotController::class, 'send'])->name('chatbot.send');
Route::get('/chatbot/history', [\App\Http\Controllers\ChatbotController::class, 'history'])->name('chatbot.history');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/auth/google/firebase', [AuthController::class, 'googleFirebaseLogin'])->name('auth.google.firebase');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Chat API Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/conversations', [\App\Http\Controllers\ChatController::class, 'getConversations'])->name('conversations');
        Route::get('/{conversation_id}/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('messages');
        Route::post('/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('send');
        Route::post('/{conversation_id}/read', [\App\Http\Controllers\ChatController::class, 'markRead'])->name('read');
        Route::get('/unread', [\App\Http\Controllers\ChatController::class, 'globalUnread'])->name('unread');
        Route::get('/start/{user_id}', [\App\Http\Controllers\ChatController::class, 'startConversation'])->name('start');
    });

    // Customer Dashboards
    Route::middleware(['role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
        Route::post('/profile', [CustomerDashboard::class, 'updateProfile'])->name('profile.update');
        Route::post('/book/{partnerId}', [CustomerDashboard::class, 'book'])->name('book');
        Route::get('/payment/{booking_id}', [CustomerDashboard::class, 'paymentCheckout'])->name('payment.checkout');
        Route::post('/payment/{booking_id}/process', [CustomerDashboard::class, 'paymentProcess'])->name('payment.process');
        Route::get('/payment/{booking_id}/receipt', [CustomerDashboard::class, 'paymentReceipt'])->name('payment.receipt');
        Route::post('/bookings/{id}/cancel', [CustomerDashboard::class, 'cancelBooking'])->name('booking.cancel');
        Route::post('/bookings/{id}/review', [CustomerDashboard::class, 'submitReview'])->name('booking.review');
        Route::get('/messages', [CustomerDashboard::class, 'messages'])->name('messages');
        Route::get('/wallet', [CustomerDashboard::class, 'wallet'])->name('wallet');
        Route::get('/safety', [CustomerDashboard::class, 'safety'])->name('safety');
        Route::get('/settings', [CustomerDashboard::class, 'settings'])->name('settings');
        Route::get('/notifications', [CustomerDashboard::class, 'notifications'])->name('notifications');
        Route::get('/reviews', [CustomerDashboard::class, 'reviews'])->name('reviews');
        Route::get('/favorites', [CustomerDashboard::class, 'favorites'])->name('favorites');
        Route::post('/favorites/toggle', [CustomerDashboard::class, 'toggleFavorite'])->name('favorites.toggle');
    });

    // Companion Partner Dashboards
    Route::middleware(['role:partner'])->prefix('partner')->name('partner.')->group(function () {
        Route::get('/dashboard', [PartnerDashboard::class, 'index'])->name('dashboard');
        
        // Onboarding / Profile setup
        Route::get('/profile', [PartnerDashboard::class, 'profile'])->name('profile');
        Route::post('/profile', [PartnerDashboard::class, 'updateProfile'])->name('profile.update');
        Route::post('/onboarding', [PartnerDashboard::class, 'saveOnboarding'])->name('onboarding.save');
        
        // Bookings
        Route::get('/bookings', [PartnerDashboard::class, 'bookings'])->name('bookings');
        Route::post('/bookings/{id}/reschedule', [PartnerDashboard::class, 'rescheduleBooking'])->name('bookings.reschedule');
        Route::post('/bookings/{id}/{action}', [PartnerDashboard::class, 'handleBooking'])->name('booking.action');
        
        // Earnings
        Route::get('/earnings', [PartnerDashboard::class, 'earnings'])->name('earnings');
        Route::post('/earnings/withdraw', [PartnerDashboard::class, 'requestWithdrawal'])->name('earnings.withdraw');
        
        // Availability
        Route::get('/availability', [PartnerDashboard::class, 'availability'])->name('availability');
        Route::post('/availability', [PartnerDashboard::class, 'updateAvailability'])->name('availability.update');
        Route::post('/availability/vacation', [PartnerDashboard::class, 'toggleVacationMode'])->name('availability.vacation');
        
        // Analytics
        Route::get('/analytics', [PartnerDashboard::class, 'analytics'])->name('analytics');
        
        // Messages
        Route::get('/messages', [PartnerDashboard::class, 'messages'])->name('messages');
        
        // Subscription
        Route::get('/subscription', [PartnerDashboard::class, 'subscription'])->name('subscription');
        Route::post('/subscription/subscribe/{planId}', [PartnerDashboard::class, 'subscribe'])->name('subscription.subscribe');
    });

    // Admin Dashboards
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        
        // Users
        Route::get('/users', [AdminDashboard::class, 'users'])->name('users');
        Route::post('/users', [AdminDashboard::class, 'storeUser'])->name('users.store');
        Route::post('/users/{id}/update', [AdminDashboard::class, 'updateUser'])->name('users.update');
        Route::post('/users/{id}/toggle', [AdminDashboard::class, 'toggleUserStatus'])->name('users.toggle');
        Route::post('/users/{id}/toggle-role/{role}', [AdminDashboard::class, 'toggleUserRole'])->name('users.toggle-role');
        Route::post('/users/{id}/delete', [AdminDashboard::class, 'deleteUser'])->name('users.delete');
        
        // KYC & Partners
        Route::get('/kyc', [AdminDashboard::class, 'kycList'])->name('kyc');
        Route::post('/partners', [AdminDashboard::class, 'storePartner'])->name('partners.store');
        Route::post('/partners/{id}/update', [AdminDashboard::class, 'updatePartner'])->name('partners.update');
        Route::get('/partners/{id}', [AdminDashboard::class, 'partnerDetails'])->name('partners.show');
        Route::post('/kyc/{id}/{action}', [AdminDashboard::class, 'handleKyc'])->name('kyc.action');
        Route::post('/partners/{id}/toggle-featured', [AdminDashboard::class, 'togglePartnerFeatured'])->name('partners.toggle-featured');
        
        // Cities
        Route::get('/cities', [AdminDashboard::class, 'cities'])->name('cities');
        Route::post('/cities', [AdminDashboard::class, 'storeCity'])->name('cities.store');
        Route::post('/cities/{id}/update', [AdminDashboard::class, 'updateCity'])->name('cities.update');
        Route::post('/cities/{id}/delete', [AdminDashboard::class, 'deleteCity'])->name('cities.delete');
        
        // Categories & Services
        Route::get('/categories', [AdminDashboard::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminDashboard::class, 'storeCategory'])->name('categories.store');
        Route::post('/categories/{id}/delete', [AdminDashboard::class, 'deleteCategory'])->name('categories.delete');
        Route::post('/services', [AdminDashboard::class, 'storeService'])->name('services.store');
        Route::post('/services/{id}/delete', [AdminDashboard::class, 'deleteService'])->name('services.delete');
        
        // CMS Pages
        Route::get('/cms', [AdminDashboard::class, 'cmsPages'])->name('cms');
        Route::post('/cms', [AdminDashboard::class, 'storeCmsPage'])->name('cms.store');
        Route::post('/cms/{id}/update', [AdminDashboard::class, 'updateCmsPage'])->name('cms.update');
        Route::post('/cms/{id}/delete', [AdminDashboard::class, 'deleteCmsPage'])->name('cms.delete');

        // Chat Moderation
        Route::get('/conversations', [AdminDashboard::class, 'conversations'])->name('conversations');
        Route::get('/conversations/{id}', [AdminDashboard::class, 'conversationShow'])->name('conversations.show');
        Route::post('/conversations/{id}/block', [AdminDashboard::class, 'conversationBlock'])->name('conversations.block');

        // Settings
        Route::get('/settings', [AdminDashboard::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminDashboard::class, 'updateSettings'])->name('settings.update');

        // Homepage Profile Management
        Route::prefix('homepage')->name('homepage.')->group(function () {
            Route::get('/recommended', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'recommended'])->name('recommended');
            Route::post('/recommended/add', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'addRecommended'])->name('recommended.add');
            Route::post('/recommended/{id}/remove', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'removeRecommended'])->name('recommended.remove');
            Route::post('/recommended/{id}/toggle-visibility', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'toggleRecommendedVisibility'])->name('recommended.toggle-visibility');
            Route::post('/recommended/reorder', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'reorderRecommended'])->name('recommended.reorder');

            Route::get('/top', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'top'])->name('top');
            Route::post('/top/add', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'addTop'])->name('top.add');
            Route::post('/top/{id}/remove', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'removeTop'])->name('top.remove');
            Route::post('/top/{id}/toggle-visibility', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'toggleTopVisibility'])->name('top.toggle-visibility');
            Route::post('/top/reorder', [\App\Http\Controllers\Admin\HomepageProfileController::class, 'reorderTop'])->name('top.reorder');
        });

        // New Enterprise Super Admin Routes
        // Bookings
        Route::get('/bookings', [AdminDashboard::class, 'bookings'])->name('bookings');
        Route::post('/bookings/{id}/action/{action}', [AdminDashboard::class, 'handleBookingAction'])->name('bookings.action');
        
        // Payments & Payouts Console
        Route::get('/transactions', [AdminDashboard::class, 'transactions'])->name('transactions');
        Route::post('/transactions/{id}/refund', [AdminDashboard::class, 'refundBooking'])->name('transactions.refund');
        Route::get('/commissions', [AdminDashboard::class, 'commissionsConsole'])->name('commissions');
        Route::post('/commissions/update', [AdminDashboard::class, 'updateCommissions'])->name('commissions.update');
        Route::get('/payouts', [AdminDashboard::class, 'payoutsConsole'])->name('payouts');
        Route::post('/payouts/{id}/action/{action}', [AdminDashboard::class, 'handlePayoutAction'])->name('payouts.action');


        // Locations
        Route::get('/locations', [AdminDashboard::class, 'locations'])->name('locations');
        Route::post('/countries', [AdminDashboard::class, 'storeCountry'])->name('countries.store');
        Route::post('/countries/{id}/update', [AdminDashboard::class, 'updateCountry'])->name('countries.update');
        Route::post('/countries/{id}/delete', [AdminDashboard::class, 'deleteCountry'])->name('countries.delete');
        Route::post('/states', [AdminDashboard::class, 'storeState'])->name('states.store');
        Route::post('/states/{id}/update', [AdminDashboard::class, 'updateState'])->name('states.update');
        Route::post('/states/{id}/delete', [AdminDashboard::class, 'deleteState'])->name('states.delete');
        Route::post('/cities/{id}/toggle', [AdminDashboard::class, 'toggleCityStatus'])->name('cities.toggle');

        // Marketing & Promos
        Route::get('/marketing', [AdminDashboard::class, 'marketing'])->name('marketing');
        Route::post('/banners', [AdminDashboard::class, 'storeBanner'])->name('banners.store');
        Route::post('/banners/{id}/delete', [AdminDashboard::class, 'deleteBanner'])->name('banners.delete');
        Route::post('/coupons', [AdminDashboard::class, 'storeCoupon'])->name('coupons.store');
        Route::post('/coupons/{id}/update', [AdminDashboard::class, 'updateCoupon'])->name('coupons.update');
        Route::post('/coupons/{id}/toggle-status', [AdminDashboard::class, 'toggleCouponStatus'])->name('coupons.toggle-status');
        Route::post('/coupons/{id}/delete', [AdminDashboard::class, 'deleteCoupon'])->name('coupons.delete');

        // Subscriptions
        Route::get('/subscriptions', [AdminDashboard::class, 'subscriptions'])->name('subscriptions');
        Route::post('/plans', [AdminDashboard::class, 'storePlan'])->name('plans.store');
        Route::post('/plans/{id}/update', [AdminDashboard::class, 'updatePlan'])->name('plans.update');
        Route::post('/plans/{id}/delete', [AdminDashboard::class, 'deletePlan'])->name('plans.delete');

        // CMS updates (Blog, FAQ, etc.)
        Route::post('/cms/blogs', [AdminDashboard::class, 'storeBlog'])->name('blogs.store');
        Route::post('/cms/blogs/{id}/update', [AdminDashboard::class, 'updateBlog'])->name('blogs.update');
        Route::post('/cms/blogs/{id}/delete', [AdminDashboard::class, 'deleteBlog'])->name('blogs.delete');

        // Notifications Console
        Route::get('/notifications', [AdminDashboard::class, 'notifications'])->name('notifications');
        Route::post('/notifications/send', [AdminDashboard::class, 'sendNotifications'])->name('notifications.send');

        // Security Console & Auditing
        Route::get('/security', [AdminDashboard::class, 'security'])->name('security');
        Route::post('/security/toggle-2fa', [AdminDashboard::class, 'toggle2FA'])->name('security.toggle-2fa');
    });

});
