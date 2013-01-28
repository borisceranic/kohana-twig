<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Base class for implementing Twig_ExtensionInterface with automatic registration of filter,
 * function and test methods (via reflection).
 *
 * @package    Appricot/Twig
 * @category   Extensions/Twig
 * @copyright  (c) 2012-2013 Appricot
 * @author     Aleksandar Ružičić <aleksandar@ruzicic.info>
 */
abstract class Twig_Extension implements Twig_ExtensionInterface
{
	protected $globals = array();
	protected $filters = array();
	protected $functions = array();
	protected $tests = array();
	protected $operators = array();
	protected $parsers = array();
	protected $nodes = array();
	protected $name;

    /**
     * Called during construction phase. Can be overriden in implementing class to provide
     * additional filters, functions, etc.
     */
    protected function initialize()
    {
    }

    /**
     * Constructs new extension object.
     *
     * Automatically defines (by reflection) filters, functions and tests from instance methods
     * with names matching following patterns:
     *
     * public function function_<function_name>()  - defines <function_name> function
     * public function filter_<filter_name>()      - defines <filter_name> filter
     * public function test_<test_name>()          - defines <test_name> test
     */
	public function __construct()
	{
        $this->initialize();

		$class = new ReflectionClass($this);

		$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

		$option_names = array('is_safe', 'pre_escape');

		foreach ($methods as $method)
		{
			if (($is_filter = substr($method->name, 0, 7) == 'filter_') or substr($method->name, 0, 9) == 'function_')
			{
				$params = $method->getParameters();
				$options = array();

				foreach ($params as $param)
				{
					$param_name = $param->getName();

					if ($param_name == 'env')
					{
						$options['needs_environment'] = true;
					}
					else if ($param->isOptional() and in_array($param_name, $option_names))
					{
						$options[$param_name] = $param->getDefaultValue();
					}
				}

				if ($is_filter)
				{
					$this->filters[substr($method->name, 7)] = new Twig_Filter_Function(array($this, $method->name), $options);
				}
				else
				{
					$this->functions[substr($method->name, 9)] = new Twig_Function_Method($this, $method->name, $options);
				}
			}
			elseif (substr($method->name, 0, 5) == 'test_')
			{
				$this->tests[substr($method->name, 5)] = new Twig_Test_Method($this, $method->name);
			}
		}

		if (empty($this->name))
		{
			$this->name = strtolower(str_replace('_Twig_Extension', '', $class->name));
		}
	}

    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(Twig_Environment $environment)
    {
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return $this->parsers;
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return array An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return $this->nodes;
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return $this->operators;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return $this->globals;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
    	return $this->name;
    }
}
