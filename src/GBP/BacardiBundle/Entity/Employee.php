<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 11.12.13
 * Time: 11:57
 */

namespace GBP\BacardiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Util\SecureRandom;

use GBP\BacardiBundle;

/**
 * @ORM\Entity
 * @ORM\Table(name="employee")
 */
class Employee {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 * @Assert\Regex(
	 *      pattern="/[а-яА-Я0-9]/",
	 *      match=false,
	 *      message="Имя не может содержать русские символы"
	 * )
	 */
	protected $name;
	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 * @Assert\Regex(
	 *      pattern="/[а-яА-Я0-9]/",
	 *      match=false,
	 *      message="Фамилия не может содержать русские символы"
	 * )
	 */
	protected $surname;
	/**
	 * @ORM\Column(type="string", unique=true, length=150)
	 * @Assert\Email(
	 *     message = "The email '{{ value }}' is not a valid email.",
	 *     checkMX = true
	 * )
	 */
	protected $email;
	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	protected $isFemale;
	/**
	 * @ORM\ManyToOne(targetEntity="City", inversedBy="employes", cascade={"remove", "persist"})
	 * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
	 * @Assert\NotBlank()
	 * @Assert\Type(type="GBP\BacardiBundle\Entity\City")
	 */
	protected $city;
	/**
	 * @ORM\ManyToMany(targetEntity="Item", inversedBy="employes", cascade={"remove", "persist"})
	 * @ORM\JoinTable(name="employee_items")
	 */
	protected $items;
	/**
	 * @var string
	 */
	protected $salt;
	/**
	 * @ORM\Column(type="string", length=40)
	 */
	protected $hash;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $photo;
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $resultPhoto;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
		$generator = new SecureRandom();
		$this->salt = $generator->nextBytes(20);
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
     * @return Employee
     */
    public function setName($name)
    {
        $this->name = BacardiBundle\mb_ucfirst($name);
    
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
     * Set surname
     *
     * @param string $surname
     * @return Employee
     */
    public function setSurname($surname)
    {
        $this->surname = BacardiBundle\mb_ucfirst($surname);
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Employee
     */
    public function setEmail($email)
    {
        $this->email = $email;
	    $this->hash = hash('sha1', $this->salt . $email);
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

	/**
	 * Set photo
	 *
	 * @param string $photo
	 * @return Employee
	 */
	public function setPhoto($photo)
	{
		$this->photo = $photo;
		return $this;
	}

	/**
	 * Get photo
	 *
	 * @return string
	 */
	public function getResultPhoto()
	{
		return $this->resultPhoto;
	}

	/**
	 * Set photo
	 *
	 * @param string $photo
	 * @return Employee
	 */
	public function setResultPhoto($photo)
	{
		$this->resultPhoto = $photo;
		return $this;
	}

	/**
	 * Get photo
	 *
	 * @return string
	 */
	public function getPhoto()
	{
		return $this->photo;
	}

    /**
     * Set isFemale
     *
     * @param boolean $isFemale
     * @return Employee
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

	public function getHash()
	{
		return $this->hash;
	}

    /**
     * Set city
     *
     * @param \GBP\BacardiBundle\Entity\City $city
     * @return Employee
     */
    public function setCity(\GBP\BacardiBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \GBP\BacardiBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add items
     *
     * @param \GBP\BacardiBundle\Entity\Item $items
     * @return Employee
     */
    public function addItem(\GBP\BacardiBundle\Entity\Item $items)
    {
	    foreach($this->items as $_item)
		    if( $_item->getCategory()->getId() == $items->getCategory()->getId() )
			    $this->removeItem($_item);

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

	public function getCategories()
	{
		$categories = array();
		$items = $this->getItems();
		/**
		 * @var $item \GBP\BacardiBundle\Entity\Item
		 */
		foreach($items as $item)
			$categories[$item->getId()] = $item->getCategory()->getId();
		return $categories;
	}

}