# Kohana Twig module

This is a Kohana module for Twig.

[Twig](http://twig.sensiolabs.org/) is a [PHP](http://php.net/) template
engine, developed by [Sensio Labs](http://sensiolabs.com/).

This module is essentially a wrapper around the original library.

## Installation

Add this module to your project as a git submodule by entering following
command in your command line:

	git submodule add https://github.com/zextra/kohana-twig.git modules/twig

This will checkout latest stable version of module into `modules/twig`
directory of your project.

Next, include this module in your `bootstrap.php` file:

	Kohana::modules(array(
		// ...
		'twig'      => MODPATH.'twig',      // Twig templating library
	));

See `guide/twig/usage.md` file for usage example.
