<?php

/**
 * @file
 * A form to collect RSVP data from users.
 */

namespace Drupal\rsvplist\Form;

use \Drupal\Component\Datetime\TimeInterface;
use \Drupal\Core\Database\Connection;
// use \Drupal\Core\Database\Dat;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;
use Drupal\mymodule\CurrentUserCustom;
use Drupal\rsvplist\EmailValidator;
use Psr\Container\ContainerInterface;
/**
 * This form is used to take user input of email id to collect RSVP's and stores
 * the entries onto DB.
 */
class RSVPForm extends FormBase{

  protected $emailVal;

  protected $currUser;

  protected $msg;

  protected $conn;

  protected $time;
  
  /**
   * Class constructor.
   */
  public function __construct(EmailValidator $emailVal, CurrentUserCustom $currUser, Messenger $msg, Connection $conn ,  TimeInterface $time) {
    $this->emailVal = $emailVal;
    $this->currUser = $currUser;
    $this->msg = $msg;
    $this->conn = $conn;
    $this->time = $time;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('rsvplist.emailValidator'),
      $container->get('mymodule.currentUserCustom'),
      $container->get('messenger'),
      $container->get('database'),
      $container->get('datetime.time'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(){
    return 'rsvplist_email_form';
  }

  /**
   * {@inheritdoc}
   */ 
  public function buildForm(array $form, FormStateInterface $form_state) {
    //Attempt to get the fully loaded node object 
    $node = \Drupal::routeMatch()->getParameter('node');
    // Some pages may not be nodes, so $node will be null in that case.
    // If node is loaded then get the id.
    if( !is_null($node)) {
      $nid = $node->id();
    }
    else {
      // If node could not be found, default to 0.
      $nid = 0;
    }
    //Establish the form render array. It has an Email text field with a
    //submit button, and a hidden field with the node id.
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email Address'),
      '#size' => 25,
      '#description' => t("We'll send updates to this email address"),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('RSVP'),
    ];
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  /**
   * {@inherit}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if(! $this->emailVal->validateEmail($value)) {
      $form_state->setErrorByName('email',
      $this->t("It appears that %mail is not a valid email address. Please try again
      ", ['%mail' => $value]));
    }
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try{
      // Phase 1: Initiate variables to save.

      // Get the current user id.
      // $uid = \Drupal::currentUser()->id();
      $uid = $this->currUser->getCurrUserId();

      // Obtain values as entered into the form.
      $nid = $form_state->getValue('nid');
      $email = $form_state->getValue('email');

      // $current_time = \Drupal::time()->getRequestTime();
      $current_time = $this->time->getRequestTime();

      // End of Phase 1.

      // Phase 2: Save the values to the DB.

      // Start to build a query builder object $query.
      // $query = \Drupal::database()->insert('rsvplist');
      $query = $this->conn->insert('rsvplist');

      // Specify the fields that the query will insert into.
      $query->fields([
        'uid',
        'nid',
        'mail',
        'created',
      ]);

      // Set the values of the fields we selected.

      $query->values([
        $uid,
        $nid,
        $email,
        $current_time,
      ]);

      // Execute the $query.
      $query->execute();
      // End of Phase 2.

      // Phase 3: Display a success message.
      // Provide form submitter a nice message.
      // \Drupal::messenger()->addMessage(
      //   t('Thanks for your feedback')
      // );
      $this->msg->addMessage(t('Thanks for your feedback'));
      // End of Phase 3.
    }
    catch (\Exception $e) {
      // \Drupal::messenger()->addError(
      //   t('Something went wrong')
      // );
      $this->msg->addMessage(t('Something went wrong'));
    }
  }
}