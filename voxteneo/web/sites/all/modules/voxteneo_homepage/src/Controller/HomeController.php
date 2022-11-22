<?php
/**
 * @file
 * Contains \Drupal\voxteneo_homepage\Controller\HelloController.
 */
namespace Drupal\voxteneo_homepage\Controller;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

class HomeController {
  public function content() {
	$config = \Drupal::config('voxteneo_homepage.settings');
		//var_dump($config);
	$date = new DrupalDateTime('now');
	$date->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
	$formatted = $date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
	
	$query = \Drupal::entityQuery('node');
	$query->condition('status', 1);
	$query->condition('type', 'event')
	->condition('field_event_date.value', $formatted, '>')
	->range(0, 2);	
	$entity_ids = $query->execute();	
	
	$nodes = \Drupal\node\Entity\Node::loadMultiple($entity_ids);
	$events = [];
	foreach ($nodes as $node){
		$mid = $node->field_event_image[0]->getValue()['target_id'];		
		$file = File::load($mid);
		
		$events[] = [
			
			'img'=>$file->createFileUrl(),
			'desc'=>$node->get('field_event_description')->value
		];
	}
	//var_dump($nodes_views);
    return array(
      '#type' => 'markup',
      //'#markup' => t('HellocdsfsWorld!'.$config->get('voxteneo_homepage.voxteneo_homepage_banner_title')),
      '#title'=>$config->get('voxteneo_homepage.voxteneo_homepage_banner_title'), 
      '#subtitle'=>$config->get('voxteneo_homepage.voxteneo_homepage_banner_subtitle'),
      '#events'=>$events,
      '#theme'=>'home',
      '#eventsview'=>$nodes_views
    );
  }
}
