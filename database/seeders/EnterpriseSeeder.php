<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Banner;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\AuditLog;
use App\Models\Blog;
use App\Models\User;

class EnterpriseSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Countries
        $india = Country::create([
            'name' => 'India',
            'code' => 'IN',
            'currency' => 'INR',
            'is_active' => true,
        ]);
        $usa = Country::create([
            'name' => 'United States',
            'code' => 'US',
            'currency' => 'USD',
            'is_active' => true,
        ]);

        // 2. Seed States
        $mp = State::create([
            'country_id' => $india->id,
            'name' => 'Madhya Pradesh',
            'code' => 'MP',
            'is_active' => true,
        ]);
        $mh = State::create([
            'country_id' => $india->id,
            'name' => 'Maharashtra',
            'code' => 'MH',
            'is_active' => true,
        ]);

        // 3. Update existing cities with state_id
        City::whereIn('slug', ['indore', 'bhopal', 'jabalpur', 'gwalior', 'ujjain', 'sagar', 'dewas', 'satna', 'ratlam', 'rewa'])
            ->update(['state_id' => $mp->id]);

        // 4. Seed Banners
        Banner::create([
            'title' => 'Find local companions in MP',
            'type' => 'homepage',
            'image_path' => 'images/hero_slide1.jpg',
            'link_url' => '/companions',
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
            'is_active' => true,
            'order_index' => 1,
        ]);
        Banner::create([
            'title' => '20% Off on Your First Companion Booking!',
            'type' => 'promotional',
            'image_path' => 'images/hero_slide2.jpg',
            'link_url' => '/companions',
            'start_date' => now(),
            'end_date' => now()->addWeeks(4),
            'is_active' => true,
            'order_index' => 2,
        ]);

        // 5. Seed Plans
        Plan::create([
            'name' => 'Basic Companion Partner',
            'slug' => 'basic-partner',
            'price' => 499.00,
            'interval' => 'monthly',
            'features_limit' => json_encode(['bookings_per_month' => 10, 'featured_listing' => false]),
            'description' => 'Perfect for part-time companions starting out.',
            'is_active' => true,
        ]);
        Plan::create([
            'name' => 'Professional Companion Partner',
            'slug' => 'pro-partner',
            'price' => 1499.00,
            'interval' => 'monthly',
            'features_limit' => json_encode(['bookings_per_month' => -1, 'featured_listing' => true]),
            'description' => 'Unlimited bookings and priority placement in search results.',
            'is_active' => true,
        ]);

        // 6. Seed Coupons
        Coupon::create([
            'code' => 'WELCOME20',
            'type' => 'percentage',
            'value' => 20.00,
            'max_uses' => 500,
            'uses_count' => 42,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);
        Coupon::create([
            'code' => 'FLAT100',
            'type' => 'flat',
            'value' => 100.00,
            'max_uses' => 200,
            'uses_count' => 12,
            'expires_at' => now()->addMonth(),
            'is_active' => true,
        ]);

        // 7. Seed Audit Logs
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            AuditLog::create([
                'user_id' => $admin->id,
                'action' => 'LOGIN',
                'description' => 'Super Admin logged in successfully',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ]);
            AuditLog::create([
                'user_id' => $admin->id,
                'action' => 'UPDATE_SETTINGS',
                'description' => 'Updated site commission and currency settings',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ]);
        }

        // 8. Seed Blogs
        Blog::create([
            'title' => 'Building Healthy Social Connections in the Modern Era',
            'slug' => 'building-healthy-social-connections',
            'content' => '<p>In today\'s digital-first society, building genuine social connections can sometimes feel challenging. Our platform helps bridge that gap by connecting you with verified companions...</p>',
            'meta_title' => 'Building Social Connections | Companion',
            'meta_description' => 'Learn how to form positive and healthy real-life social interactions using Companion.',
            'author_name' => 'Dr. Aditi Roy',
            'is_active' => true,
        ]);

    }
}
