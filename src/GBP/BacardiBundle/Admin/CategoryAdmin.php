<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Koretskiy
 * Date: 15.12.13
 * Time: 18:27
 */

namespace GBP\BacardiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class CategoryAdmin extends Admin {
	protected function configureRoutes(RouteCollection $collection)
	{
//		$collection->clearExcept(array('list', 'show'));
	}

	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->add('name', 'text', array('label' => 'Наименование'))
			->add('isFemale', 'checkbox', array('label' => 'Для женщин?', 'required' => false))
		;
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
			->add('name')
			->add('isFemale')
		;
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('name')
			->add('isFemale')
		;
	}
} 