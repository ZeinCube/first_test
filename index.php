<?php

function getDirectoriesToWalk(string $directory): array
{
    $directories = array_diff(scandir($directory), ['..', '.']);
    $directories = array_filter($directories, static function ($item) use ($directory) {
        return is_dir($directory . '/' . $item);
    });

    $realDirectories = [];

    foreach ($directories as $value) {
        $realDirectories[] = $directory . '/' . $value;
    }

    return $realDirectories;
}

$searchingDirectory = $argv[1];

if (!is_dir($searchingDirectory)) {
    echo 'Not a directory';
    die(1);
}

$sum = 0;

$directoriesToWalk []= $searchingDirectory;

while (count($directoriesToWalk) > 0)
{
    foreach ($directoriesToWalk as $index => $walkingDir) {

        $countFile = $walkingDir. '/count';
        if (is_file($countFile)) {
            $sum += (int)file_get_contents($countFile);
        }

        unset($directoriesToWalk[$index]);

        $innerDirectories = getDirectoriesToWalk($walkingDir);

        array_push($directoriesToWalk, ...$innerDirectories);
        echo "Current using memory (KB): "  . memory_get_usage() / 1024 . "\n";
    }
}

echo "TOTAL SUM: " . $sum .  "\n";
echo "Max memory size (MB): " . memory_get_peak_usage() / 1024 / 1024;