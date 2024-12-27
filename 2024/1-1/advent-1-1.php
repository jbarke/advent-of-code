<?php

/**
 * Open text file, parse and return two arrays.
 * Don’t really care about reuse, so just making this hacky (hardcoded paths)
 * and procedural.
 */
function getInput ()
{
  $ret = [
    [],
    [],
  ];

  $handle = fopen('./input.txt', 'r');

  if ($handle) {
    while (($line = fgets($handle)) !== false) {
      $line = trim($line);

      $pieces = explode('   ', $line);

      if (count($pieces) === 2) {
        $ret[0][] = $pieces[0];
        $ret[1][] = $pieces[1];
      }
    }

    fclose($handle);
  }

  sort($ret[0], SORT_NUMERIC);
  sort($ret[1], SORT_NUMERIC);

  return $ret;
}

/**
 * Calculate the total “distance” between elements in two arrays.
 * Distance is defined and getting the difference between the corresponding
 * elements and summing them.
 */
function calculateDistance ($arr1, $arr2)
{
  // The two arrays must be the same size.
  $l = count($arr1);
  $l2 = count($arr2);

  $distance = 0;

  if ($l === $l2) {
    for ($i = 0; $i < $l; $i++) {
      $diff = abs($arr2[$i] - $arr1[$i]);

      $distance += $diff;
    }
  }

  return $distance;
}

// $data will be an array of sorted arrays.
$data = getInput();

$distance = calculateDistance($data[0], $data[1]);

echo "\nThe distance is {$distance}\n";
