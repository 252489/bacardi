<?php

namespace GBP\BacardiBundle\Controller;

use Doctrine\DBAL\DBALException;
use GBP\BacardiBundle\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GBP\BacardiBundle\Entity\Employee;
use GBP\BacardiBundle\Entity\City;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

		$request = $this->get('request');
		if( $request->getMethod() == 'POST' )
		{
			$form->handleRequest($request);
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
					$message = \Swift_Message::newInstance()
						->setSubject('Добро пожаловать на вечеринку Bacardi')
						->setFrom('info@bacardi.com')
						->setTo($employee->getEmail())
						->setBody(
							$this->renderView(
								'GBPBacardiBundle:Default:email.html.twig',
								array(
									'name' => $employee->getName()
									, 'link' => $link = $this->generateUrl('gbp_bacardi_get_employee', array(
										'email' => $employee->getEmail(), 'hash' => $employee->getHash()
									), UrlGeneratorInterface::ABSOLUTE_URL )
								)
							)
						)
					;
					$this->get('mailer')->send($message);
					return $this->redirect($link);
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

//		if( $employee )
//			var_dump($employee);
		return $this->render('GBPBacardiBundle:Default:index.html.twig', array('name' => 'some name'));
	}
}
