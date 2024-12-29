<?php

/**
 * Create the program that the computer is trying to run.
 */
function mul ($val1, $val2)
{
  return $val1 * $val2;
}

/**
 * Given a path to a file, parse it for `mul(d,d)` and return an array
 * of matches.
 */
function parseInput ($path, $conditionalFlag = false)
{
  $data = file_get_contents($path);

  if ($conditionalFlag) {
    // Strip sections in between don’t and do.
    //$data = preg_replace('/don\'t\(\).*do\(\)/s', 'do()', $data);

    // Well... that didn’t work and... I’m not sure why. Seems like the reg
    // ex should work (and it does) and that the solution is conceptually
    // sound, but per the site, the sum I submitted is too low.

    // So... we’re just going to iterate through multiple times looking for
    // don’ts and from there, the next do.
    // And... that worked the first time, so.... yeah.
    $test = true;

    while ($test) {
      $startOffset = mb_strpos($data, 'don\'t()');

      if ($startOffset === false) {
        $test = false;
        break;
      }

      $endOffset = mb_strpos($data, 'do()', $startOffset);

      if ($endOffset) {
        $data = substr_replace($data, '', $startOffset,
            $endOffset - $startOffset);
      } else {
        $data = substr_replace($data, '', $startOffset);
      }
    }
  }

  preg_match_all('/mul\(\d+\,\d+\)/', $data, $matches);

  return $matches[0];
}

// Solution to problem 3-1
function getSumOfMuls ($conditionalFlag = false)
{
  $sum = 0;

  $matches = parseInput('./input.txt', $conditionalFlag);

  if ($matches) {
    foreach ($matches as $match) {
      $sum += eval('return ' . $match . ';');
    }
  }

  echo "\nThe sum is {$sum}\n";
}

// CLI runner
if (isset($argv[1])) {
  if ($argv[1] === '3-1') {
    getSumOfMuls();
  }

  if ($argv[1] === '3-2') {
    getSumOfMuls(true);
  }
}

