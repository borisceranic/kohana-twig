<?php

return array
(
	'environment' => array
	(
		'debug'               => FALSE,
		'trim_blocks'         => FALSE,
		'charset'             => 'utf-8',
		'base_template_class' => 'Twig_Template',
		'cache'               => Kohana::$cache_dir.'/twig',
		'auto_reload'         => TRUE,
		'strict_variables'    => FALSE,
		'autoescape'          => TRUE,
		'optimizations'       => -1,
	),
	'extensions' => array
	(
		// List extension class names
	),
	'templates_dir'  => 'views',
	'suffix'         => 'twig',
);
