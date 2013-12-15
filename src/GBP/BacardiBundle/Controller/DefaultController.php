<?php

namespace GBP\BacardiBundle\Controller;

use Doctrine\DBAL\DBALException;
use GBP\BacardiBundle\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GBP\BacardiBundle\Entity\Employee;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
	public function indexAction()
	{
		if( $this->get('session')->get('user') )
			return $this->redirect( $this->generateUrl('gbp_bacardi_get_employee', array(
				'email' => $this->get('session')->get('user')->getEmail(), 'hash' => $this->get('session')->get('user')->getHash()
			), UrlGeneratorInterface::ABSOLUTE_URL ) );

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

		if( $employee )
		{
			$this->get('session')->set('user', $employee);
			if( $employee->getPhoto() ) return $this->redirect( $this->generateUrl('gbp_bacardi_cabinet') );
			elseif( $this->get('request')->getMethod() == 'POST' && $this->get('request')->get('photo') && $this->get('request')->get('sex') ) {
				$employee
					->setIsFemale( $this->get('request')->get('sex') == 'woman' )
					->setPhoto( $this->get('request')->get('photo') );
				$em = $this->getDoctrine()->getManager();
				$em->persist($employee);
				$em->flush();
				return $this->redirect( $this->generateUrl('gbp_bacardi_cabinet') );
			}
		} else {
			$this->get('session')->getFlashBag()->add(
				'error',
				'Такого пользователя не существует или ошибка в адресе'
			);
			return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		}
		return $this->render('GBPBacardiBundle:Default:photo.html.twig', array('name' => 'some name'));
	}

	public function cabinetAction()
	{
		if( !$this->get('session')->get('user') ) return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		$employee = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Employee')
			->findOneBy(array('hash' => $this->get('session')->get('user')->getHash(), 'email' => $this->get('session')->get('user')->getEmail()));

		$items = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Item')
			->findBySexJoinedToCategory($employee->getIsFemale())
		;

		return $this->render('GBPBacardiBundle:Default:cabinet.html.twig', array(
			'categories' => $employee->getCategories()
			, 'is_female' => $employee->getIsFemale()
			, 'items' => $items

		));
	}

	public function setitemAction($id)
	{
		if( !$this->get('session')->get('user') ) return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );

		/**
		 * @var $request \Symfony\Component\HttpFoundation\Request
		 */
		$request = $this->get('request');
//		if( !$request->isXmlHttpRequest() )
//			throw new \Exception("Данный запрос должен быть XmlHttpRequest");

		$employee = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Employee')
			->findOneBy(array('hash' => $this->get('session')->get('user')->getHash(), 'email' => $this->get('session')->get('user')->getEmail()));

		$item = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Item')->find($id);
		if( !$item ) throw new \Exception("Не найден элемент с таким ID");

		$employee->addItem($item);

		$em = $this->getDoctrine()->getManager();
		$em->persist($employee);
		$em->flush();

		return $this->render( 'GBPBacardiBundle:Default:setitem.json.twig', array('data' => array('success' => true)) );
	}
}
