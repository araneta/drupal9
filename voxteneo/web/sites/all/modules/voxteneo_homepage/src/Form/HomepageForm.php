<?php

/**
 * @file
 * Contains Drupal\voxteneo_homepage\Form\HomepageForm.
 */

namespace Drupal\voxteneo_homepage\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class HomepageForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'voxteneo_homepage_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('voxteneo_homepage.settings');
    
    $form['voxteneo_homepage_banner_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Home page banner title'),
      '#default_value' => $config->get('voxteneo_homepage.voxteneo_homepage_banner_title'),
      '#description' => $this->t('Enter banner title'),
    ];
        
    $form['voxteneo_homepage_banner_subtitle'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Home page banner subtitle'),
      '#default_value' => $config->get('voxteneo_homepage.voxteneo_homepage_banner_subtitle'),
      '#description' => $this->t('Enter banner sub title'),
    ];
    
     $form['voxteneo_homepage_banner_image'] = [
      '#type' => 'media_library',      
		'#allowed_bundles' => ['image'],
		'#title' => t('Upload your image'),
		'#default_value' => NULL|1|'1,2,3',
      '#default_value' => $config->get('voxteneo_homepage.voxteneo_homepage_banner_image'),
      '#description' => $this->t('Enter banner image'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('voxteneo_homepage.settings');
    $config->set('voxteneo_homepage.voxteneo_homepage_banner_title', $form_state->getValue('voxteneo_homepage_banner_title'));
    $config->set('voxteneo_homepage.voxteneo_homepage_banner_subtitle', $form_state->getValue('voxteneo_homepage_banner_subtitle'));
    $config->set('voxteneo_homepage.voxteneo_homepage_banner_image', $form_state->getValue('voxteneo_homepage_banner_image'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'voxteneo_homepage.settings',
    ];
  }

}
