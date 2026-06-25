<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City;
use App\Models\Category;
use App\Models\Service;
use App\Models\CompanionProfile;
use App\Models\DocumentVerification;
use App\Models\Booking;
use App\Models\Review;
use App\Models\CmsPage;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Settings
        Setting::set('site_name', 'Companion Booking Platform');
        Setting::set('contact_email', 'support@companion.com');
        Setting::set('platform_commission', '15');
        Setting::set('currency', 'INR');

        // 2. Seed CMS Pages
        CmsPage::create([
            'title'     => 'About Us',
            'slug'      => 'about-us',
            'content'   => '<div class="row align-items-center py-2"><div class="col-md-6 mb-4 mb-md-0"><h3 class="fw-bold mb-3 text-dark">Welcome to Companion!</h3><p class="text-muted leading-relaxed mb-3">We are the leading platform connecting people with friendly, local companion partners for social, travel, fitness, and study activities. Our mission is to reduce social isolation and foster memorable real-life connections.</p><p class="text-muted leading-relaxed">Whether you are new to a city, looking for a study buddy, wanting to hike with a local guide, or simply seeking friendly conversation over coffee, Companion makes it easy, safe, and pleasant to connect in the real world.</p></div><div class="col-md-6"><img src="/images/about_us.png" class="img-fluid rounded-4 shadow" alt="About Us"></div></div>',
            'is_active' => true,
        ]);
        CmsPage::create([
            'title'     => 'Terms of Service',
            'slug'      => 'terms-of-service',
            'content'   => '<h3>Terms of Service</h3><p>By using our platform, you agree to treat companion partners with respect. Payments are handled securely, and cancellations must be made 24 hours prior to the scheduled booking.</p>',
            'is_active' => true,
        ]);
        CmsPage::create([
            'title'     => 'Privacy Policy',
            'slug'      => 'privacy-policy',
            'content'   => '<h3>Privacy Policy</h3><p>Your privacy is important to us. We secure your personal information and documents uploaded for KYC verification. We do not sell your personal data to third parties.</p>',
            'is_active' => true,
        ]);

        // 3. Seed Cities — Madhya Pradesh, India
        $citiesData = [
            ['name' => 'Indore',   'slug' => 'indore'],
            ['name' => 'Bhopal',   'slug' => 'bhopal'],
            ['name' => 'Jabalpur', 'slug' => 'jabalpur'],
            ['name' => 'Gwalior',  'slug' => 'gwalior'],
            ['name' => 'Ujjain',   'slug' => 'ujjain'],
            ['name' => 'Sagar',    'slug' => 'sagar'],
            ['name' => 'Dewas',    'slug' => 'dewas'],
            ['name' => 'Satna',    'slug' => 'satna'],
            ['name' => 'Ratlam',   'slug' => 'ratlam'],
            ['name' => 'Rewa',     'slug' => 'rewa'],
        ];
        $cities = [];
        foreach ($citiesData as $city) {
            $cities[] = City::create($city);
        }

        // 4. Seed Categories and Services
        $categoriesData = [
            [
                'name'        => 'Social Companion',
                'slug'        => 'social-companion',
                'description' => 'Friendly partners for dinners, events, movie nights, or casual cafe chats.',
                'services'    => [
                    ['name' => 'Dining Partner',   'description' => 'Accompany for dinner or lunches at restaurants.'],
                    ['name' => 'Cafe Chat',         'description' => 'Casual conversation over coffee/tea.'],
                    ['name' => 'Event Goer',        'description' => 'Go together to weddings, concerts, or networking sessions.'],
                    ['name' => 'Movie Buddy',       'description' => 'Accompany for cinema and discussions.'],
                ],
            ],
            [
                'name'        => 'Travel Companion',
                'slug'        => 'travel-companion',
                'description' => 'Local companions to guide you through hiking trails or explore cities together.',
                'services'    => [
                    ['name' => 'Local Tour Guide', 'description' => 'Navigate city sights and local secrets.'],
                    ['name' => 'Hiking Partner',   'description' => 'Accompany on outdoor trails and nature parks.'],
                    ['name' => 'Road Trip Guide',  'description' => 'Explore nearby towns or scenic routes.'],
                ],
            ],
            [
                'name'        => 'Study & Career Buddy',
                'slug'        => 'study-career-buddy',
                'description' => 'Partners to practice languages, mock interview, or study in libraries.',
                'services'    => [
                    ['name' => 'Study Partner',    'description' => 'Focus and study together in cafes or libraries.'],
                    ['name' => 'Language Practice','description' => 'Conversational practice in English, Hindi, or regional languages.'],
                    ['name' => 'Mock Interviewer', 'description' => 'Simulate tech or business interviews.'],
                    ['name' => 'Coding Buddy',     'description' => 'Pair programming or reviewing code.'],
                ],
            ],
            [
                'name'        => 'Fitness & Sports',
                'slug'        => 'fitness-sports',
                'description' => 'Motivate your health goals with gym buddies, running, or sports partners.',
                'services'    => [
                    ['name' => 'Gym Buddy',        'description' => 'Work out and spot each other at the gym.'],
                    ['name' => 'Tennis Partner',   'description' => 'Play matches or practice rallies.'],
                    ['name' => 'Running Partner',  'description' => 'Hit local jogging tracks together.'],
                ],
            ],
        ];

        $servicesList = [];
        foreach ($categoriesData as $catData) {
            $category = Category::create([
                'name'        => $catData['name'],
                'slug'        => $catData['slug'],
                'description' => $catData['description'],
            ]);
            foreach ($catData['services'] as $serData) {
                $servicesList[] = Service::create([
                    'category_id' => $category->id,
                    'name'        => $serData['name'],
                    'description' => $serData['description'],
                ]);
            }
        }

        // 5. Seed Users

        // Admin — Indian name
        User::create([
            'name'      => 'Rajesh Admin',
            'email'     => 'admin@companion.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'phone'     => '+917712345678',
            'gender'    => 'male',
            'is_active' => true,
            'city_id'   => $cities[0]->id,
        ]);

        // Customers — Indian names & MP cities
        $customer1 = User::create([
            'name'      => 'Rahul Sharma',
            'email'     => 'john@customer.com',
            'password'  => Hash::make('password'),
            'role'      => 'customer',
            'phone'     => '+917771112223',
            'gender'    => 'male',
            'is_active' => true,
            'city_id'   => $cities[0]->id, // Indore
        ]);
        $customer2 = User::create([
            'name'      => 'Priya Jain',
            'email'     => 'emma@customer.com',
            'password'  => Hash::make('password'),
            'role'      => 'customer',
            'phone'     => '+917771112224',
            'gender'    => 'female',
            'is_active' => true,
            'city_id'   => $cities[1]->id, // Bhopal
        ]);
        $customer3 = User::create([
            'name'      => 'Suresh Patel',
            'email'     => 'david@customer.com',
            'password'  => Hash::make('password'),
            'role'      => 'customer',
            'phone'     => '+917771112225',
            'gender'    => 'male',
            'is_active' => true,
            'city_id'   => $cities[3]->id, // Gwalior
        ]);

        // ─────────────────────────────────────────────────────────────────────────
        // Partners (Companions) — 23 Indian profiles (21 approved, 1 pending, 1 rejected)
        // profile_picture → locally stored Indian portrait photos in public/images/profiles/
        // ─────────────────────────────────────────────────────────────────────────
        $partnersData = [
            // 1 ── Priya Sharma (Female, Indore)
            [
                'name'            => 'Priya Sharma',
                'email'           => 'priya@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[0]->id,
                'bio'             => 'Professional lifestyle blogger and cafe enthusiast from Indore. I love exploring new coffee shops and discussing books or art. Happy to accompany you to restaurant openings or art walks!',
                'hourly_rate'     => 2500.00,
                'profile_picture' => 'images/profiles/partner_2_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 4,
                'services'        => [0, 1, 10, 12],
            ],
            // 2 ── Aarav Sharma (Male, Bhopal)
            [
                'name'            => 'Aarav Sharma',
                'email'           => 'aarav@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[1]->id,
                'bio'             => 'University student offering event accompaniment and city tour guiding in Bhopal. Friendly, outgoing, fluent in English and Hindi.',
                'hourly_rate'     => 1800.00,
                'profile_picture' => 'images/profiles/partner_1_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 2,
                'services'        => [1, 2, 4, 8],
            ],
            // 3 ── Aditya Verma (Male, Jabalpur)
            [
                'name'            => 'Aditya Verma',
                'email'           => 'aditya@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[2]->id,
                'bio'             => 'Avid hiker and language enthusiast based in Jabalpur. Best hiking trails around Bhedaghat. Language practice in Hindi & English.',
                'hourly_rate'     => 2200.00,
                'profile_picture' => 'images/profiles/partner_3_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [5, 8],
            ],
            // 4 ── Diya Malhotra (Female, Gwalior)
            [
                'name'            => 'Diya Malhotra',
                'email'           => 'diya@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[3]->id,
                'bio'             => 'Senior software engineer in Gwalior who loves cafe chats and mock interviews. Let\'s practice coding or review your CV.',
                'hourly_rate'     => 3500.00,
                'profile_picture' => 'images/profiles/partner_4_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 5,
                'services'        => [1, 7, 9],
            ],
            // 5 ── Vihaan Gupta (Male, Ujjain)
            [
                'name'            => 'Vihaan Gupta',
                'email'           => 'vihaan@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[4]->id,
                'bio'             => 'Yoga practitioner and spiritual guide in Ujjain. Explore scenic temples, try local street food, or have deep conversations.',
                'hourly_rate'     => 1500.00,
                'profile_picture' => 'images/profiles/partner_5_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [1, 4],
            ],
            // 6 ── Kiara Joshi (Female, Sagar)
            [
                'name'            => 'Kiara Joshi',
                'email'           => 'kiara@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[5]->id,
                'bio'             => 'MBA student and shopping companion in Sagar. Fashion styling, event attendance, and study support in local libraries.',
                'hourly_rate'     => 1900.00,
                'profile_picture' => 'images/profiles/partner_6_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 2,
                'services'        => [1, 2, 7],
            ],
            // 7 ── Arjun Mehta (Male, Dewas)
            [
                'name'            => 'Arjun Mehta',
                'email'           => 'arjun@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[6]->id,
                'bio'             => 'Avid road tripper and photographer from Dewas. I know the best highway routes and scenic spots. Let\'s do a photowalk or road trip!',
                'hourly_rate'     => 3000.00,
                'profile_picture' => 'images/profiles/partner_7_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 4,
                'services'        => [6, 4],
            ],
            // 8 ── Aaradhya Rao (Female, Satna)
            [
                'name'            => 'Aaradhya Rao',
                'email'           => 'aaradhya@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[7]->id,
                'bio'             => 'Language teacher fluent in English, Hindi, and Sanskrit. Coffee chats, reading sessions, and casual museum walks in Satna.',
                'hourly_rate'     => 1700.00,
                'profile_picture' => 'images/profiles/partner_8_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [1, 8],
            ],
            // 9 ── Reyansh Dixit (Male, Ratlam)
            [
                'name'            => 'Reyansh Dixit',
                'email'           => 'reyansh@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[8]->id,
                'bio'             => 'Ratlam local who loves food tours and chai chats. Best street food hubs, local market guides, or study libraries.',
                'hourly_rate'     => 1600.00,
                'profile_picture' => 'images/profiles/partner_9_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 2,
                'services'        => [1, 7],
            ],
            // 10 ── Ananya Roy (Female, Rewa)
            [
                'name'            => 'Ananya Roy',
                'email'           => 'ananyaroy@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[9]->id,
                'bio'             => 'Tech consultant and coding buddy in Rewa. Mock interviews, software engineering CVs, or coding projects together.',
                'hourly_rate'     => 3200.00,
                'profile_picture' => 'images/profiles/partner_10_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 5,
                'services'        => [7, 9],
            ],
            // 11 ── Vivaan Nair (Male, Indore)
            [
                'name'            => 'Vivaan Nair',
                'email'           => 'vivaan@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[0]->id,
                'bio'             => 'Theatre artist and cultural enthusiast in Indore. Live events, plays, art gallery openings, and deep dining conversations.',
                'hourly_rate'     => 2100.00,
                'profile_picture' => 'images/profiles/partner_11_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [0, 1, 2],
            ],
            // 12 ── Pihu Mishra (Female, Bhopal)
            [
                'name'            => 'Pihu Mishra',
                'email'           => 'pihu@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[1]->id,
                'bio'             => 'Nature lover and hiking guide in Bhopal. Morning trails, lake walks, photography sessions, mental wellness over coffee.',
                'hourly_rate'     => 1950.00,
                'profile_picture' => 'images/profiles/partner_12_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [1, 5],
            ],
            // 13 ── Kabir Singh (Male, Jabalpur)
            [
                'name'            => 'Kabir Singh',
                'email'           => 'kabir@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[2]->id,
                'bio'             => 'Cycling enthusiast and gym buddy based in Jabalpur. Cycling tracks, gym workouts, or grab healthy snacks together.',
                'hourly_rate'     => 2300.00,
                'profile_picture' => 'images/profiles/partner_13_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 4,
                'services'        => [10, 12],
            ],
            // 14 ── Isha Sen (Female, Gwalior)
            [
                'name'            => 'Isha Sen',
                'email'           => 'isha@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[3]->id,
                'bio'             => 'Singer and music companion in Gwalior. Local live music events, acoustic sessions, or retro music discussions over dining.',
                'hourly_rate'     => 2050.00,
                'profile_picture' => 'images/profiles/partner_14_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 2,
                'services'        => [1, 2],
            ],
            // 15 ── Sai Kulkarni (Male, Ujjain)
            [
                'name'            => 'Sai Kulkarni',
                'email'           => 'sai@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[4]->id,
                'bio'             => 'Local food guide and movie enthusiast in Ujjain. Cultural stories, historic sites, and weekend dining talks.',
                'hourly_rate'     => 1750.00,
                'profile_picture' => 'images/profiles/partner_15_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [0, 1, 4],
            ],
            // 16 ── Meera Nair (Female, Sagar)
            [
                'name'            => 'Meera Nair',
                'email'           => 'meera@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[5]->id,
                'bio'             => 'English tutor and library companion in Sagar. Study hours, book reviews, conversational English, or walks in local parks.',
                'hourly_rate'     => 1650.00,
                'profile_picture' => 'images/profiles/partner_16_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 2,
                'services'        => [1, 7, 8],
            ],
            // 17 ── Rohan Das (Male, Dewas)
            [
                'name'            => 'Rohan Das',
                'email'           => 'rohan@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[6]->id,
                'bio'             => 'Badminton player and fitness buddy in Dewas. Sports matches, cardio runs, or gym workouts — count me in!',
                'hourly_rate'     => 2400.00,
                'profile_picture' => 'images/profiles/partner_17_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [10, 12],
            ],
            // 18 ── Pooja Hegde (Female, Satna)
            [
                'name'            => 'Pooja Hegde',
                'email'           => 'pooja@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[7]->id,
                'bio'             => 'Graphic designer and shopping helper in Satna. Cafe chats, art stores, room decor shopping, or museum tours.',
                'hourly_rate'     => 2100.00,
                'profile_picture' => 'images/profiles/partner_18_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 2,
                'services'        => [1, 4],
            ],
            // 19 ── Krishna Rao (Male, Ratlam)
            [
                'name'            => 'Krishna Rao',
                'email'           => 'krishna@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[8]->id,
                'bio'             => 'Student counselor and mock interviewer in Ratlam. Public speaking, coding interviews, or career option discussions.',
                'hourly_rate'     => 2800.00,
                'profile_picture' => 'images/profiles/partner_19_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 4,
                'services'        => [7, 9],
            ],
            // 20 ── Sneha Reddy (Female, Rewa)
            [
                'name'            => 'Sneha Reddy',
                'email'           => 'sneha@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[9]->id,
                'bio'             => 'Avid reader and language buddy. Hindi conversational skills, classic novels, or study sessions in Rewa.',
                'hourly_rate'     => 1900.00,
                'profile_picture' => 'images/profiles/partner_20_female.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [7, 8],
            ],
            // 21 ── Aditya Sen (Male, Indore)
            [
                'name'            => 'Aditya Sen',
                'email'           => 'adityasen@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[0]->id,
                'bio'             => 'Weekend hiker and local explorer in Indore. Best hiking paths and nearby waterfalls. Let\'s hike together!',
                'hourly_rate'     => 2600.00,
                'profile_picture' => 'images/profiles/partner_21_male.jpg',
                'kyc_status'      => 'approved',
                'experience_years'=> 3,
                'services'        => [5],
            ],
            // 22 ── Ishaan Verma (Male, Bhopal) — Pending KYC
            [
                'name'            => 'Ishaan Verma',
                'email'           => 'ishaan@partner.com',
                'gender'          => 'male',
                'city_id'         => $cities[1]->id,
                'bio'             => 'Local guide with deep knowledge of Bhopal\'s lakes, art galleries, and historic sites. Ready for sightseeing and road trips!',
                'hourly_rate'     => 3000.00,
                'profile_picture' => 'images/profiles/partner_22_male.jpg',
                'kyc_status'      => 'pending',
                'experience_years'=> 3,
                'services'        => [4, 6],
            ],
            // 23 ── Manya Sen (Female, Gwalior) — Rejected KYC
            [
                'name'            => 'Manya Sen',
                'email'           => 'manya@partner.com',
                'gender'          => 'female',
                'city_id'         => $cities[3]->id,
                'bio'             => 'Mindfulness teacher and gym motivator in Gwalior. Morning runs, tennis matches, and healthy chats. Let\'s stay active together!',
                'hourly_rate'     => 2400.00,
                'profile_picture' => 'images/profiles/partner_23_female.jpg',
                'kyc_status'      => 'rejected',
                'kyc_notes'       => 'Documents blurry. Please upload clear ID proofs.',
                'experience_years'=> 2,
                'services'        => [10, 11],
            ],
        ];

        foreach ($partnersData as $index => $pData) {
            $partner = User::create([
                'name'            => $pData['name'],
                'email'           => $pData['email'],
                'password'        => Hash::make('password'),
                'role'            => 'partner',
                'phone'           => '+9199' . str_pad(($index + 10000000), 8, '0', STR_PAD_LEFT),
                'gender'          => $pData['gender'],
                'is_active'       => true,
                'city_id'         => $pData['city_id'],
                'profile_picture' => $pData['profile_picture'],
            ]);

            $dbCity = \App\Models\City::find($pData['city_id']);
            $cityName = $dbCity ? $dbCity->name : 'Bhopal';
            $stateName = ($dbCity && $dbCity->state) ? $dbCity->state->name : 'Madhya Pradesh';

            $cityCoordinates = [
                'indore'   => [22.7196, 75.8577],
                'bhopal'   => [23.2599, 77.4126],
                'jabalpur' => [23.1815, 79.9864],
                'gwalior'  => [26.2183, 78.1828],
                'ujjain'   => [23.1760, 75.7885],
                'sagar'    => [23.8388, 78.7378],
                'dewas'    => [22.9676, 76.0534],
                'satna'    => [24.5774, 80.8322],
                'ratlam'   => [23.3315, 75.0367],
                'rewa'     => [24.5362, 81.3037],
            ];
            $slug = strtolower($cityName);
            $coords = $cityCoordinates[$slug] ?? [23.2599, 77.4126];

            CompanionProfile::create([
                'user_id'          => $partner->id,
                'bio'              => $pData['bio'],
                'hourly_rate'      => $pData['hourly_rate'],
                'rating'           => 0.00,
                'kyc_status'       => $pData['kyc_status'],
                'kyc_notes'        => $pData['kyc_notes'] ?? null,
                'experience_years' => $pData['experience_years'],
                'bank_holder_name' => $pData['name'],
                'bank_account_number' => '12345678' . str_pad($index, 4, '0', STR_PAD_LEFT),
                'bank_ifsc'        => 'ICIC0000123',
                'bank_name'        => 'ICICI Bank',
                'languages'        => ['English', 'Hindi'],
                'interests'        => ['Travel', 'Reading', 'Music'],
                'country'          => 'India',
                'state'            => $stateName,
                'city'             => $cityName,
                'area'             => 'MP Nagar',
                'latitude'         => $coords[0],
                'longitude'        => $coords[1],
            ]);

            DocumentVerification::create([
                'user_id'       => $partner->id,
                'aadhaar_front' => 'kyc/mock_aadhaar_front.jpg',
                'aadhaar_back'  => 'kyc/mock_aadhaar_back.jpg',
                'pan_card'      => 'kyc/mock_pan_card.jpg',
                'selfie'        => 'kyc/mock_selfie.jpg',
                'aadhaar_status'=> $pData['kyc_status'],
                'pan_status'    => $pData['kyc_status'],
                'selfie_status' => $pData['kyc_status'],
                'notes'         => $pData['kyc_notes'] ?? null,
            ]);

            // Also seed dummy availability slots for verified partners to let them be marked as fully onboarded!
            if ($pData['kyc_status'] === 'approved') {
                foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $day) {
                    \App\Models\Availability::create([
                        'user_id' => $partner->id,
                        'day' => $day,
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                        'is_available' => true,
                    ]);
                }
            }

            foreach ($pData['services'] as $serIndex) {
                if (isset($servicesList[$serIndex])) {
                    $partner->services()->attach($servicesList[$serIndex]->id);
                }
            }
        }

        // Fetch partners for bookings
        $priya  = User::where('email', 'priya@partner.com')->first();
        $aarav  = User::where('email', 'aarav@partner.com')->first();
        $aditya = User::where('email', 'aditya@partner.com')->first();

        // 6. Seed Bookings & Reviews
        $b1 = Booking::create([
            'customer_id'      => $customer1->id,
            'partner_id'       => $priya->id,
            'booking_date'     => now()->subDays(5)->format('Y-m-d'),
            'start_time'       => '18:00:00',
            'duration_hours'   => 3,
            'hourly_rate'      => $priya->companionProfile->hourly_rate,
            'total_amount'     => $priya->companionProfile->hourly_rate * 3,
            'status'           => 'completed',
            'location_address' => 'Chappan Dukan, Indore',
            'description'      => 'Help me celebrate my birthday dinner with a foodie discussion.',
        ]);
        Review::create([
            'booking_id'  => $b1->id,
            'customer_id' => $customer1->id,
            'partner_id'  => $priya->id,
            'rating'      => 5,
            'comment'     => 'Priya was an incredible dining companion! She shared fascinating facts about food pairing. 10/10!',
        ]);

        $b2 = Booking::create([
            'customer_id'      => $customer2->id,
            'partner_id'       => $priya->id,
            'booking_date'     => now()->subDays(2)->format('Y-m-d'),
            'start_time'       => '09:00:00',
            'duration_hours'   => 2,
            'hourly_rate'      => $priya->companionProfile->hourly_rate,
            'total_amount'     => $priya->companionProfile->hourly_rate * 2,
            'status'           => 'completed',
            'location_address' => 'Gold\'s Gym, Indore',
            'description'      => 'Need a gym spotter and workout tips.',
        ]);
        Review::create([
            'booking_id'  => $b2->id,
            'customer_id' => $customer2->id,
            'partner_id'  => $priya->id,
            'rating'      => 4,
            'comment'     => 'Great session, really pushed my limits. Very professional!',
        ]);

        $priyaProfile = $priya->companionProfile;
        $priyaProfile->rating = 4.5;
        $priyaProfile->save();

        $b3 = Booking::create([
            'customer_id'      => $customer1->id,
            'partner_id'       => $aarav->id,
            'booking_date'     => now()->subDays(10)->format('Y-m-d'),
            'start_time'       => '14:00:00',
            'duration_hours'   => 4,
            'hourly_rate'      => $aarav->companionProfile->hourly_rate,
            'total_amount'     => $aarav->companionProfile->hourly_rate * 4,
            'status'           => 'completed',
            'location_address' => 'State Museum, Bhopal',
            'description'      => 'Touring the state museum and having a cafe talk.',
        ]);
        Review::create([
            'booking_id'  => $b3->id,
            'customer_id' => $customer1->id,
            'partner_id'  => $aarav->id,
            'rating'      => 5,
            'comment'     => 'Aarav was incredibly knowledgeable about art history and very engaging! Highly recommend.',
        ]);

        $aaravProfile = $aarav->companionProfile;
        $aaravProfile->rating = 5.0;
        $aaravProfile->save();

        Booking::create([
            'customer_id'      => $customer2->id,
            'partner_id'       => $aditya->id,
            'booking_date'     => now()->addDays(3)->format('Y-m-d'),
            'start_time'       => '08:00:00',
            'duration_hours'   => 5,
            'hourly_rate'      => $aditya->companionProfile->hourly_rate,
            'total_amount'     => $aditya->companionProfile->hourly_rate * 5,
            'status'           => 'pending',
            'location_address' => 'Bhedaghat Dhuandhar Falls Hike, Jabalpur',
            'description'      => 'Let\'s hike the main trail. I want to improve my conversational Hindi.',
        ]);

        Booking::create([
            'customer_id'      => $customer3->id,
            'partner_id'       => $priya->id,
            'booking_date'     => now()->addDays(1)->format('Y-m-d'),
            'start_time'       => '19:00:00',
            'duration_hours'   => 2,
            'hourly_rate'      => $priya->companionProfile->hourly_rate,
            'total_amount'     => $priya->companionProfile->hourly_rate * 2,
            'status'           => 'approved',
            'location_address' => 'Sayaji Cafe, Indore',
            'description'      => 'Quick catchup chat.',
        ]);
    }
}
