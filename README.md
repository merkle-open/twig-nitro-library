# Terrific Twig
[![Build Status](https://travis-ci.org/deniaz/terrific-twig.svg?branch=master)](https://travis-ci.org/deniaz/terrific-twig)
[![Latest Stable Version](https://poser.pugx.org/deniaz/terrific-twig/v/stable.svg)](https://packagist.org/packages/deniaz/terrific-twig)
[![Total Downloads](https://poser.pugx.org/deniaz/terrific-twig/downloads.svg)](https://packagist.org/packages/deniaz/terrific-twig)
[![License](https://poser.pugx.org/deniaz/terrific-twig/license.svg)](https://packagist.org/packages/deniaz/terrific-twig)

This Twig Extensions adds a new `component` tag to Twig which works just like the `component` handlebars helper 
from [Splendid](https://github.com/namics/generator-splendid).

## Installation
Using [composer](https://packagist.org/packages/deniaz/terrific-twig):

```bash
$ composer require deniaz/terrific-twig
```
## Requirements

The following versions of PHP are supported by this version.

+ PHP 5.4
+ PHP 5.5
+ PHP 5.6
+ HHVM

## Usage
```twig
{# Includes the Navigation/navigation.html.twig template #}
{% component 'Navigation' %}

{# Includes the Navigation component and injects the additional variable foo (= "bar") #}
{% component with {"foo": "bar"} %}

{# Includes the primary variant of the Navigation component #}
{% component 'Navigation' 'primary' %}

{# Includes the primary variant of the Navigation component and injects variables #}
{% component 'Navigation' 'primary' with {"active": "home"} %}
```

## Documentation
### Extension
The extension provides terrific extensions to Twig. Currently it adds an additional `Twig_Filesystem_Loader` which
adds Terrific component paths to the environment. It also provides the `ComponentTokenParser`.

### Token Parser
The token parser contains the parsing process for the component tag. Currently it parses the `template`, the optional 
`variant` and optional `variables`.

It's based on the `Twig_TokenParser_Include` as the functionality is similar.

### Node
The Node implements a `compile()` method which, well, compiles the node. 

## Credits
+ [Twig Template Engine](http://twig.sensiolabs.org/)
+ [Terrific](http://terrifically.org/)
+ [Terrific Micro](https://github.com/namics/terrific-micro)
+ [Terrific Micro Twig](https://github.com/namics/terrific-micro-twig)
+ [Splendid](https://github.com/namics/generator-splendid)
