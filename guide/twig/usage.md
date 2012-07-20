# Usage

Twig is used in a way similar to [Kohana Views](../kohana/mvc/views). In order
to display a Twig template, you must create a controller that extends
[Controller_Twig] class.

**classes/controller/demo.php**

	class Controller_Demo extends Controller_Twig
	{
		public function action_index()
		{
			$this->template->title_suffix = 'Sample Application';
		}
	}

By assigning values to properties of `$this->template` object, you are
effectively assigning template variables.

When index method is executed (ie. by visiting http://example.com/demo/index),
it will attempt loading file **views/demo/index.twig**. Please note view
file's extension: *twig*. It is default extension, which can be changed using
twig config file (see config/twig.php file in your twig module dir).

**views/demo/index.twig**

	{% extends "templates/base.twig" %}

	{% block title %}Hello World!{% endblock %}

	{% block content %}
	{{ parent() }}
				<p>Hello World!</p>
				<p>We are extending content of parent() block.</p>
	{% endblock %}

You might be wondering why I didn't put `title_suffix` variable in template.
I wanted to point out that variable context is shared between currently
rendered template, base template and all templates included from within any
other template.

If you tried to access your demo controller, you would most likely get an
exception saying:

	Twig_Error_Loader [ 0 ]: Unable to find template "templates/base.twig" (looked into: ...) in "demo/index.twig".

This is because of the topmost line in index.twig template, which tells
template to extend existing template (located elsewhere). In Twig, this is
known as a [Template Inheritance](http://twig.sensiolabs.org/doc/templates.html#template-inheritance).

Point of using it here was to show that paths are relative to your views
directory. For reference, here's a simple base.twig template.

**views/templates/base.twig**

	<!DOCTYPE html>
	<html>
		<head>
	{% block head %}
			<meta charset="utf-8" />
			<title>{% block title %}{% endblock %} - {{ title_suffix}}</title>
	{% endblock head %}
		</head>
		<body>
			<div id="wrapper">
	{% block content %}
				<h1>Base template</h1>
				<p>This is a sample content block.</p>
	{% endblock %}
			</div>
		</body>
	</html>

We can see that `title_suffix` variable is being used.

That's all! :)
