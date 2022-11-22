<?php

namespace Drupal\voxteneo_homepage\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;

/**
 * Provides a 'Related Items' Block.
 *
 * @Block(
 *   id = "related_block",
 *   admin_label = @Translation("Related Items Block"),
 *   category = @Translation("Hello World Block"),
 * )
 */
class RelatedBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
	$node = \Drupal::routeMatch()->getParameter('node');
	
	$news = null;
	$events = null;
	
	if ($node instanceof \Drupal\node\NodeInterface) {
		$nid = $node->id();
		if ($node->bundle() == 'news') {
			$termId = $node->get('field_news_category')->target_id;

			//news
			$query = \Drupal::entityQuery('node');
			$query->condition('status', 1);
			$query->condition('type', 'news')
			->sort('field_news_publish_date.value' , 'DESC')
			->condition('field_news_category', [$termId], 'IN')
			->condition('nid', [$nid], 'NOT IN')
			->range(0, 2);	
			$entity_ids = $query->execute();		
			$nodes = \Drupal\node\Entity\Node::loadMultiple($entity_ids);
			$news = [];
			foreach ($nodes as $node){
				$mid = $node->field_news_image[0]->getValue()['target_id'];		
				$file = File::load($mid);
				$url = $node->toURL();
				//var_dump($node);
				
				$date = new DrupalDateTime();
				$date->setTimezone(new \DateTimezone(DateTimeItemInterface::STORAGE_TIMEZONE));
				$formatted = $date->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT);
				
				$news[] = [
					'url'=>$url,
					'img'=>$file->createFileUrl(),
					'title'=>$node->getTitle(),
					'desc'=>$node->get('field_news_description')->value,
					'date'=>date_format(date_create($node->get('field_news_publish_date')->value),"j F Y")
				];
			}	
		}elseif ($node->bundle() == 'event') {
			$termId = $node->get('field_event_category')->target_id;

			//events
			$query = \Drupal::entityQuery('node');
			$query->condition('status', 1);
			$query->condition('type', 'event')
			->sort('field_event_date.value' , 'ASC')
			->condition('field_event_category', [$termId], 'IN')
			->condition('nid', [$nid], 'NOT IN')
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
					'title'=>$node->getTitle(),
					'desc'=>$node->get('field_event_description')->value,
					'date'=>date_format(date_create($node->get('field_event_date')->value),"j F Y")
				];
			}
			//var_dump($events);
		}
	}
	
	
	//var_dump($nodes2);
    return array(
      '#type' => 'markup',
      //'#markup' => t('HellocdsfsWorld!'.$config->get('voxteneo_homepage.voxteneo_homepage_banner_title')),
      '#events'=>$events,
      '#news'=>$news,
      '#theme'=>'related',
      
    );
    return [
      '#markup' => $this->t('Hello, World!'),
    ];
  }
	public function getCacheMaxAge() {
		return 0;
	}
}
