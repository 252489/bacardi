<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 19.12.13
 * Time: 17:42
 */

namespace GBP\BacardiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Controller\CoreController;

class AdminController extends CoreController {

	public function downloadAllAction(Request $request)
	{
		$employees = $this->getDoctrine()->getRepository('GBPBacardiBundle:Employee')->findAll();
		$zip = new \ZipArchive();
		$file = tempnam(sys_get_temp_dir(), 'zip');
		$zip->open($file, \ZipArchive::CREATE);
		foreach( $employees as $employee )
		{
			if( !$employee->getResultphoto() ) continue;
			$photo = str_replace('data:image/png;base64,', '', $employee->getResultphoto());
			$img_data = base64_decode($photo);

			$zip->addFromString($employee->getName() . '_' . $employee->getSurname() .'.png', $img_data);
			unset($img_data);
		}
		$c = $zip->numFiles;
		$zip->close();

		if( $c )
		{
			return new Response(file_get_contents($file), 200, array(
				'Content-Type' => ' application/zip',
				'Content-Description' => 'File Transfer',
				'Content-Transfer-Encoding' => 'binary',
				'Cache-Control' => 'public',
				'Pragma' => 'private',
				'Content-Disposition' => 'attachment; filename="all_looks.zip"'
			));
		}
	}

} 