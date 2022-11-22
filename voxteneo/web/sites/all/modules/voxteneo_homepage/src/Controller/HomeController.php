<?php
/**
 * @file
 * Contains \Drupal\voxteneo_homepage\Controller\HelloController.
 */
namespace Drupal\voxteneo_homepage\Controller;
class HomeController {
  public function content() {
	  $config = \Drupal::config('voxteneo_homepage.settings');
		//var_dump($config);
    return array(
      '#type' => 'markup',
      //'#markup' => t('HellocdsfsWorld!'.$config->get('voxteneo_homepage.voxteneo_homepage_banner_title')),
      '#title'=>$config->get('voxteneo_homepage.voxteneo_homepage_banner_title'), 
      '#subtitle'=>$config->get('voxteneo_homepage.voxteneo_homepage_banner_subtitle'),
      '#theme'=>'banner',
    );
  }
}
