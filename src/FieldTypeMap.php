<?php

namespace webflo\PHPStanDrupalFields;

use DrupalFinder\DrupalFinder;
use Nette\Neon\Neon;

class FieldTypeMap {

  protected array $map;

  public function __construct() {
    $drupalFinder = new DrupalFinder();
    if ($drupalFinder->locateRoot(getcwd())) {
      $composerRoot = $drupalFinder->getComposerRoot();
      if ($composerRoot === FALSE) {
        throw new \RuntimeException('Unable to locate composer root.');
      }
    }
    $this->map = Neon::decodeFile($composerRoot . '/drupal.entity-metadata.neon');
  }

  /**
   * @deprecated
   */
  public function getFieldType() {
    return $this->map;
  }

  public function getEntityTypes() : array {
    return $this->map['parameters']['drupal']['entityFieldMapping'] ?? [];
  }

  public function getFields($entityTypeId) : array|FALSE {
    return $this->map['parameters']['drupal']['entityFieldMapping'][$entityTypeId]['fields'] ?? FALSE;
  }

  public function getFieldDefinition($entityTypeId, $fieldName) : array|FALSE {
    return $this->map['parameters']['drupal']['entityFieldMapping'][$entityTypeId]['fields'][$fieldName] ?? FALSE;
  }

  public function getTypeFormEntityClass(string $className) : string|FALSE {
    foreach ($this->map['parameters']['drupal']['entityFieldMapping'] as $id => $entityType) {
      if ($entityType['type']['class'] === $className) {
        return $id;
      }
    }
    return FALSE;
  }

}
