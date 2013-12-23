<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits ( gillbeits[at]gmail[dot]com )
 * Date: 23.12.13
 * Time: 12:29
 */

namespace GBP\BacardiBundle\Twig\Extension;

class FileExistsExtension extends \Twig_Extension
{
	/**
	 *Return the function registered as twig extension
	 *
	 *@return array
	 */
	public function getFunctions()
	{
		return array(
			'file_exists' => new \Twig_Function_Function('file_exists'),
		);
	}

	public function getName()
	{
		return 'gbp_file_exists';
	}
}