# Terrific Twig
[![Build Status](https://travis-ci.org/deniaz/terrific-twig.svg?branch=master)](https://travis-ci.org/deniaz/terrific-twig)
[![Latest Stable Version](https://poser.pugx.org/deniaz/terrific-twig/v/stable.svg)](https://packagist.org/packages/deniaz/terrific-twig)
[![Total Downloads](https://poser.pugx.org/deniaz/terrific-twig/downloads.svg)](https://packagist.org/packages/deniaz/terrific-twig)
[![License](https://poser.pugx.org/deniaz/terrific-twig/license.svg)](https://packagist.org/packages/deniaz/terrific-twig)

This Twig Extension adds a new `component` tag to Twig which works just like the `component` helper from [Nitro](https://github.com/namics/generator-nitro).

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

## Usage
```twig
{# Includes the component, component's default data is merged with the context #}
{% component 'component-name' %}

{# Includes the component, the default data is injected as a child context #}
{% component 'component-name' only %}

{# Includes the component, but a variantion of the component data is merged with the context #}
{% component 'component-name' 'component-name-variation' %}

{# Includes the component, but a variantion of the component data is injected as a child context #}
{% component 'component-name' 'component-name-variation' only %}

{# Includes the component, data object is merged with the context #}
{% component 'component-name' { foo: 'bar' } %}

{# Includes the component, data object is injected as a child context #}
{% component 'component-name' { foo: 'bar' } only %}
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

### ConfigReader
Reads nitro's `config.json` and parses essential information such as the component paths and file extension.

## Credits
+ [Twig Template Engine](http://twig.sensiolabs.org/)
+ [Terrific](http://terrifically.org/)
+ [Terrific Micro](https://github.com/namics/terrific-micro)
+ [Terrific Micro Twig](https://github.com/namics/terrific-micro-twig)
+ [Nitro](https://github.com/namics/generator-nitro)

This project is partially sponsored by [Namics](https://github.com/namics).
