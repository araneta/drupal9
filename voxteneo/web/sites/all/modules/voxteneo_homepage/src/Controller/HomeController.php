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
	
	//events
	$query = \Drupal::entityQuery('node');
	$query->condition('status', 1);
	$query->condition('type', 'event')
	->sort('field_event_date.value' , 'ASC')
	->condition('field_event_date.value', $formatted, '>')
	->range(0, 2);	
	$entity_ids = $query->execute();		
	$nodes = \Drupal\node\Entity\Node::loadMultiple($entity_ids);
	$events = [];
	foreach ($nodes as $node){
		$mid = $node->field_event_image[0]->getValue()['target_id'];		
		$file = File::load($mid);
		$url = $node->toURL();
		//var_dump($node);
		
		$date = new DrupalDateTime();
		$date->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
		$formatted = $date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
		
		$events[] = [
			'url'=>$url,
			'img'=>$file->createFileUrl(),
			'desc'=>$node->get('field_event_description')->value,
			'date'=>date_format(date_create($node->get('field_event_date')->value),"j F Y")
		];
	}
	
	//news
	$query = \Drupal::entityQuery('node');
	$query->condition('status', 1);
	$query->condition('type', 'news')
	 ->sort('field_news_publish_date.value' , 'DESC')	
	->range(0, 1);	
	$entity_ids = $query->execute();		
	$nodes2 = \Drupal\node\Entity\Node::loadMultiple($entity_ids);
	$news = [];
	foreach ($nodes2 as $node2){
		$mid = $node2->field_news_image[0]->getValue()['target_id'];		
		$file = File::load($mid);
		$url = $node2->toURL();
		//var_dump($node);
		
		$date = new DrupalDateTime();
		$date->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
		$formatted = $date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
		
		$news[] = [
			'url'=>$url,
			'img'=>$file->createFileUrl(),
			'desc'=>$node2->get('field_news_description')->value,
			'date'=>date_format(date_create($node2->get('field_news_publish_date')->value),"j F Y")
		];
	}
	//var_dump($nodes2);
    return array(
      '#type' => 'markup',
      //'#markup' => t('HellocdsfsWorld!'.$config->get('voxteneo_homepage.voxteneo_homepage_banner_title')),
      '#title'=>$config->get('voxteneo_homepage.voxteneo_homepage_banner_title'), 
      '#subtitle'=>$config->get('voxteneo_homepage.voxteneo_homepage_banner_subtitle'),
      '#events'=>$events,
      '#news'=>$news,
      '#theme'=>'home',
      
    );
  }
}
