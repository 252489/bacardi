<?php

namespace GBP\BacardiBundle\Controller;

use Doctrine\DBAL\DBALException;
use GBP\BacardiBundle\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GBP\BacardiBundle\Entity\Employee;
use GBP\BacardiBundle\Entity\City;
use Symfony\Component\Form\FormError;

class DefaultController extends Controller
{
	public function indexAction()
	{
		$employee = new Employee();
		$employee
			->setEmail('П@чта')
			->setName('имя')
			->setSurname('фамилия')
		;

		$form = $this->createFormBuilder($employee)
			->add('name', 'text')
			->add('surname', 'text')
			->add('email', 'text')
			->add('city', new CityType())
			->add('save', 'submit')
			->getForm();

		if( $this->getRequest()->getMethod() == 'POST' )
		{
			$form->handleRequest($this->getRequest());
			if ($form->isValid())
			{
				$city = $this->getDoctrine()
					->getRepository('GBPBacardiBundle:City')->findOneByName( $employee->getCity()->getName() ) ? : $employee->getCity();
				$employee->setCity($city);
				$em = $this->getDoctrine()->getManager();
				$em->persist($city);
				$em->persist($employee);
				try {
					$em->flush();
				} catch( DBALException $e ) {
					if( $e->getPrevious()->getCode() === '23000' )
						$form->addError( new FormError("Такой пользователь уже существует") );
				}
			}
		}
		return $this->render('GBPBacardiBundle:Default:index.html.twig', array('form' => $form->createView() ));
	}

	public function getAction($email, $hash)
	{
		/**
		 * @var $employee Employee
		 */
		$employee = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Employee')
			->findOneBy(array('hash' => $hash, 'email' => $email));

		if( $employee )
			var_dump($employee);
		return $this->render('GBPBacardiBundle:Default:index.html.twig', array('name' => 'some name'));
	}
}
