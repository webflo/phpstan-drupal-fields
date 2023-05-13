<?php

namespace webflo\PHPStanDrupalFields;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;

class FieldItemListPropertyReflection implements PropertyReflection {

  /** @var ClassReflection */
  private $declaringClass;

  /** @var string */
  private $propertyName;

  protected string $fieldItemListClass;

  public function __construct(ClassReflection $declaringClass, string $propertyName, string $fieldItemListClass) {
    $this->declaringClass = $declaringClass;
    $this->propertyName = $propertyName;
    $this->fieldItemListClass = $fieldItemListClass;
  }

  public static function fromFieldDefinition(ClassReflection $declaringClass, string $propertyName, array $fieldDefinition) {
    return new static($declaringClass, $propertyName, $fieldDefinition['fieldItemListClass']);
  }

  public function getReadableType(): Type {
    return new ObjectType($this->fieldItemListClass);
  }

  public function getWritableType(): Type {
    if ($this->propertyName === 'entity') {
      return new ObjectType('Drupal\Core\Entity\EntityInterface');
    }
    if ($this->propertyName === 'target_id') {
      return new StringType();
    }
    if ($this->propertyName === 'value') {
      return new StringType();
    }

    // Fallback.
    return new NullType();
  }

  public function canChangeTypeAfterAssignment(): bool {
    return TRUE;
  }

  public function getDeclaringClass(): ClassReflection {
    return $this->declaringClass;
  }

  public function isStatic(): bool {
    return FALSE;
  }

  public function isPrivate(): bool {
    return FALSE;
  }

  public function isPublic(): bool {
    return TRUE;
  }

  public function isReadable(): bool {
    return TRUE;
  }

  public function isWritable(): bool {
    return TRUE;
  }

  public function getDocComment(): ?string {
    return NULL;
  }

  public function isDeprecated(): \PHPStan\TrinaryLogic {
    return \PHPStan\TrinaryLogic::createNo();
  }

  public function getDeprecatedDescription(): ?string {
    return NULL;
  }

  public function isInternal(): \PHPStan\TrinaryLogic {
    return \PHPStan\TrinaryLogic::createNo();
  }

}
