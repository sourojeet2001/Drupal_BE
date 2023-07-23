<?php

/**
 * @file
 * Contains Drupal\rsvplist\EmailValidator.
 */

namespace Drupal\rsvplist;

class EmailValidator {
  protected $email_val;
  public function validateEmail($email) {
    if (preg_match('/^[a-zA-Z0-9._%+-]+@(yahoo|gmail|outlook)\.com$/', $email)) {
      return TRUE;
    }
    return FALSE;
  }
}