<?php

namespace Drupal\commerce_currencies;

use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\DrupalKernelInterface;
use Drupal\Core\Entity\{Entity\EntityFormDisplay, Entity\EntityViewDisplay, EntityInterface, EntityStorageException};
use Drupal\Core\Extension\{ModuleHandlerInterface, ModuleInstaller, ModuleInstallerInterface, ModuleUninstallValidatorInterface};
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Update\UpdateHookRegistry;
use Psr\Log\LoggerInterface;

/**
 * Uninstaller for Commerce Currencies.
 * It removes its own fields and restores stock Commerce functionality before uninstalling.
 * 
 * @see \Drupal\Core\Extension\ModuleInstaller
 */
class Uninstaller extends ModuleInstaller {
  use MessengerTrait;
  use StringTranslationTrait;

  protected ModuleInstallerInterface $originalInstaller;

  public function __construct(ModuleInstallerInterface $original_installer, $root, ModuleHandlerInterface $module_handler, DrupalKernelInterface $kernel, Connection $connection, UpdateHookRegistry $update_registry, protected ?LoggerInterface $logger = NULL) {
    $this->originalInstaller = $original_installer;
    parent::__construct($root, $module_handler, $kernel, $connection, $update_registry, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public function install(array $module_list, $enable_dependencies = TRUE) {
    return $this->originalInstaller->install($module_list, $enable_dependencies);
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall(array $module_list, $uninstall_dependents = TRUE) {
    if (in_array('commerce_currencies', $module_list) && $uninstall_dependents) {
      $ids = \Drupal::entityQuery('entity_form_display')
        ->condition('id', 'commerce_product_variation.', 'STARTS_WITH')
        ->execute();
      if (!empty($ids)) {
        foreach (EntityFormDisplay::loadMultiple($ids) as $display) {
          _commerce_currencies_restore_display($display);
          $display->save();
        }
      }

      $ids = \Drupal::entityQuery('entity_view_display')
        ->condition('id', 'commerce_product_variation.', 'STARTS_WITH')
        ->execute();
      if (!empty($ids)) {
        foreach (EntityViewDisplay::loadMultiple($ids) as $display) {
          _commerce_currencies_restore_display($display);
          $display->save();
        }
      }

      $this->messenger()->addStatus(t('Original price fields restored.'));
    }

    try {
      return $this->originalInstaller->uninstall($module_list, $uninstall_dependents);
    } catch (EntityStorageException $e) {
      $this->messenger()->addError(t('Run cron before attempting to uninstall again.'));
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addUninstallValidator(ModuleUninstallValidatorInterface $uninstall_validator) {
    $this->originalInstaller->addUninstallValidator($uninstall_validator);
  }

  /**
   * {@inheritdoc}
   */
  public function validateUninstall(array $module_list) {
    return $this->originalInstaller->validateUninstall($module_list);
  }

  /**
   * Lists configuration entities affected by a dependency removal.
   * Removes own view and form displays that we restore before uninstallation.
   * 
   * Copied and modified from ConfigDependencyDeleteFormTrait::addDependencyListsToForm().
   * 
   * @see \Drupal\Core\Config\Entity\ConfigDependencyDeleteFormTrait
   */
  public static function addDependencyLists(array &$form, array $names) {
    /** @var ConfigManagerInterface $config_manager */
    $config_manager = \Drupal::service('config.manager');
    /** @var EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = \Drupal::entityTypeManager();

    $dependent_entities = $config_manager->getConfigEntitiesToChangeOnDependencyRemoval('module', $names);
    foreach ($dependent_entities['update'] as $key => $entity) {
      /** @var EntityInterface $entity */
      $type = $entity->getEntityTypeId();
      if ($type == 'entity_form_display' || $type == 'entity_view_display') {
        unset($dependent_entities['update'][$key]);
      }
    }
    foreach ($dependent_entities['delete'] as $key => $entity) {
      /** @var EntityInterface $entity */
      $type = $entity->getEntityTypeId();
      if ($type == 'entity_form_display' || $type == 'entity_view_display') {
        unset($dependent_entities['delete'][$key]);
      }
    }

    $entity_types = [];

    $form['entity_updates'] = [
      '#type' => 'details',
      '#title' => t('Configuration updates'),
      '#description' => t('The listed configuration will be updated.'),
      '#open' => TRUE,
      '#access' => FALSE,
    ];

    foreach ($dependent_entities['update'] as $entity) {
      /** @var ConfigEntityInterface  $entity */
      $entity_type_id = $entity->getEntityTypeId();
      if (!isset($form['entity_updates'][$entity_type_id])) {
        $entity_type = $entity_type_manager->getDefinition($entity_type_id);
        // Store the ID and label to sort the entity types and entities later.
        $label = $entity_type->getLabel();
        $entity_types[$entity_type_id] = $label;
        $form['entity_updates'][$entity_type_id] = [
          '#theme' => 'item_list',
          '#title' => $label,
          '#items' => [],
        ];
      }
      $form['entity_updates'][$entity_type_id]['#items'][$entity->id()] = $entity->label() ?: $entity->id();
    }
    if (!empty($dependent_entities['update'])) {
      $form['entity_updates']['#access'] = TRUE;

      // Add a weight key to the entity type sections.
      asort($entity_types, SORT_FLAG_CASE);
      $weight = 0;
      foreach ($entity_types as $entity_type_id => $label) {
        $form['entity_updates'][$entity_type_id]['#weight'] = $weight;
        // Sort the list of entity labels alphabetically.
        ksort($form['entity_updates'][$entity_type_id]['#items'], SORT_FLAG_CASE);
        $weight++;
      }
    }

    $form['entity_deletes'] = [
      '#type' => 'details',
      '#title' => t('Configuration deletions'),
      '#description' => t('The listed configuration will be deleted.'),
      '#open' => TRUE,
      '#access' => FALSE,
    ];

    foreach ($dependent_entities['delete'] as $entity) {
      $entity_type_id = $entity->getEntityTypeId();
      if (!isset($form['entity_deletes'][$entity_type_id])) {
        $entity_type = $entity_type_manager->getDefinition($entity_type_id);
        // Store the ID and label to sort the entity types and entities later.
        $label = $entity_type->getLabel();
        $entity_types[$entity_type_id] = $label;
        $form['entity_deletes'][$entity_type_id] = [
          '#theme' => 'item_list',
          '#title' => $label,
          '#items' => [],
        ];
      }
      $form['entity_deletes'][$entity_type_id]['#items'][$entity->id()] = $entity->label() ?: $entity->id();
    }
    if (!empty($dependent_entities['delete'])) {
      $form['entity_deletes']['#access'] = TRUE;

      // Add a weight key to the entity type sections.
      asort($entity_types, SORT_FLAG_CASE);
      $weight = 0;
      foreach ($entity_types as $entity_type_id => $label) {
        if (isset($form['entity_deletes'][$entity_type_id])) {
          $form['entity_deletes'][$entity_type_id]['#weight'] = $weight;
          // Sort the list of entity labels alphabetically.
          ksort($form['entity_deletes'][$entity_type_id]['#items'], SORT_FLAG_CASE);
          $weight++;
        }
      }
    }
  }
}
