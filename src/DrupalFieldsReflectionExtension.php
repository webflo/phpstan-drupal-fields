<?php

namespace webflo\PHPStanDrupalFields;

use mglaman\PHPStanDrupal\Drupal\EntityDataRepository;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;

class DrupalFieldsReflectionExtension implements PropertiesClassReflectionExtension {

  public function __construct(protected EntityDataRepository $entityDataRepository, protected FieldTypeMap $fieldTypeMap) {

  }

  public function hasProperty(ClassReflection $classReflection, string $propertyName): bool {
    $entityType = $this->fieldTypeMap->getTypeFormEntityClass($classReflection->getName());
    if (!$entityType) {
      return FALSE;
    }

    $fieldDefinition = $this->fieldTypeMap->getFieldDefinition($entityType, $propertyName);

    if (!$fieldDefinition) {
      return FALSE;
    }

    return TRUE;
  }

  public function getProperty(ClassReflection $classReflection, string $propertyName): \PHPStan\Reflection\PropertyReflection {
    $entityType = $this->fieldTypeMap->getTypeFormEntityClass($classReflection->getName());
    $fieldDefinition = $this->fieldTypeMap->getFieldDefinition($entityType, $propertyName);
    return FieldItemListPropertyReflection::fromFieldDefinition($classReflection, $propertyName, $fieldDefinition);
  }

}
