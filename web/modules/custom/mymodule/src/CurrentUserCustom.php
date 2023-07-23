<?php

/**
 * @file
 * Contains Drupal\mymodule\CurrentUserCustom.
 */

namespace Drupal\mymodule;

class CurrentUserCustom {
  protected $curr_user;

  public function __construct() {
    $this->curr_user = \Drupal::currentUser();
  }

  public function getCurrUserId() {
    return $this->curr_user->id();
  }

  public function getAccountName() {
    return $this->curr_user->getAccountName();
  }

  public function getUserRoles() {
    return $this->curr_user->getRoles();
  }
}