<?php

namespace webflo\PHPStanDrupalFields;

use Drupal\Core\Entity\FieldableEntityInterface;
use mglaman\PHPStanDrupal\Drupal\EntityDataRepository;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class FieldableEntityInterfaceReturnTypeExtension implements DynamicMethodReturnTypeExtension {

  public function __construct(protected EntityDataRepository $entityDataRepository, protected FieldTypeMap $fieldTypeMap) {

  }

  public function getClass(): string {
    return FieldableEntityInterface::class;
  }

  public function isMethodSupported(MethodReflection $methodReflection): bool {
    return $methodReflection->getName() === 'get';
  }

  public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?\PHPStan\Type\Type {
    $entityTypeId = $this->getEntityType($scope->getType($methodCall->var));
    if ($entityTypeId) {
      $argType = $scope->getType($methodCall->getArgs()[0]->value);
      $fieldDefinition = $this->fieldTypeMap->getFieldDefinition($entityTypeId, $argType->getValue());
      if ($fieldDefinition) {
        return new ObjectType($fieldDefinition['fieldItemListClass']);
      }
    }
    return NULL;
  }

  protected function getEntityType(Type $type) : string|FALSE {
    foreach ($this->fieldTypeMap->getEntityTypes() as $entityTypeId => $entityTypeDefinition) {
      $result = $type->isSuperTypeOf(new ObjectType($entityTypeDefinition['type']['class']));
      if ($result->yes()) {
        return $entityTypeId;
      }
    }
    return FALSE;
  }

}
