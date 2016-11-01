<?php

/**
 * @file
 * Enables modules and site configuration for a portfolio site installation.
 */

use Drupal\contact\Entity\ContactForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function portfolio_form_install_configure_form_alter(&$form, FormStateInterface $form_state) {
  $form['#submit'][] = 'portfolio_form_install_configure_submit';
}
