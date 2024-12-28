<?php
/**
 * Second day in a row that both problems use the same input, so this
 * directory structure is inefficient. Let’s change it tomorrow.
 */

function isSafe ($levels)
{
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

  return $safe;
}

$safeCount = 0;

$handle = fopen('./input.txt', 'r');

if ($handle) {
  // Each line is a report.
  while (($line = fgets($handle)) !== false) {
    $line = trim($line);

    // Each report contains levels.
    $levels = explode(' ', $line);

    // If we’re safe, increase the count.
    if (isSafe($levels)) {
      $safeCount++;

    // If we’re not safe, try again by removing one level at a time.
    } else {
      for ($i = 0, $l = count($levels); $i < $l; $i++) {
        // Copy the levels array.
        $newLevels = $levels;
        // Remove a level.
        array_splice($newLevels, $i, 1);

        // If we can find one safe version with a missing level,
        // we’re good.
        if (isSafe($newLevels)) {
          $safeCount++;
          break;
        }
      }
    }
  }

  fclose($handle);
}

echo "\nThe total number of safe reports is {$safeCount}\n";
