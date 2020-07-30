# Terrific Twig
![Build Status](https://github.com/namics/twig-nitro-library/workflows/Twig%20Nitro%20Library/badge.svg)
![Latest version](https://img.shields.io/github/v/release/namics/twig-nitro-library)
![License](https://img.shields.io/github/license/namics/twig-nitro-library)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/namics/twig-nitro-library?color=%23787CB5)

Extension to embrace the [Terrific](https://github.com/brunschgi/terrificjs) frontend methodology in [Twig](http://twig.sensiolabs.org/).

Adds a custom `component` tag to Twig which mimics the [Nitro](https://github.com/namics/generator-nitro) handlebars helper.

## Installation
Using [composer](https://packagist.org/packages/namics/terrific-twig):

```shell script
$ composer require namics/terrific-twig
```

## Requirements

The following versions of PHP are currently supported.
* 7.3
* 7.4

## Setup
Step 1: Implement `TemplateInformationProvider`

```php
class TemplateInformationProvider implements TemplateInformationProviderInterface
{
    public function getPaths() {
    /* List of path where Terrific Components can be found, e.g.
      @code
      [
        '/var/www/example.com/frontend/src/atoms',
        '/var/www/example.com/frontend/src/molecules',
        '/var/www/example.com/frontend/src/organisms'
      ]
      @endcode */
      return [];
    }
}
```

Step 2: Implement `ContextProviderInterface`

```php
class ContextProvider implements ContextProviderInterface
{
    public function compile(Twig_Compiler $compiler, Twig_Node $component, Twig_Node $dataVariant, $only) {
        // ...
    }
}
```

Step 3: Add `TerrificLoader`
```php
$loader = ...;
$chain = new Twig_Loader_Chain([$loader, new TerrificLoader(new TemplateInformationProvider)]);
$twig = new Twig_Environment($chain);
```

Step 4: Add `TerrificExtension`
```php
$twig = new Twig_Environment($chain);
$twig->addExtension(new TerrificExtension(new ContextProvider));
```

Step 5: Profit!

## Usage
```twig
{# Includes the component, data object is merged with the context #}
{% component 'Example' { title: 'Inject an Object' } %}

{# Includes the component, data object is injected as a child context #}
{% component 'Example' { title: 'Inject an Object' } only %}

{# Includes the component, object variable data is merged with the context #}
{% set fooComponentData = { title: 'Inject an Object' } %}
{% component 'Example' fooComponentData %}

{# Includes the component with name contained by string variable, data object is merged with the context #}
{% set fooComponentName = 'Example' %}
{% component fooComponentName { title: 'Inject an Object' } %}

{# Includes the component with name contained by string variable, object variable data is merged with the context #}
{% set fooComponentName = 'Example' %}
{% set fooComponentData = { title: 'Inject an Object' } %}
{% component fooComponentName fooComponentData %}
```

## Documentation
### Extension
The extension provides terrific extensions to Twig. Currently, the extension provides the `ComponentTokenParser`.

### Token Parser
The token parser contains the parsing step for the component tag. It tokenizes the stream to different nodes (`component`, `data`) and an attribute (`only`).

The functionality is based on the fantastic `Twig_TokenParser_Include`.

### Node
The Node compiles the tokenized tag to PHP. To see some of the output, check the [`ComponentTest`](https://github.com/namics/terrific-twig/blob/master/test/Twig/Node/ComponentTest.php).

### Loader
The `TerrificLoader` loads templates contained inside the given paths. An implementation of `TemplateLocatorInterface` provides the paths where the loader should search for templates. Recursively loads any directories contained inside the given directories.

### Template Information Provider
An implementation of `TemplateInformationProviderInterface` should return a list of paths where templates live. These should be in the form of `['frontend/components/atoms', 'frontend/components/molecules', 'frontend/components/organisms']`. The component directory will be provided by the `TerrificLoader` (`Example/example.[ext]`).

### Context Provider
This is the tricky part. An implementation of `ContextProviderInterface` decides which data will be made available to Twig templates.
TODO: More on that.

### ConfigReader
Reads nitro's `config.json` and parses essential information such as the component paths and file extension.

### Tests
Tests can be run by using the following command:
```shell script
composer run-scrip tests
```

### CI
This project uses GitHub actions.
#### Run locally
* Install [nektos/act](https://github.com/nektos/act).
* Open terminal, go to project directory.
* Run `act -P ubuntu-latest=shivammathur/node:latest` as described [here](https://github.com/shivammathur/setup-php#local-testing-setup).

## Credits
+ [Twig Template Engine](http://twig.sensiolabs.org/)
+ [Terrific](http://terrifically.org/)
+ [Terrific Micro](https://github.com/namics/terrific-micro)
+ [Terrific Micro Twig](https://github.com/namics/terrific-micro-twig)
+ [Nitro](https://github.com/namics/generator-nitro)

This project is partially sponsored by [Namics](https://github.com/namics).
