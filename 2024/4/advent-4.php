<?php

/**
 * Ok, I was stupid about this one.
 * Should have gone with the multidimensional array from the get-go.
 * Once that was complete, do the search by letter with the direction offsets.
 * This means two things:
 * 1. Don’t mix with the regular expressions
 * 2. If we’re matching based on the start letter and then moving directions,
 *    no need to reverse the word. duh.
 *
 * So, refactor this.
 */

/**
 * Read a file into a two-dimensional array.
 */
function getInputIntoArray ($path)
{
  $ret = [];

  $handle = fopen($path, 'r');

  if ($handle) {
    while (($line = fgets($handle)) !== false) {
      $line = trim($line);

      $ret[] = mb_str_split($line);
    }

    fclose($handle);
  }

  return $ret;
}

function checkForWord ($data, $wordArr, $x, $y, $direction)
{
  $test = true;
  $i = 1;
  $l = count($wordArr);

  while ($test === true) {
    // We successfully found the word if we made it here.
    if ($i >= $l) {
      $test = false;
      break;
    }

    // Set the offsets based on the direction and the current iteration.
    switch ($direction) {
      case 'diagonal-ne':
        $offsetX = $x + $i;
        $offsetY = $y - $i;
        break;

      case 'diagonal-nw':
        $offsetX = $x - $i;
        $offsetY = $y - $i;
        break;

      case 'diagonal-se':
        $offsetX = $x + $i;
        $offsetY = $y + $i;
        break;

      case 'diagonal-sw':
        $offsetX = $x - $i;
        $offsetY = $y + $i;
        break;

      case 'down':
        $offsetX = $x;
        $offsetY = $y + $i;
        break;

      case 'up':
        $offsetX = $x;
        $offsetY = $y - $i;
        break;
    }

    // No match; we can bail
    if (!(
      isset($data[$offsetY]) &&
      isset($data[$offsetY][$offsetX]) &&
      ($data[$offsetY][$offsetX] === $wordArr[$i])
    )) {
      return 0;
    }

    // Increase iteration count for offsets.
    $i++;
  }

  return 1;
}

function wordSearch ($word, $path = './input.txt')
{
  $wordArr = mb_str_split($word);

  // We’re looking for the word forwards and backwards.
  $revWord = strrev($word);
  $revWordArr = mb_str_split($revWord);

  $count = 0;

  $data = getInputIntoArray($path);

  for ($y = 0, $l = count($data); $y < $l; $y++) {
    $row = $data[$y];

    $line = implode('', $row);

    // Search for the word horizontally.
    // Can’t use `substr_count` because it ignores overlapped words
    // and, per instructions, these can be. Actually, this particular word
    // probably can’t overlap in such a way that we couldn’t use
    // `substr_count`, but whatever.
    $count += preg_match_all('/' . $word . '/', $line);

    // Search for the word reversed horizontally.
    $count += preg_match_all('/' . $revWord . '/', $line);

    // Actual search; multidimensional array.
    for ($x = 0, $m = count($row); $x < $m; $x++) {
      // If the current letter matches our word start letter, ...
      if ($row[$x] === $wordArr[0]) {
        $count += checkForWord($data, $wordArr, $x, $y, 'down');
        $count += checkForWord($data, $wordArr, $x, $y, 'up');
        $count += checkForWord($data, $wordArr, $x, $y, 'diagonal-se');
        $count += checkForWord($data, $wordArr, $x, $y, 'diagonal-sw');
        $count += checkForWord($data, $wordArr, $x, $y, 'diagonal-ne');
        $count += checkForWord($data, $wordArr, $x, $y, 'diagonal-nw');
      }

      // If the current letter matches our reverse word start letter, ...
      /*if ($row[$x] === $revWordArr[0]) {
        $count += checkForWord($data, $revWordArr, $x, $y, 'down');
        $count += checkForWord($data, $revWordArr, $x, $y, 'up');
        $count += checkForWord($data, $revWordArr, $x, $y, 'diagonal-se');
        $count += checkForWord($data, $revWordArr, $x, $y, 'diagonal-sw');
        $count += checkForWord($data, $revWordArr, $x, $y, 'diagonal-ne');
        $count += checkForWord($data, $revWordArr, $x, $y, 'diagonal-nw');
      }*/
    }
  }

  return $count;
}

// CLI runner
if (isset($argv[1])) {
  if ($argv[1] === '4-1') {
    echo 'XMAS shows up ' . wordSearch('XMAS') . ' times.' . "\n";
  }
}
