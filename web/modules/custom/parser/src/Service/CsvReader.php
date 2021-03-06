<?php

namespace Drupal\parser\Service;

/**
 * Class CsvReader.
 *
 * Parses a given csv file and returns an array.
 */
class CsvReader {

  /**
   * Parses csv file with php function str_getcsv.
   */
  public function parse($csvFile) {
    if (!is_file($csvFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $csvFile));
    }
    if (!is_readable($csvFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $csvFile));
    }
    return array_map('str_getcsv', file($csvFile));
  }

}
