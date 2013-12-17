<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Koretskiy
 * Date: 15.12.13
 * Time: 15:58
 */

namespace GBP\BacardiBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ItemRepository extends EntityRepository {

	public function findBySexJoinedToCategory($isFemale)
	{
		$query = $this->getEntityManager()
			->createQuery('
            SELECT i, c, it
            FROM GBPBacardiBundle:Item i
            JOIN i.category c
            JOIN i.itemtype it
            WHERE c.isFemale = :isFemale'
			)->setParameter('isFemale', (bool)$isFemale);
		try {
			$items = array();
			foreach($query->getArrayResult() as $item )
				$items[$item['category']['namelink']][] = $item;
			return $items;
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
} 