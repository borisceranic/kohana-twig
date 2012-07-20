<?php defined('SYSPATH') or die('No direct script access.');

return array(
	// Leave this alone
	'modules' => array(

		// This should be the path to this modules userguide pages, without the 'guide/'. Ex: '/guide/modulename/' would be 'modulename'
		'twig' => array(

			// Whether this modules userguide pages should be shown
			'enabled' => TRUE,

			// The name that should show up on the userguide index page
			'name' => 'Twig',

			// A short description of this module, shown on the index page
			'description' => 'The flexible, fast, and secure template engine for PHP.',

			// Copyright message, shown in the footer for this module
			'copyright' => 'Twig module &copy; 2012 Appricot &mdash; Twig &copy; 2010-2012 Sensio Labs',
		)
	)
);
