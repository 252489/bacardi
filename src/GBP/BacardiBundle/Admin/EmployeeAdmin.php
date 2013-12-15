<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Koretskiy
 * Date: 15.12.13
 * Time: 18:12
 */

namespace GBP\BacardiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use GBP\BacardiBundle\Form\CityType;
use Sonata\AdminBundle\Route\RouteCollection;

class EmployeeAdmin extends Admin {

	protected function configureRoutes(RouteCollection $collection)
	{
		 $collection->clearExcept(array('list', 'show'));
	}

	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->add('name', 'text', array('label' => 'Имя'))
			->add('surname', 'text', array('label' => 'Фамилия'))
			->add('city', new CityType(), array('label' => 'Город'))
			->add('email', 'text', array('label' => 'Em@il'))
		;
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
			->add('name')
			->add('surname')
			->add('city')
			->add('email')
		;
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('email')
			->add('name')
			->add('surname')
			->add('city')
		;
	}
} 