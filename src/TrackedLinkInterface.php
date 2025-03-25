<?php

declare(strict_types=1);

namespace Drupal\link_tracker;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a tracked link entity type.
 */
interface TrackedLinkInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
