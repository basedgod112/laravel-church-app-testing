<?php

namespace App\Services;

class BibleService
{
    /**
     * Canonical Protestant Bible order.
     * Single source of truth for ordering.
     *
     * @return string[]
     */
    public static function canonical(): array
    {
        return [
            'Genesis','Exodus','Leviticus','Numbers','Deuteronomy','Joshua','Judges','Ruth','1 Samuel','2 Samuel','1 Kings','2 Kings','1 Chronicles','2 Chronicles','Ezra','Nehemiah','Esther','Job','Psalms','Proverbs','Ecclesiastes','Song of Solomon','Isaiah','Jeremiah','Lamentations','Ezekiel','Daniel','Hosea','Joel','Amos','Obadiah','Jonah','Micah','Nahum','Habakkuk','Zephaniah','Haggai','Zechariah','Malachi','Matthew','Mark','Luke','John','Acts','Romans','1 Corinthians','2 Corinthians','Galatians','Ephesians','Philippians','Colossians','1 Thessalonians','2 Thessalonians','1 Timothy','2 Timothy','Titus','Philemon','Hebrews','James','1 Peter','2 Peter','1 John','2 John','3 John','Jude','Revelation'
        ];
    }

    /**
     * Normalize a book name for consistent matching.
     */
    public static function normalize(string $name): string
    {
        $n = mb_strtolower(trim($name));
        $n = preg_replace('/[^a-z0-9]+/u', ' ', $n);
        return preg_replace('/\s+/', ' ', $n);
    }

    /**
     * Scan storage/app/bible/{translation} and build an index array.
     * Returns array of ['book' => string, 'chapters' => array, 'chapter_count' => int]
     */
    public static function buildIndexFromFolder(string $translation): array
    {
        $dir = storage_path("app/bible/$translation");
        if (!is_dir($dir)) {
            return [];
        }

        $files = scandir($dir);
        $books = [];
        foreach ($files as $f) {
            if (!is_file($dir . DIRECTORY_SEPARATOR . $f)) continue;
            if (!str_ends_with($f, '.json')) continue;
            $name = substr($f, 0, -5);
            $parts = explode('_', $name);
            if (count($parts) < 2) continue;
            $chapter = array_pop($parts);
            $bookFile = implode('_', $parts);
            $bookDisplay = str_replace('_', ' ', $bookFile);
            if (!isset($books[$bookDisplay])) $books[$bookDisplay] = [];
            $books[$bookDisplay][] = intval($chapter);
        }

        $index = [];
        foreach ($books as $book => $chapters) {
            sort($chapters, SORT_NUMERIC);
            $index[] = [
                'book' => $book,
                'chapters' => $chapters,
                'chapter_count' => count($chapters),
            ];
        }

        return $index;
    }

    /**
     * Reorder an index array into canonical Bible order.
     * Leaves non-canonical books appended alphabetically.
     *
     * @param array $index  Array of index items (['book'=>..., 'chapters'=>...])
     * @return array
     */
    public static function reorderIndexToCanonical(array $index): array
    {
        // Build normalized map for quick lookup
        $normalizedIndex = [];
        foreach ($index as $item) {
            $normalizedIndex[self::normalize($item['book'])] = $item;
        }

        $ordered = [];
        $usedKeys = [];

        foreach (self::canonical() as $c) {
            $cn = self::normalize($c);
            if (isset($normalizedIndex[$cn])) {
                $ordered[] = $normalizedIndex[$cn];
                $usedKeys[] = $cn;
                continue;
            }

            $foundKey = null;
            foreach ($normalizedIndex as $k => $v) {
                if (in_array($k, $usedKeys, true)) continue;
                if (str_contains($k, $cn) || str_contains($cn, $k)) {
                    $foundKey = $k;
                    break;
                }
            }
            if ($foundKey !== null) {
                $ordered[] = $normalizedIndex[$foundKey];
                $usedKeys[] = $foundKey;
            }
        }

        // Append remaining books alphabetically
        $remaining = [];
        foreach ($index as $item) {
            $key = self::normalize($item['book']);
            if (in_array($key, $usedKeys, true)) continue;
            $remaining[] = $item;
        }

        usort($remaining, function ($a, $b) {
            return strcasecmp($a['book'], $b['book']);
        });

        return array_merge($ordered, $remaining);
    }
}
