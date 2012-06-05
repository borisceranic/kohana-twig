<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Twig Templating Library for Kohana
 *
 * @package Appricot/Twig
 * @author  Boris Ceranic <zextra@gmail.com>
 */

/*
 * In order to leverage Kohana's caching facilities, a custom autoloader will
 * be used, instead of Twig's default one.
 */
Twig_Autoloader::register();
