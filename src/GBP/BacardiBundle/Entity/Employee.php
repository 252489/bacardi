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
	public $id;
	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 */
	public $name;
	/**
	 * @ORM\Column(type="string", length=100)
	 * @Assert\NotBlank()
	 */
	public $surname;
	/**
	 * @ORM\Column(type="string", unique=true, length=150)
	 * @Assert\Email(
	 *     message = "The email '{{ value }}' is not a valid email.",
	 *     checkMX = true
	 * )
	 */
	public $email;
	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	public $isFemale;
	/**
	 * @ORM\ManyToOne(targetEntity="City", inversedBy="employes", cascade={"remove", "persist"})
	 * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
	 * @Assert\NotBlank()
	 * @Assert\Type(type="GBP\BacardiBundle\Entity\City")
	 */
	public $city;
	/**
	 * @ORM\ManyToMany(targetEntity="Item", inversedBy="employes", cascade={"remove", "persist"})
	 * @ORM\JoinTable(name="employee_items")
	 */
	public $items;
	/**
	 * @var string
	 */
	public $salt;
	/**
	 * @ORM\Column(type="string", length=40)
	 */
	public $hash;
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set surname
     *
     * @param string $surname
     * @return Employee
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
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
        $this->items[] = $items;
	    $this->addCategorie($items->getCategory());
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \GBP\BacardiBundle\Entity\Item $items
     */
    public function removeItem(\GBP\BacardiBundle\Entity\Item $items)
    {
	    $this->removeCategorie($items->getCategory());
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
     * Add categories
     *
     * @param \GBP\BacardiBundle\Entity\Category $categories
     * @return Employee
     */
    private function addCategorie(\GBP\BacardiBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \GBP\BacardiBundle\Entity\Category $categories
     */
    private function removeCategorie(\GBP\BacardiBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }
}