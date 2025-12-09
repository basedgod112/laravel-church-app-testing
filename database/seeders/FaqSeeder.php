<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FaqCategory;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // Create realistic FAQs for each category
        $faqs = [
            'Systematic Theology' => [
                [
                    'question' => 'What does the doctrine of the Trinity mean?',
                    'answer' => "The Trinity teaches that there is one God who exists in three distinct persons — Father, Son, and Holy Spirit — each fully divine yet sharing the same divine essence. This is a way Christians express biblical witness to God's unity and the personal distinctions revealed in Scripture.",
                ],
                [
                    'question' => 'How are justification and sanctification different?',
                    'answer' => "Justification is a one-time legal declaration by God that a person is righteous through faith in Christ. Sanctification is the ongoing process by which a believer grows in holiness and Christlikeness through the Spirit's work and the believer's repentance and obedience.",
                ],
            ],
            'Biblical Theology' => [
                [
                    'question' => 'How should I read Old Testament promises in light of the New Testament?',
                    'answer' => "Many Old Testament promises find their fulfillment in Christ and the new covenant; reading them through the storyline of Scripture (creation, fall, redemption, restoration) helps show how earlier texts point forward to Christ while retaining their original context and meaning.",
                ],
            ],
            'Apologetics' => [
                [
                    'question' => 'Does belief in God conflict with science?',
                    'answer' => "No — many Christians see science and faith as complementary. Science explains how the natural world operates; faith addresses questions of purpose, meaning, and moral values. Where tensions appear, careful interpretation of both the scientific data and the biblical text helps clarify the issues.",
                ],
                [
                    'question' => 'How can I answer the problem of evil?',
                    'answer' => "The problem of evil is serious but not insurmountable. Christian responses typically appeal to human freedom and the reality of a broken creation, the promise of redemption, and the hope that God will ultimately address evil. Pastoral sensitivity and honest engagement are important when discussing suffering.",
                ],
            ],
            'Church History' => [
                [
                    'question' => 'What caused the Protestant Reformation?',
                    'answer' => "The Reformation arose from widespread concerns about church practice, authority, and theology in the 16th century — including debates over Scripture, justification, and clerical abuses — and led to renewed emphasis on sola scriptura and justification by faith.",
                ],
            ],
            'Denominations' => [
                [
                    'question' => 'Why are there different denominations?',
                    'answer' => "Denominations developed for theological, historical, cultural, and practical reasons. Christians have often disagreed about doctrine, worship style, church government, and practice; those differences, along with geography and history, produced distinct traditions that emphasize different aspects of faith and practice.",
                ],
            ],
            'Worship' => [
                [
                    'question' => 'What is the purpose of corporate worship?',
                    'answer' => "Corporate worship gathers believers to glorify God, hear Scripture, receive teaching, celebrate the sacraments, pray for one another, and be formed by the rhythms of the church. It's both proclamation and formation, strengthening faith and community.",
                ],
                [
                    'question' => 'How can I prepare for meaningful worship?',
                    'answer' => "Prepare by reading Scripture beforehand, praying for openness to God, arriving with a servant heart for others, and participating actively (not just passively). Small practices like setting aside phones and arriving a few minutes early help create space for focus.",
                ],
            ],
        ];

        foreach ($faqs as $categoryName => $entries) {
            $category = FaqCategory::firstWhere('name', $categoryName);
            if (! $category) continue;

            foreach ($entries as $entry) {
                Faq::firstOrCreate(
                    ['question' => $entry['question']],
                    [
                        'answer' => $entry['answer'],
                        'faq_category_id' => $category->id,
                    ]
                );
            }
        }
    }
}
