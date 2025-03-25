<?php

declare(strict_types=1);

namespace Drupal\link_tracker\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\link_tracker\TrackedLinkInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the tracked link entity class.
 *
 * @ContentEntityType(
 *   id = "link_tracker_tracked_link",
 *   label = @Translation("Tracked Link"),
 *   label_collection = @Translation("Tracked Links"),
 *   label_singular = @Translation("tracked link"),
 *   label_plural = @Translation("tracked links"),
 *   label_count = @PluralTranslation(
 *     singular = "@count tracked links",
 *     plural = "@count tracked links",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\link_tracker\TrackedLinkListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\link_tracker\TrackedLinkAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\link_tracker\Form\TrackedLinkForm",
 *       "edit" = "Drupal\link_tracker\Form\TrackedLinkForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "link_tracker_tracked_link",
 *   admin_permission = "administer link_tracker_tracked_link",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "url",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/tracked-link",
 *     "add-form" = "/tracked-link/add",
 *     "canonical" = "/tracked-link/{link_tracker_tracked_link}",
 *     "edit-form" = "/tracked-link/{link_tracker_tracked_link}/edit",
 *     "delete-form" = "/tracked-link/{link_tracker_tracked_link}/delete",
 *     "delete-multiple-form" = "/admin/content/tracked-link/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.link_tracker_tracked_link.settings",
 * )
 */
final class TrackedLink extends ContentEntityBase implements TrackedLinkInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Tracked URL'))
      ->setDescription(t('The URL to track.'))
      ->setDisplayOptions('form', [
        'type' => 'uri',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the tracked link was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the tracked link was last edited.'));

    return $fields;
  }

}
