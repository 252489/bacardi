<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 19.12.13
 * Time: 11:58
 */

namespace GBP\BacardiBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

class EmployeeCRUDController extends CRUDController {

	public function downloadAction()
	{
		$id = $this->get('request')->get($this->admin->getIdParameter());

		$employee = $this->admin->getObject($id);
		if (!$employee) {
			throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
		}
		$photo = str_replace('data:image/png;base64,', '', $employee->getResultphoto());
		$img_data = base64_decode($photo);
		return new Response($img_data, 200, array(
			'Content-Type' => 'image/png',
			'Content-Disposition' => 'attachment; filename="'.$employee->getName() . '_' . $employee->getSurname() .'.png"',
			'refresh' => '5;url=finish'
		));
	}
} 