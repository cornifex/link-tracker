<?php

declare(strict_types=1);

namespace Drupal\link_tracker\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\link_tracker\VisitInterface;

/**
 * Defines the visit entity class.
 *
 * @ContentEntityType(
 *   id = "link_tracker_visit",
 *   label = @Translation("Visit"),
 *   label_collection = @Translation("Visits"),
 *   label_singular = @Translation("visit"),
 *   label_plural = @Translation("visits"),
 *   label_count = @PluralTranslation(
 *     singular = "@count visits",
 *     plural = "@count visits",
 *   ),
 *   base_table = "link_tracker_visit",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
final class Visit extends ContentEntityBase implements VisitInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) : array {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['tracked_link'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'link_tracker_tracked_link');
    $fields['ip_address'] = BaseFieldDefinition::create('string');
    $fields['timestamp'] = BaseFieldDefinition::create('created');
    return $fields;
  }

}
