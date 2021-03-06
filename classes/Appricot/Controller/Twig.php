<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Twig template controller
 *
 * @package Appricot/Twig
 * @author  John Heathco <jheathco@gmail.com>
 * @author  Boris Ceranic <zextra@gmail.com>
 */
abstract class Appricot_Controller_Twig extends Controller {

	/**
	 * @var boolean  Auto-render template after controller method returns
	 */
	public $auto_render = TRUE;

	/**
	 * @var object Twig_View instance
	 */
	public $template = NULL;

	public function before()
	{
		if ($this->auto_render)
		{
			// Auto-generate template filename
			// ie. Controller_Admin_Users::action_index() => 'admin/users/index'
			if ($this->template === NULL)
			{
				$this->template = $this->request->controller().'/'.$this->request->action();

				if ($this->request->directory())
				{
					// Preprend directory if needed
					$this->template = $this->request->directory().'/'.$this->template;
				}
			}

			// controllers can be in_sub_directories, so fix that
			$this->template = str_replace('_', '/', $this->template);

			$this->template = Twig_View::factory($this->template);
		}
	}

	public function after()
	{
		if ($this->auto_render)
		{
			// Auto-render the template
			$this->response->body($this->template->render());
		}
	}
}
