# Terrific Twig
[![Build Status](https://travis-ci.org/deniaz/terrific-twig.svg?branch=master)](https://travis-ci.org/deniaz/terrific-twig)
[![Latest Stable Version](https://poser.pugx.org/deniaz/terrific-twig/v/stable.svg)](https://packagist.org/packages/deniaz/terrific-twig)
[![Total Downloads](https://poser.pugx.org/deniaz/terrific-twig/downloads.svg)](https://packagist.org/packages/deniaz/terrific-twig)
[![License](https://poser.pugx.org/deniaz/terrific-twig/license.svg)](https://packagist.org/packages/deniaz/terrific-twig)

Extension to embrace the [Terrific](https://github.com/brunschgi/terrificjs) frontend methodology in [Twig](http://twig.sensiolabs.org/).

Currently it adds a custom `component` tag to Twig which mimics [Nitro](https://github.com/namics/generator-nitro)'s handlebars helper.

## Installation
Using [composer](https://packagist.org/packages/deniaz/terrific-twig):

```bash
$ composer require deniaz/terrific-twig
```

## Requirements

The following versions of PHP are currently supported.

+ ~~PHP 5.4~~ (**Deprecated**. Builds are failing since the tests are relying on [`::class`](http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class).)
+ PHP 5.5
+ PHP 5.6
+ PHP 7
+ HHVM

## Setup
Step 1: Implement `TemplateInformationProvider`

```php
class TemplateInformationProvider implements TemplateInformationProviderInterface
{
    public function getPaths()
    {
      return []; // List of path where Terrific Components can be found, e.g. (/var/www/example.com/frontend/components)
    }
    
    public function getFileExtension()
    {
      $fileExtension = 'html.twig';
      return $fileExtension;
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
{# Includes the component, component's default data is merged with the context #}
{% component 'Example' %}

{# Includes the component, the default data is injected as a child context #}
{% component 'Example' only %}

{# Includes the component, but a variantion of the component data is merged with the context #}
{% component 'Example' 'example-variant' %}

{# Includes the component, but a variantion of the component data is injected as a child context #}
{% component 'Example' 'example-variant' only %}

{# Includes the component, data object is merged with the context #}
{% component 'Example' { title: 'Inject an Object' } %}

{# Includes the component, data object is injected as a child context #}
{% component 'Example' { title: 'Inject an Object' } only %}
```

## Documentation
### Extension
The extension provides terrific extensions to Twig. Currently the extension provides the `ComponentTokenParser`.

### Token Parser
The token parser contains the parsing step for the component tag. It tokenizes the stream to different nodes (`component`, `data`) and an attribute (`only`).

The functionality is based on the fantastic `Twig_TokenParser_Include`.

### Node
The Node compiles the tokenized tag to PHP. To see some of the output, check the [`ComponentTest`](https://github.com/deniaz/terrific-twig/blob/master/test/Twig/Node/ComponentTest.php).

### Loader
The `TerrificLoader` extends the `Twig_Loader_Filesystem` as it actually loads templates from the filesystem. An implementation of `TemplateLocatorInterface` provides the paths where the loader should search for templates.

### Template Information Provider
An implementation of `TemplateInformationProviderInterface` should return a list of paths where templates live. These should be in the form of `['frontend/components/atoms', 'frontend/components/molecules', 'frontend/components/organisms']`. The component directory will be provided by the `TerrificLoader` (`Example/example.[ext]`).

### Context Provider
This is the tricky part. An implementation of `ContextProviderInterface` decides which data will be made available to Twig templates.
TODO: More on that.

### ConfigReader
Reads nitro's `config.json` and parses essential information such as the component paths and file extension.

## Credits
+ [Twig Template Engine](http://twig.sensiolabs.org/)
+ [Terrific](http://terrifically.org/)
+ [Terrific Micro](https://github.com/namics/terrific-micro)
+ [Terrific Micro Twig](https://github.com/namics/terrific-micro-twig)
+ [Nitro](https://github.com/namics/generator-nitro)

This project is partially sponsored by [Namics](https://github.com/namics).
