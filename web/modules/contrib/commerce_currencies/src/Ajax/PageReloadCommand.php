<?php

namespace Drupal\commerce_currencies\Ajax;

use Drupal\Core\Ajax\{CommandInterface, CommandWithAttachedAssetsInterface};
use Drupal\Core\Asset\AttachedAssets;

class PageReloadCommand implements CommandInterface, CommandWithAttachedAssetsInterface {

  public function render(): array {
    return ['command' => 'PageReload'];
  }

  public function getAttachedAssets(): AttachedAssets {
    $assets = new AttachedAssets();
    $assets->setLibraries(['commerce_currencies/ajax_commands']);
    return $assets;
  }
}
