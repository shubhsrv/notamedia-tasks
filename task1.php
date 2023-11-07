<?php
/**
 * This script finds and displays files in the /datafiles folder
 * with names consisting of numbers and letters of the Latin alphabet,
 * having the .ixt extension, ordered by name.
 *
 */

/**
 * Function to find matching files in the given directory based on the pattern.
 *
 * @param string $directory Path to the directory to search for files.
 * @param string $pattern Regular expression pattern to match file names.
 * @return array Matching file names.
 */

function findMatchingFiles($directory, $pattern)
{
    $matchingFiles = [];

    if ($handle = opendir($directory)) {
        while (($file = readdir($handle)) !== false) {
            if (preg_match($pattern, $file)) {
                $matchingFiles[] = $file;
            }
        }
        closedir($handle);
    }

    return $matchingFiles;
}

$datafilesDirectory = '/datafiles';

// Regex
$filePattern = '/^[A-Za-z0-9]+\.ixt$/';
$matchingFiles = findMatchingFiles($datafilesDirectory, $filePattern);
sort($matchingFiles);

echo "Matching files in the /datafiles folder:\n";
foreach ($matchingFiles as $fileName) {
    echo $fileName . "\n";
}
