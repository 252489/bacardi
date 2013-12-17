<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 17.12.13
 * Time: 13:48
 */

namespace GBP\BacardiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="itemtype")
 */
class ItemType {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(type="string", length=100, unique=true)
	 */
	protected $name;
	/**
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="itemtype")
	 */
	protected $items;
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $layer;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

	public function __toString()
	{
		return $this->name;
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ItemType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add items
     *
     * @param \GBP\BacardiBundle\Entity\Item $items
     * @return ItemType
     */
    public function addItem(\GBP\BacardiBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \GBP\BacardiBundle\Entity\Item $items
     */
    public function removeItem(\GBP\BacardiBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set layer
     *
     * @param integer $layer
     * @return ItemType
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;

        return $this;
    }

    /**
     * Get layer
     *
     * @return integer 
     */
    public function getLayer()
    {
        return $this->layer;
    }
}
