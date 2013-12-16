<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 11.12.13
 * Time: 12:00
 */

namespace GBP\BacardiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use GBP\BacardiBundle;

/**
 * @ORM\Entity
 * @ORM\Table(name="city")
 */
class City {
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
	 * @ORM\OneToMany(targetEntity="Employee", mappedBy="city")
	 */
	protected $employes;

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
     * @return City
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
     * Add employes
     *
     * @param \GBP\BacardiBundle\Entity\Employee $employes
     * @return City
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

	public function __toString()
	{
		return $this->getName();
	}
}