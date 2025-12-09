<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use App\Models\FaqCategory;
use App\Models\Faq;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\ContactMessage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default admin (store in $admin for reuse)
        $admin = User::firstOrCreate(
            ['email' => 'admin@ehb.be'],
            [
                'is_admin' => true,
                'name' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('Password!321'),
            ]
        );

        // Default normal users
        $alice = User::firstOrCreate(
            ['email' => 'alice.johnson@example.com'],
            [
                'name' => 'Alice Johnson',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        $bob = User::firstOrCreate(
            ['email' => 'bob.smith@example.com'],
            [
                'name' => 'Bob Smith',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        // News
        News::factory()->count(4)->create();

        // Programs
        $this->call(ProgramSeeder::class);

        // Resources
        $resourceCategories = [
            'Systematic Theology' => 'Resources exploring doctrine and the organized study of Christian beliefs.',
            'Biblical Theology' => 'Resources focusing on theological themes and narratives across the Bible.',
            'Apologetics' => 'Defenses of the Christian faith and answers to common objections.',
            'Church History' => 'Materials covering the historical development of the church and its movements.',
            'Denominations' => 'Information about different Christian denominations and their distinctions.',
            'Pastoral' => 'Pastoral care, counseling, and ministry resources for church leaders.',
            'Sermons' => 'Collections of sermon transcripts, outlines, and recordings for preaching and study.',
            'Science' => 'Discussions and resources at the intersection of science and faith.',
            'Philosophy' => 'Philosophical works and reflections relevant to theology and apologetics.',
            'Devotional' => 'Daily devotionals and spiritual formation materials for personal growth.',
            'Revival' => 'Resources related to revival movements, spiritual renewal, and awakening.',
        ];

        foreach ($resourceCategories as $name => $description) {
            ResourceCategory::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // Create 4 resources assigned to random existing resource categories
        $resourceCategoryIds = ResourceCategory::pluck('id')->toArray();
        if (!empty($resourceCategoryIds)) {
            Resource::factory()->count(4)->make()->each(function ($resource) use ($resourceCategoryIds) {
                $resource->resource_category_id = $resourceCategoryIds[array_rand($resourceCategoryIds)];
                $resource->save();
            });
        }

        // Attach comments to 3 recent resources
        $sampleResources = Resource::orderBy('created_at', 'desc')->take(3)->get();
        if ($sampleResources->isNotEmpty()) {
            $r0 = $sampleResources[0];

            Comment::firstOrCreate([
                'user_id' => $alice->id,
                'resource_id' => $r0->id,
                'body' => 'This was a really helpful overview — clarified several questions I had about the doctrine. Thanks for sharing!'
            ]);

            if (isset($sampleResources[1])) {
                $r1 = $sampleResources[1];
                Comment::firstOrCreate([
                    'user_id' => $bob->id,
                    'resource_id' => $r1->id,
                    'body' => 'I appreciated the historical context here. Would love references to primary sources if anyone has recommendations.'
                ]);
            }

            if (isset($sampleResources[2])) {
                $r2 = $sampleResources[2];
                // Use the $admin created above
                Comment::firstOrCreate([
                    'user_id' => $admin->id,
                    'resource_id' => $r2->id,
                    'body' => 'We plan to add a follow-up guide to this resource next month — stay tuned.'
                ]);
            }
        }

        // FAQ
        $faqCategories = [
            'Systematic Theology' => 'Questions about core doctrines and theological systems.',
            'Biblical Theology' => 'Questions about biblical themes, interpretation, and context.',
            'Apologetics' => 'Common objections and concise answers defending the faith.',
            'Church History' => 'Questions regarding events, movements, and figures in church history.',
            'Denominations' => 'Questions about practices and beliefs of different Christian traditions.',
            'Worship' => 'Questions about worship services, liturgy, music, and corporate practice.',
        ];

        foreach ($faqCategories as $name => $description) {
            FaqCategory::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // Create 4 FAQs assigned to random existing FAQ categories
        $faqCategoryIds = FaqCategory::pluck('id')->toArray();
        if (!empty($faqCategoryIds)) {
            Faq::factory()->count(4)->make()->each(function ($faq) use ($faqCategoryIds) {
                $faq->faq_category_id = $faqCategoryIds[array_rand($faqCategoryIds)];
                $faq->save();
            });
        }

        // Contact messages
        ContactMessage::firstOrCreate(
            ['email' => 'alice@example.com', 'message' => 'I would like to know more about your programs.'],
            ['name' => 'Alice']
        );

        ContactMessage::firstOrCreate(
            ['email' => 'bob@example.com', 'message' => 'Can you provide the schedule for next Sunday?'],
            ['name' => 'Bob']
        );

        ContactMessage::firstOrCreate(
            ['email' => 'carol@example.com', 'message' => 'I am interested in volunteering.'],
            ['name' => 'Carol']
        );
    }
}
