<?php
class HtmlTagTest extends PHPUnit_Framework_TestCase
{
    public function testId()
    {
	    $div = HtmlGenerator\HtmlTag::createElement('div');
	    $div->id('test');

        $this->assertEquals($div, '<div id="test"></div>');
    }

    public function testAddClass()
    {
	    $div = HtmlGenerator\HtmlTag::createElement('div');
	    $div->addClass('test');

	    $this->assertEquals($div, '<div class="test"></div>');
    }

    public function testRemoveClass()
    {
	    $div = HtmlGenerator\HtmlTag::createElement('div');
	    $div->addClass('test');
	    $div->addClass('test2');
	    $div->removeClass('test');

	    $this->assertEquals($div, '<div class="test2"></div>');
    }
}