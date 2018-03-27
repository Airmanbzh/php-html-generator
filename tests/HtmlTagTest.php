<?php
class HtmlTagTest extends \PHPUnit\Framework\TestCase
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

	public function testAutoClose()
	{
		$br = HtmlGenerator\HtmlTag::createElement('br');

		$this->assertEquals($br, '<br/>');
	}

    public function testInstanceReturnWithCreateElement()
    {
        $div = HtmlGenerator\HtmlTag::createElement('div');

        $this->assertTrue($div instanceof HtmlGenerator\HtmlTag);
        $this->assertTrue(method_exists($div, 'addClass'));
    }
}