<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Twig loader
 *
 * @package Appricot/Twig
 * @author  John Heathco <jheathco@gmail.com>
 * @author  Boris Ceranic <zextra@gmail.com>
 */
class Appricot_Twig {

	/**
	 * @var  object  Twig instance
	 */
	public static $instance;

	/**
	 * @var  Twig_Environment
	 */
	public $twig;

	/**
	 * @var  object  Twig configuration (Kohana_Config object)
	 */
	public $config;

	public static function instance()
	{
		if ( ! Twig::$instance)
		{
			Twig::$instance = new Twig;

			// Load Twig configuration
			Twig::$instance->config = Kohana::$config->load('twig');

			// Array of template locations in cascading filesystem
			$template_paths = array(APPPATH.'views');
			foreach (Kohana::modules() as $module_path)
			{
				$temp_path = $module_path.'views';
				if (is_dir($temp_path))
				{
					$template_paths[] = $temp_path;
				}
				unset($temp_path);
			}

			// Create the the loader
			$loader = new Twig_Loader_Filesystem($template_paths);

			// Set up Twig
			Twig::$instance->twig = new Twig_Environment($loader, Twig::$instance->config->environment);

			foreach (Twig::$instance->config->extensions as $extension)
			{
				// Load extensions
				Twig::$instance->twig->addExtension(new $extension);
			}
		}

		return Twig::$instance;
	}

	final protected function __construct()
	{
		// This is a singleton class
	}

}
