# PHP HTML GENERATOR

Create HTML tags and render them efficiently.

Build status:
* Master [![Build Status](https://travis-ci.org/Airmanbzh/php-html-generator.svg?branch=master)](https://travis-ci.org/Airmanbzh/php-html-generator)
* Dev [![Build Status](https://travis-ci.org/Airmanbzh/php-html-generator.svg?branch=dev)](https://travis-ci.org/Airmanbzh/php-html-generator)

## Overview

```php
return HtmlTag::createElement();
// returns an empty HtmlTag Container
```
```php
return HtmlTag::createElement('a');
// returns an HtmlTag containing a 'a' tag
```

### Why you should use it

 - it always generates valid HTML and XHTML code
 - it makes templates cleaner
 - it's easy to use and fast to execute

## Render tags

```php
echo(HtmlTag::createElement('a'));
```
or 
```php
$tag = HtmlTag::createElement('a')
echo( $tag );
```

### Simple tags


```php
echo HtmlTag::createElement('div');
```
```html
<div></div>
```

```php
echo(HtmlTag::createElement('p')->text('some content'));
```
```html
<p>some content</p>
```

### Structured tags

```php
echo(HtmlTag::createElement('div')->addElement('a')->text('a text'));
```
```html
<div><a>a text</a></div>
```

```php
$container = HtmlTag::createElement('div');
$container->addElement('p')->text('a text');
$container->addElement('a')->text('a link');
```
```html
<div><p>a text</p><a>a link</a></div>
```
	
### Attributes

#### Classics attributes (method : 'set')

```php
$tag = HtmlTag::createElement('a')
    ->set('href','./sample.php')
    ->set('id','myID')
    ->text('my link');
echo( $tag );
```
```html
<a href='./sample.php' id='myID'>my link</a>
```
	
#### Shortcut to set an ID attribute (method : 'id')

```php
$tag = HtmlTag::createElement('div')
    ->id('myID');
echo( $tag );
```
```html
<div id='myID'>my link</div>
```

#### Class management (method : 'addClass'/'removeClass')

```php
$tag = HtmlTag::createElement('div')
    ->addClass('oneClass')
    ->text('my content')
echo( $tag );
```
```html
<div class="oneClass">my content</div>
```

```php
$tag = HtmlTag::createElement('div')
    ->addClass('aClass')
    ->addClass('anothereClass')
    ->text('my content')
echo( $tag );
```
```html
<div class="aClass anothereClass">my content</div>
```

```php
$tag = HtmlTag::createElement('div')
    ->addClass('firstClass')
    ->addClass('secondClass')
    ->text('my content')
    ->removeClass('firstClass');
echo( $tag );
```
```html
<div class="secondClass">my content</div>
```
	
### More

Text and content are generated according to the order of addition
```php
$tag = HtmlTag::createElement('p')
    ->text('a text')
    ->addElement('a')
    ->text('a link');
```
```html
<p>ma text<a>a link</a></p>
```
	
To generate content before text, 2 solutions :
```php
$tag = HtmlTag::createElement('p')
    ->addElement('a')
    ->text('a link')
    ->getParent()
    ->text('a text');
```
or
```php
$tag = HtmlTag::createElement('p');
$tag->addElement('a')->text('a link');
$tag->text('a text');
```

```html
<p><a>a link</a>a text</p>
```
