<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 11.12.13
 * Time: 11:58
 */

namespace GBP\BacardiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="GBP\BacardiBundle\Repository\ItemRepository")
 * @ORM\Table(name="item")
 * @ORM\HasLifecycleCallbacks
 */
class Item {

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
	 * @ORM\Column(type="string", length=255)
	 */
	protected $image;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $previewImage;
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $layer;
	/**
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;
	/**
	 * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items")
	 * @ORM\JoinColumn(name="itemtype_id", referencedColumnName="id")
	 */
	protected $itemtype;
	/**
	 * @ORM\ManyToMany(targetEntity="Employee", mappedBy="items")
	 */
	protected $employes;

	private $file;
	private $filePreview;


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

	public function setFile(UploadedFile $file)
	{
		$this->file = $file;
	}

	public function setFilePreview(UploadedFile $file)
	{
		$this->filePreview = $file;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function getFilePreview()
	{
		return $this->filePreview;
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function upload()
	{
		// the file property can be empty if the field is not required
		if (null === $this->getFile() && null === $this->getFilePreview() ) {
			return;
		}

		$filepath = realpath( dirname(__FILE__) . '/../../../../web/upload/items' );
		if( null !== $this->getFile() )
		{
			$this->getFile()->move(
				$filepath,
				$this->getFile()->getClientOriginalName()
			);
			$this->image = '/upload/items/' . $this->getFile()->getClientOriginalName();
		}
		if( null !== $this->getFilePreview() )
		{
			$this->getFilePreview()->move(
				$filepath,
				'thumb_' . $this->getFilePreview()->getClientOriginalName()
			);
			$this->previewImage = '/upload/items/thumb_' . $this->getFilePreview()->getClientOriginalName();
		}
	}

    /**
     * Set itemtype
     *
     * @param \GBP\BacardiBundle\Entity\ItemType $itemtype
     * @return Item
     */
    public function setItemtype(\GBP\BacardiBundle\Entity\ItemType $itemtype = null)
    {
        $this->itemtype = $itemtype;

        return $this;
    }

    /**
     * Get itemtype
     *
     * @return \GBP\BacardiBundle\Entity\ItemType 
     */
    public function getItemtype()
    {
        return $this->itemtype;
    }
}
