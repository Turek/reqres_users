<?php

namespace Drupal\reqres_users\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Reqres User entity.
 *
 * @ContentEntityType(
 *   id = "reqres_user",
 *   label = @Translation("Reqres User"),
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "reqres_users",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class ReqresUser extends ContentEntityBase {

  const ENTITY_TYPE_ID = 'reqres_user';

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Reqres User entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Reqres User entity.'))
      ->setReadOnly(TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('The email of the Reqres User entity.'));

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('The first name of the Reqres User entity.'));

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDescription(t('The last name of the Reqres User entity.'));

    return $fields;
  }

  /**
   * Gets the email of the user.
   *
   * @return string
   *   The email of the user.
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * Sets the email of the user.
   *
   * @param string $email
   *   The email of the user.
   *
   * @return $this
   */
  public function setEmail($email) {
    $this->set('email', $email);
    return $this;
  }

  /**
   * Gets the first name of the user.
   *
   * @return string
   *   The first name of the user.
   */
  public function getFirstName() {
    return $this->get('first_name')->value;
  }

  /**
   * Sets the first name of the user.
   *
   * @param string $first_name
   *   The first name of the user.
   *
   * @return $this
   */
  public function setFirstName($first_name) {
    $this->set('first_name', $first_name);
    return $this;
  }

  /**
   * Gets the last name of the user.
   *
   * @return string
   *   The last name of the user.
   */
  public function getLastName() {
    return $this->get('last_name')->value;
  }

  /**
   * Sets the last name of the user.
   *
   * @param string $last_name
   *   The last name of the user.
   *
   * @return $this
   */
  public function setLastName($last_name) {
    $this->set('last_name', $last_name);
    return $this;
  }

  /**
   * Gets the name of the user (first and last name combined).
   *
   * @return string
   *   The name of the user.
   */
  public function getName() {
    return $this->get('first_name')->value . ' ' . $this->get('last_name')->value;
  }

}
