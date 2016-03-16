<?php

/**
 * @file
 * Contains Drupal\custom\Form\DefaultForm.
 */

namespace Drupal\custom\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 *
 * @package Drupal\custom\Form
 */
class DefaultForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom.default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom.default');

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Open modal on change'),
      '#ajax' => [
        'callback' => array($this, 'openModal'),
        'event' => 'change',
        'progress' => array(
          'type' => 'throbber',
          'message' => t('opening modal......'),
        ),
      ],
    );

    $form['test'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('test'),
      '#default_value' => $config->get('test'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('custom.default')
      ->set('test', $form_state->getValue('test'))
      ->save();
  }

  /**
   * Ajax callback to open a modal.
   */
  public function openModal(array &$form, FormStateInterface $form_state) {
    // html content to render
    $content = array(
      'content' => array(
        '#markup' => 'My return',
      ),
    );
    $html = drupal_render($content);

    // ajax response
    $response = new AjaxResponse();
    // $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    // $response->setAttachments($form['#attached']);
    $response->addCommand(new OpenModalDialogCommand('Hi', $html));
    return $response;
  }

}
