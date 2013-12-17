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
	 * @ORM\Column(type="string", length=100)
	 */
	protected $name;
	/**
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="itemtype")
	 */
	protected $items;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
}
