# phpstan-drupal-fields

A PHPStan extension and drush command to improve the capabilities of phpstan.

## Goals

- Provide proper return types for $user->field_name (based on the field configuration)
- Provide proper return types for $user->get('field_name') (based on the field configuration)
- Support all defined entity types and field types

## Approach

- Dump the entity field mapping (\Drupal\Core\Entity\EntityFieldManager) into a file to use it during analysis with PHPStan.

## Problems

- \mglaman\PHPStanDrupal\Reflection\EntityFieldsViaMagicReflectionExtension must be disabled. Overloaded via composer.json classmap in this packages.
- How to handle bundles? A particular field may not be configured for the bundle.

## Installation

```shell
composer require webflo/phpstan-drupal-fields
drush phpstan:generate
```

### Example (with standard profile)

```php
$account = \Drupal::entityTypeManager()->getStorage('user')->load(1);
$fid = $account->get('user_picture')->target_id;

$field = 'user_picture';
$fid = $account->get($field)->target_id;

$account = \Drupal\user\Entity\User::load(1);
$fid = $account->get($field)->target_id;
$fid = $account->user_picture->target_id;

$invalid = $account->get($field)->foo;
```
