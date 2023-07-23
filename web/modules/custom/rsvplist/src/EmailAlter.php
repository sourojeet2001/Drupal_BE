<?php

/**
 * @file
 * Contains Drupal\rsvplist\EmailAlter.
 */

namespace Drupal\rsvplist;

class EmailAlter extends EmailValidator {
  protected $email_val;
  public function validateEmail($email) {
    if (preg_match('/^[a-zA-Z0-9._%+-]+@(innoraft)\.com$/', $email)) {
      return TRUE;
    }
    return FALSE;
  }
}