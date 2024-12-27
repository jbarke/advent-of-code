<?php

/**
 * Eh. 1-2 shared more with 1-1 than I expected, so copying over the input.txt
 * and the `getInput` function. If they continue to be this similar, I’ll get
 * “smarter” about this and make the code less hacky.
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

  // 1-1 required soring; 1-2 doesn’t, but it doesn’t really hurt.
  sort($ret[0], SORT_NUMERIC);
  sort($ret[1], SORT_NUMERIC);

  return $ret;
}

function calculateSimilarity ($arr1, $arr2)
{
  $score = 0;

  for ($i = 0, $l = count($arr1); $i < $l; $i++) {
    $num = $arr1[$i];
    $count = 0;

    for ($j = 0, $m = count($arr2); $j < $m; $j++) {
      if ($arr2[$j] === $num) {
        $count++;
      }
    }

    $score += ($num * $count);
  }

  return $score;
}

// $data will be an array of sorted arrays.
$data = getInput();

$score = calculateSimilarity($data[0], $data[1]);

echo "\nThe similarity is {$score}\n";
