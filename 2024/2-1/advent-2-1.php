<?php
/**
 * Eh. Let’s start by just making this one totally procedural.
 */
$safeCount = 0;

$handle = fopen('./input.txt', 'r');

if ($handle) {
  // Each line is a report.
  while (($line = fgets($handle)) !== false) {
    $line = trim($line);

    // Each report contains levels.
    $levels = explode(' ', $line);

    // Asssume it’s safe.
    $safe = true;
    $direction = '';

    for ($i = 1, $l = count($levels); $i < $l; $i++) {
      // Signed.
      $diff = $levels[$i] - $levels[$i - 1];

      // Direction must be consistent.
      if ($diff < 0) {
        $currDirection = 'decrease';
      } elseif ($diff > 0) {
        $currDirection = 'increase';
      }

      if (
        $direction !== '' &&
        $currDirection !== $direction
      ) {
        $safe = false;
        break;
      }

      // Levels must differ by at least one and not more than three.
      $diff = abs($diff);
      if ($diff < 1 || $diff > 3) {
        $safe = false;
        break;
      }

      // Set our vars for the next loop.
      $direction = $currDirection;
      $currDirection = '';
    }

    // If we’re still safe, increase the count.
    if ($safe === true) {
      $safeCount++;
    }
  }

  fclose($handle);
}

echo "\nThe total number of safe reports is {$safeCount}\n";
