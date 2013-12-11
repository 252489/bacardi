<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 11.12.13
 * Time: 12:30
 */

namespace GBP\BacardiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	public $id;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	public $name;
	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	public $isFemale;
	/**
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
	 */
	public $items;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Set isFemale
     *
     * @param boolean $isFemale
     * @return Category
     */
    public function setIsFemale($isFemale)
    {
        $this->isFemale = $isFemale;
    
        return $this;
    }

    /**
     * Get isFemale
     *
     * @return boolean 
     */
    public function getIsFemale()
    {
        return $this->isFemale;
    }

    /**
     * Add items
     *
     * @param \GBP\BacardiBundle\Entity\Item $items
     * @return Category
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
     * Add employes
     *
     * @param \GBP\BacardiBundle\Entity\Employee $employes
     * @return Category
     */
    public function addEmploye(\GBP\BacardiBundle\Entity\Employee $employes)
    {
        $this->employes[] = $employes;
    
        return $this;
    }

    /**
     * Remove employes
     *
     * @param \GBP\BacardiBundle\Entity\Employee $employes
     */
    public function removeEmploye(\GBP\BacardiBundle\Entity\Employee $employes)
    {
        $this->employes->removeElement($employes);
    }

    /**
     * Get employes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmployes()
    {
        return $this->employes;
    }
}