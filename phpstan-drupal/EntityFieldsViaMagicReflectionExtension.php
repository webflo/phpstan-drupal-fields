<?php

namespace mglaman\PHPStanDrupal\Reflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\TrinaryLogic;
use PHPStan\Type\ObjectType;

/**
 * Override of mglaman\PHPStanDrupal since its not possible ot disable services
 * in PHPStan.
 */
class EntityFieldsViaMagicReflectionExtension implements PropertiesClassReflectionExtension
{

  public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
  {
    return FALSE;
  }

  public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
  {
    throw new \LogicException();
  }

}
