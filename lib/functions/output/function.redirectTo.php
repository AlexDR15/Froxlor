<?php

/**
 * This file is part of the Froxlor project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
 * Copyright (c) 2010 the Froxlor Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.froxlor.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org> (2003-2009)
 * @author     Froxlor team <team@froxlor.org> (2010-)
 * @license    GPLv2 http://files.froxlor.org/misc/COPYING.txt
 * @package    Functions
 *
 */

/**
 * Sends an header ( 'Location ...' ) to the browser.
 *
 * @param   string   Destination
 * @param   array    Get-Variables
 * @param   boolean  if the target we are creating for a redirect
 *                   should be a relative or an absolute url
 *
 * @return  boolean  false if params is not an array
 *
 * @author  Florian Lippert <flo@syscp.org>
 * @author  Martin Burchert <eremit@syscp.org>
 * @changes martin@2005-01-29
 *          - added isRelative parameter
 *          - speed up the url generation
 *          - fixed bug #91
 */

function redirectTo($destination, $get_variables = array(), $isRelative = false)
{
	$params = array();

	$linker = new linker();
	$linker->filename = $destination;

	if(is_array($get_variables))
	{
		foreach($get_variables as $key => $value)
		{
			$linker->add($key, $value);
		}

		if($isRelative)
		{
			$linker->protocol = '';
			$linker->host = '';
			$path = './';
		}
		else
		{
			if(isset($_SERVER['HTTPS'])
			   && strtolower($_SERVER['HTTPS']) == 'on')
			{
				$linker->protocol = 'https';
			}
			else
			{
				$linker->protocol = 'http';
			}
			if(dirname($_SERVER['PHP_SELF']) == '/')
			{
				$path = '/';
			}
			else
			{
				$path = dirname($_SERVER['PHP_SELF']) . '/';
			}
			$linker->host = $_SERVER['HTTP_HOST'];
		}
		$linker->filename = $path . $destination;
		header('Location: ' . $linker->getLink());
		exit;
	}
	elseif($get_variables == null)
	{
		header('Location: ' . $linker->getLink());
		exit;
	}

	return false;
}
