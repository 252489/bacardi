<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy Koretskiy
 * Date: 15.12.13
 * Time: 17:02
 */

namespace GBP\BacardiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ItemAdmin extends Admin {

	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->add('name', 'text', array('label' => 'Наименование'))
			->add('category', 'entity', array('class' => 'GBP\BacardiBundle\Entity\Category'))
			->add('file', 'file', array('label' => 'Изображение на манекене', 'data_class' => null))
			->add('filePreview', 'file', array('label' => 'Изображение в списке', 'data_class' => null))
			->add('layer', 'text', array('label' => 'Слой на манекене'))
		;
	}

	// Fields to be shown on filter forms
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
			->add('name')
			->add('category')
		;
	}

	// Fields to be shown on lists
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('name')
			->add('category')
		;
	}
}