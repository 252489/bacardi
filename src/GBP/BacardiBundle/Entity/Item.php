<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 11.12.13
 * Time: 11:58
 */

namespace GBP\BacardiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="item")
 */
class Item {
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
	 * @ORM\Column(type="string", length=255)
	 */
	public $image;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	public $previewImage;
	/**
	 * @ORM\Column(type="smallint")
	 */
	public $layer;
	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="items", cascade={"remove", "persist"})
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	public $category;
	/**
	 * @ORM\ManyToMany(targetEntity="Employee", mappedBy="items")
	 */
	public $employes;

	/**
	 * Constructor
	 */
	public function __construct()
	{
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
     * @return Item
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
     * Set image
     *
     * @param string $image
     * @return Item
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set previewImage
     *
     * @param string $previewImage
     * @return Item
     */
    public function setPreviewImage($previewImage)
    {
        $this->previewImage = $previewImage;
    
        return $this;
    }

    /**
     * Get previewImage
     *
     * @return string 
     */
    public function getPreviewImage()
    {
        return $this->previewImage;
    }

    /**
     * Set layer
     *
     * @param integer $layer
     * @return Item
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

    /**
     * Set category
     *
     * @param \GBP\BacardiBundle\Entity\Category $category
     * @return Item
     */
    public function setCategory(\GBP\BacardiBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \GBP\BacardiBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add employes
     *
     * @param \GBP\BacardiBundle\Entity\Employee $employes
     * @return Item
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