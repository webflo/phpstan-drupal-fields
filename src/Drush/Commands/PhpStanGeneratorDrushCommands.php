<?php

namespace webflo\PHPStanDrupalFields\Drush\Commands;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use DrupalFinder\DrupalFinder;
use Drush\Attributes as CLI;
use Drush\Boot\DrupalBootLevels;
use Drush\Commands\DrushCommands;
use Nette\Neon\Neon;

class PhpStanGeneratorDrushCommands extends DrushCommands {

  #[CLI\Command(name: 'phpstan:generate')]
  #[CLI\Bootstrap(level: DrupalBootLevels::FULL)]
  function phpStanGenerate() {
    /**
     * @todo: Figure out DI for drush commands
     */
    $entityTypeManager = \Drupal::service('entity_type.manager');
    $entityFieldManger = \Drupal::service('entity_field.manager');
    $fieldTypePluginManger = \Drupal::service('plugin.manager.field.field_type');

    $data = [];

    foreach ($entityTypeManager->getDefinitions() as $entityType => $entityTypeDefinition) {
      if ($entityTypeDefinition instanceof ContentEntityTypeInterface) {
        $data[$entityType]['type']['class'] = $entityTypeDefinition->getClass();
        foreach ($entityFieldManger->getFieldStorageDefinitions($entityType) as $fieldDefinition) {
          $fieldType = $fieldTypePluginManger->getDefinition($fieldDefinition->getType());
          $data[$entityType]['fields'][$fieldDefinition->getName()] = [
            'fieldName' => $fieldDefinition->getName(),
            'fieldTypeClass' => $fieldType['class'],
            'fieldItemListClass' => $fieldType['list_class'],
            'fieldType' => $fieldDefinition->getType(),
            'entityClass' => $entityTypeDefinition->getClass(),
          ];
        }
      }
    }

    $finder = new DrupalFinder();
    $finder->locateRoot(\Drupal::root());

    if ($finder->getComposerRoot() === FALSE) {
      throw new \RuntimeException('Unable to locate composer root.');
    }

    $data = [
      'parameters' => [
        'drupal' => [
          'entityFieldMapping' => $data,
        ]
      ]
    ];

    file_put_contents($finder->getComposerRoot() .  '/drupal.entity-metadata.neon', Neon::encode($data, TRUE));
  }

}
