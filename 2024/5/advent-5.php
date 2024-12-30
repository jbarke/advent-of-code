<?php

function checkUpdatesAgainstRules ($rules, $updates, $valid = true)
{
  // Return array of filtered updates.
  $ret = [];

  // Iterate through the updates.
  foreach ($updates as $update) {
    // Reset the test per loop.
    $test = $valid;

    // Each update is an array of pages.
    for ($i = 0, $l = count($update); $i < $l; $i++) {
      // Need to re-loop through the pages to check each against each.
      for ($j = 0; $j < $l; $j++) {
        // We don’t need to check a page against itself.
        if ($i !== $j) {
          // This page must come after the current page.
          if ($j > $i) {
            if (!in_array($update[$j], $rules[$update[$i]])) {
              $test = !$valid;
            }

          // This page must come before the current page.
          } elseif ($j < $i) {
            if (!in_array($update[$i], $rules[$update[$j]])) {
              $test = !$valid;
            }
          }
        }
      }
    }

    // If the update passed the test, add to our return array.
    if ($test === true) {
      $ret[] = $update;
    }
  }

  return $ret;
}

function getPageOrderingRules ($path)
{
  $ret = [];

  $handle = fopen($path, 'r');

  $test = true;

  if ($handle) {
    while (($line = fgets($handle)) !== false) {
      $line = trim($line);

      // An empty line is the delimiter for the report.
      if (!$line) {
        $test = false;
      }

      if ($test === true) {
        $pieces = explode('|', $line);

        if (!isset($ret[$pieces[0]])) {
          $ret[$pieces[0]] = [];
        }

        $ret[$pieces[0]][] = $pieces[1];
      }
    }
  }

  return $ret;
}

function getUpdates ($path)
{
  $ret = [];

  $handle = fopen($path, 'r');

  $test = false;

  if ($handle) {
    while (($line = fgets($handle)) !== false) {
      $line = trim($line);

      if ($test === true) {
        $ret[] = explode(',', $line);
      }

      // An empty line is the delimiter for the report.
      if (!$line) {
        $test = true;
      }
    }
  }

  return $ret;
}

function sumMiddlePages ($updates)
{
  $sum = 0;

  foreach ($updates as $update) {
    $total = count($update);

    $sum += $update[floor($total / 2)];
  }

  return $sum;
}

// CLI runner
if (isset($argv[1])) {
  if ($argv[1] === '5-1') {
    // Parse out of the input.txt.
    $rules = getPageOrderingRules('./input.txt');
    // Parse out of the input.txt.
    $updates = getUpdates('./input.txt');
    // Filter for valid updates.
    $updates = checkUpdatesAgainstRules($rules, $updates, true);

    echo 'The sume of the middle pages is: ' . sumMiddlePages($updates) . "\n";
  }
}
