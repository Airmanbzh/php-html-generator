# PHP HTML GENERATOR

Create HTML tags and render them efficiently.

## Overview

    return HtmlTag::createElement();
    // returns an empty HtmlTag Container

    return HtmlTag::createElement('a');
    // returns an HtmlTag containing a 'a' tag

### Why you should use it

 - it always generates valid HTML and XHTML code
 - it makes templates cleaner
 - it's easy to use and fast to execute

## Render tags

    echo(HtmlTag::createElement('a'));

### Simple tags

    echo $html->tag('div')
    // <div></div>

    echo(HtmlTag::createElement('p')->text('some content'));
    // <p>some content</p>

### Structured tags

	echo(HtmlTag::createElement('div')->addElement('a')->text('a text'));
    // <div><a>a text</a></div>

	$container = HtmlTag::createElement('div');
	$container->addElement('p')->text('a text');
	$container->addElement('a')->text('a link');
    // <div><p>a text</p><a>a link</a></div>
	
### Attributes

#### Classics attributes (method : 'set')

    $tag = $html->tag('a')
		->set('href','./sample.php')
		->set('id','myID')
		->text('my link');
	echo( $tag );
    // <a href='./sample.php' id='myID'>my link</a>
	
#### ID (method : 'id')

    $tag = $html->tag('div')
		->id('myID');
	echo( $tag );
    // <div id='myID'>my link</a>

#### Class management (method : 'addClass'/'removeClass')

    $tag = $html->tag('div')
		->addClass('firstClass')
		->addClass('secondClass')
		->text('my content')
		->removeClass('firstClass');
	echo( $tag );
    // <div class="secondClass">my content</div>
	
### More

	Text and content are generated according to the order of addition
	$tag = $html->tag('p')
		->text('a text')
		->addElement('a')
		->text('a link');
	// <p>ma text<a>a link</a></p>
	
	To generate content before text, 2 solutions :
	$tag = $html->tag('p')
		->addElement('a')
		->text('a link')
		->getParent()
		->text('a text');
	or
	$tag = $html->tag('p');
	$tag->addElement('a')->text('a link');
	$tag->text('a text');
	
	// <p><a>a link</a>a text</p>
