<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BibleService;

class BuildBibleIndex extends Command
{
    protected $signature = 'bible:index {--translation=WEB : Translation folder name}';
    protected $description = 'Build a books index (book name and chapter count) from per-chapter JSON files under storage/app/bible/{translation}';

    public function handle(): int
    {
        $translation = $this->option('translation') ?: 'WEB';
        $dir = storage_path('app/bible/' . $translation);
        if (!is_dir($dir)) {
            $this->error("Directory not found: $dir");
            return 1;
        }

        // Use service to build index
        $index = BibleService::buildIndexFromFolder($translation);

        // Reorder using the shared service
        $final = BibleService::reorderIndexToCanonical($index);

        $outPath = $dir . DIRECTORY_SEPARATOR . 'books_index.json';
        file_put_contents($outPath, json_encode($final, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info('Wrote index to: ' . $outPath . ' (' . count($final) . ' books)');
        return 0;
    }
}
