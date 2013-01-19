<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Twig Autoloader replacement for Kohana
 *
 * Interface is compatible with original Autoloader.
 *
 * @package Appricot/Twig
 * @author  Boris Ceranic <zextra@gmail.com>
 */
class Appricot_Twig_Autoloader {

	/**
	 * A module-relative path to a vendor directory.
	 */
	protected static $LIBRARY_DIR = 'vendor/lib';

	/**
	 * Register a custom autoloader
	 *
	 * @param string OPTIONAL path to a Twig library directory
	 */
	public static function register($library_dir = NULL)
	{
		if ($library_dir !== NULL)
		{
			static::$LIBRARY_DIR = $library_dir;
		}
		spl_autoload_register(array(new static, 'autoload'));
	}

	/**
	 * Autoload a class from Twig_* namespace
	 *
	 * This method is mostly a copy of {@link Kohana::auto_load()} method.
	 *
	 * @param  string Name of class to be loaded
	 * @return bool   TRUE on success
	 */
	public static function autoload($class)
	{
		if (0 !== strpos($class, 'Twig'))
		{
			return;
		}

		try
		{
			// Generate filename from class name, so it can be found
			$file = str_replace(array('_', "\0"), array('/', ''), $class);

			if ($path = Kohana::find_file(static::$LIBRARY_DIR, $file))
			{
				// Load the class file
				require $path;

				// Class has been found
				return TRUE;
			}

			// Class is not in the filesystem
			return FALSE;
		}
		catch (Exception $e)
		{
			// Display error page, curl up and die...
			Kohana_Exception::handler($e);
		}
	}
}
