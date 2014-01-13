<?php

namespace GBP\BacardiBundle\Controller;

use Doctrine\DBAL\DBALException;
use GBP\BacardiBundle\Form\CityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GBP\BacardiBundle\Entity\Employee;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
	public function indexAction()
	{
		if( $this->get('session')->get('user') )
		{
			$employee = $this->getDoctrine()
				->getRepository('GBPBacardiBundle:Employee')
				->findOneBy(array('hash' => $this->get('session')->get('user')->getHash(), 'email' => $this->get('session')->get('user')->getEmail()));
			if( $employee )
				return $this->redirect( $this->generateUrl('gbp_bacardi_get_employee', array(
					'email' => $employee->getEmail(), 'hash' => $employee->getHash()
				), UrlGeneratorInterface::ABSOLUTE_URL ) );
			else
				$this->get('session')->set('user', null);
		}

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

				$message = \Swift_Message::newInstance()
					->setSubject('Добро пожаловать на вечеринку Bacardi')
					->setFrom( $this->container->getParameter('mailer_user') )
					->setTo($employee->getEmail())
				;

				try {
					if( mb_strtolower( $employee->getCity()->getName(), 'UTF-8' ) == 'город' )
						throw new \Exception( "Вы не указали город" );
					$em->flush();
					$link = $this->generateUrl('gbp_bacardi_get_employee', array(
						'email' => $employee->getEmail(), 'hash' => $employee->getHash()
					), UrlGeneratorInterface::ABSOLUTE_URL );
					$this->get('mailer')->send(
						$message->setBody(
							$this->renderView(
								'GBPBacardiBundle:Default:startemail.html.twig',
								array(
									'name' => $employee->getName()
								, 'link' => $link
								)
							)
						)
					);
					return $this->redirect($link);
				} catch( DBALException $e ) {
					if( $e->getPrevious()->getCode() === '23000' )
					{
						/**
						 * @var $employee Employee
						 */
						$employee = $this->getDoctrine()
							->getRepository('GBPBacardiBundle:Employee')
							->findOneBy(array('email' => $employee->getEmail()));

						$link = $this->generateUrl('gbp_bacardi_get_employee', array(
							'email' => $employee->getEmail(), 'hash' => $employee->getHash()
						), UrlGeneratorInterface::ABSOLUTE_URL );

						$this->get('mailer')->send(
							$message->setBody(
								$this->renderView(
									'GBPBacardiBundle:Default:startemail.html.twig',
									array(
										'name' => $employee->getName()
									, 'link' => $link
									)
								)
							)
						);
						$form->addError( new FormError("Такой пользователь уже существует. Ссылка повторно отправлена на ваш email") );
					}
				} catch (\Exception $e) {
					$form->addError( new FormError($e->getMessage()) );
				}
			} else {
				/**
				 * @var $validator Validator
				 */
				$validator = $this->get('validator');
				foreach( $validator->validate($employee) as $error )
					$form->addError( new FormError( $error->getMessage() ) );
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

			if( $employee->getResultphoto() ) return $this->redirect( $this->generateUrl('gbp_bacardi_image_options') );

			$form = $this->createFormBuilder($employee)
				->add('isFemale', 'hidden')
				->add('photo', 'hidden')
				->add('save', 'submit')
				->getForm();

//			if( $employee->getPhoto() ) return $this->redirect( $this->generateUrl('gbp_bacardi_cabinet') );
//			else
			if( $this->get('request')->getMethod() == 'POST' ) {

				$form->handleRequest($this->get('request'));
				if( $form->isValid() )
				{
					$employee
						->setIsFemale( $employee->getIsFemale() )
						->setPhoto( $employee->getPhoto() );
					$em = $this->getDoctrine()->getManager();
					$em->persist($employee);
					$em->flush();
					return $this->redirect( $this->generateUrl('gbp_bacardi_cabinet') );
				}
			}
		} else {
			$this->get('session')->set('user', null);
			$this->get('session')->getFlashBag()->add(
				'error',
				'Такого пользователя не существует или ошибка в адресе'
			);
			return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		}
		return $this->render('GBPBacardiBundle:Default:photo.html.twig', array('form' => $form->createView()));
	}

	public function cabinetAction()
	{
		if( !$this->get('session')->get('user') ) return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		$employee = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Employee')
			->findOneBy(array('hash' => $this->get('session')->get('user')->getHash(), 'email' => $this->get('session')->get('user')->getEmail()));

		if( !$employee )
		{
			$this->get('session')->set('user', null);
			$this->get('session')->getFlashBag()->add(
				'error',
				'Такого пользователя не существует или ошибка в адресе'
			);
			return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		}

		if( $employee && $employee->getResultphoto() ) return $this->redirect( $this->generateUrl('gbp_bacardi_image_options') );

		$items = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Item')
			->findBySexJoinedToCategory($employee->getIsFemale())
		;

		$_categories = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Category')
			->findAll()
		;

		$categories = array();
		foreach($_categories as $c)
			if( $c->getIsFemale() == $employee->getIsFemale() )
				$categories[$c->getNamelink()] = $c;

		$form = $this->createFormBuilder($employee)
			->add('resultPhoto', 'hidden')
			->getForm();

		if( $this->get('request')->getMethod() == 'POST' )
		{
			$form->handleRequest($this->get('request'));
			if( $form->isValid() )
			{
				$employee->setResultphoto( $employee->getResultphoto() );
				$em = $this->getDoctrine()->getManager();
				$em->persist($employee);
				$em->flush();

				return $this->redirect( $this->generateUrl('gbp_bacardi_image_options') );
			}
		}
		return $this->render('GBPBacardiBundle:Default:cabinet.html.twig', array(
			'active_categories' => $employee->getCategories()
			, 'categories' => $categories
			, 'is_female' => $employee->getIsFemale()
			, 'items' => $items
			, 'photodata' => $employee->getResizedPhoto(60)
			, 'form' => $form->createView()
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

	public function resultAction()
	{

		$employee = $this->getDoctrine()
			->getRepository('GBPBacardiBundle:Employee')
			->findOneBy(array('hash' => $this->get('session')->get('user')->getHash(), 'email' => $this->get('session')->get('user')->getEmail()));
		if( !$employee )
		{
			$this->get('session')->set('user', null);
			$this->get('session')->getFlashBag()->add(
				'error',
				'Такого пользователя не существует или ошибка в адресе'
			);
			return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		}

		$form = $this->createFormBuilder($employee)
			->add('resultPhoto', 'hidden')
			->add('__type', 'hidden', array("mapped" => false))
			->getForm();

		$r_photo = $employee->getResultphoto();
		if( $this->get('request')->getMethod() == 'POST' )
		{

			$form->handleRequest($this->get('request'));
			if( $form->isValid() )
			{
				$photo = str_replace('data:image/png;base64,', '', $employee->getResultphoto());
				$img_data = base64_decode($photo);
				$filename = realpath( dirname(__FILE__) . '/../../../../web/upload/' ) . '/' . $employee->getEmail() . '.png';
				file_put_contents( $filename, $img_data );

				// 0 - send to email, 1 - save to disk
				if( $form['__type']->getData() == 0 )
				{
					$message = \Swift_Message::newInstance();
					$message->setSubject('Спасибо за примерку Bacardi')
						->setFrom( $this->container->getParameter('mailer_user') )
						->setTo($employee->getEmail())
						->attach( \Swift_Attachment::fromPath($filename) )
						->setBody(
							$this->renderView(
								'GBPBacardiBundle:Default:email.html.twig',
								array('name' => $employee->getName(), 'is_female' => $employee->getIsFemale())
							)
							, 'text/html'
						)
					;
					$this->get('mailer')->send($message);
				} else {
					$link = $this->generateUrl('gbp_bacardi_finish', array(
						'getphoto' => true ));
					return $this->redirect( $link );
				}
				return $this->redirect( $this->generateUrl('gbp_bacardi_finish', array('getphoto' => 0)) );
			}
		}
		return $this->render( 'GBPBacardiBundle:Default:result.html.twig', array('result' => $r_photo, 'form' => $form->createView() ) );
	}

	public function finishAction($getphoto=null)
	{
		if( !$this->get('session')->get('user') ) return $this->redirect( $this->generateUrl('gbp_bacardi_homepage') );
		$filename = realpath( dirname(__FILE__) . '/../../../../web/upload/' ) . '/' . $this->get('session')->get('user')->getEmail() . '.png';
		if( $getphoto == 2 && file_exists($filename) )
		{
			$img_data = file_get_contents($filename);
			return new Response($img_data, 200, array(
				'Content-Type' => 'image/png',
				'Content-Disposition' => 'attachment; filename="'.$this->get('session')->get('user')->getEmail().'.png"',
				'refresh' => '5;url=finish'
			));
		}
		return $this->render( 'GBPBacardiBundle:Default:finish.html.twig', array('getphoto' => $getphoto) );
	}
}
