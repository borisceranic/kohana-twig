<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Various useful Twig extensions.
 *
 * @package    Appricot/Twig
 * @category   Extensions/Twig
 * @copyright  (c) 2012-2013 Appricot
 * @author     Aleksandar Ružičić <aleksandar@ruzicic.info>
 */
class Appricot_Twig_Extension extends Twig_Extension
{
	/**
	 * @var array  Used for email_body_height memoization
	 */
	private $body_height_cache = array();

	/**
	 * Default options for email_body_height function
	 * @var array
	 */
	public $body_height_options = array(
		'recalc' => false,              // set to true to force recalculation (skips cache check)
		'metrics' => array(             // text metrics used in calculation
			'p' => array(               // metrics for <p> element, also used for <br>
				'line-length'   => 75,  // in characters, approximate
				'line-height'   => 19,  // in pixels
				'margin'        => 25,  // in pixels (sum vertical margins and paddings)
			),
			'h1' => array(              // metrics for <h1> element
				'line-length'   => 48,  // in characters, approximate
				'line-height'   => 22,  // in pixels
				'margins'       => 45,  // in pixels (sum vertical margins and paddings)
			),
		),
	);

	/**
	 * Calculates approximate height of HTML email body text
	 *
	 * @param  string $body    body of HTML email message to calculate height for
	 * @param  array  $options options array containing metrics parameters
	 * @return int             approximate height in pixels of $body
	 */
	public function function_email_body_height($body, array $options = array())
	{
		$options = array_merge($this->body_height_options, $options);
		$cache_key = isset($options['cache_key']) ? $options['cache_key'] : md5($body);

		if ( ! $options['recalc'] and isset($this->body_height_cache[$cache_key]))
		{
			return $this->body_height_cache[$cache_key];
		}

		$height = 0;

		$dom = new DOMDocument;
		$dom->loadHTML('<html><body id="email_body">' . $body . '</body></html>');

		$tag_names = array_map('strtolower', array_keys($options['metrics']));

		foreach ($dom->getElementById('email_body')->childNodes as $node)
		{
			if ( ! ($node instanceof DOMElement))
			{
				continue;
			}

			$tag_name = strtolower($node->nodeName);

			if (in_array($tag_name, $tag_names))
			{
				$metrics = $options['metrics'][$tag_name];

				$line_chars = 0;
				$lines = 0;

				foreach ($node->childNodes as $child)
				{
					if (strtolower($child->nodeName) == 'br')
					{
						$lines++;
						$line_chars = 0;
					}
					else
					{
						$line_chars += strlen($child->textContent);
						$lines += floor($line_chars / $metrics['line-length']);
						$line_chars %= $metrics['line-length'];
					}
				}

				if ($line_chars > 0)
				{
					$lines++;
				}

				$height += $metrics['margins'] + $metrics['line-height'] * $lines;
			}
			elseif ($tag_name == 'br' and isset($options['metrics']['p']))
			{
				$height += $options['metrics']['p']['line-height'];
			}
		}

		return ($this->body_height_cache[$cache_key] = $height);
	}

	/**
	 * Provide additional extensions.
	 */
	protected function initialize()
	{
		// Make Route::url available to templates as 'url' function
		$this->functions['url'] = new Twig_Function_Function('Route::url');
	}
}
