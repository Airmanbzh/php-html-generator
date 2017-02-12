<?php
class MarkupTest extends PHPUnit_Framework_TestCase
{
    //    public function setUp()
    //    {
    //    }
    //
    //    public function tearDown()
    //    {
    //    }
    public function testCreation()
    {
        $this->assertEquals(get_class(HtmlGenerator\Markup::createElement()), "HtmlGenerator\Markup");
    }

    public function testToString()
    {
        $div = HtmlGenerator\Markup::createElement('div');
        $this->assertEquals($div, '<div></div>');
    }

    public function testAddElement()
    {
        $div = HtmlGenerator\Markup::createElement('div');
        $div->addElement('p');
        $this->assertEquals($div, '<div><p></p></div>');
    }

    public function testAttr()
    {
        $div = HtmlGenerator\Markup::createElement('div');
        $div->attr('id', 'testId');
        $this->assertEquals($div, '<div id="testId"></div>');
    }

    public function testSet()
    {
        $div = HtmlGenerator\Markup::createElement('div');
        $div->set('id', 'testId');
        $this->assertEquals($div, '<div id="testId"></div>');
    }

    public function testSetWithArray()
    {
        $div = HtmlGenerator\Markup::createElement('div');
        $div->set(array('id' => 'testId', 'class' => 'test'));
        $this->assertEquals($div, '<div id="testId" class="test"></div>');
    }

    public function testText()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->text('text');

        $this->assertEquals($div, '<div><p>text</p></div>');
    }

    public function testGetParent()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->addElement('a')
            ->getParent()
            ->addElement('a');

        $this->assertEquals($div, '<div><p><a></a><a></a></p></div>');
    }

    public function testGetFirst()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getFirst()
            ->text('test');

        $this->assertEquals($div, '<div><p><a>test</a><a></a></p></div>');
    }

    public function testGetLast()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getFirst()
            ->getLast()
            ->text('test');

        $this->assertEquals($div, '<div><p><a></a><a></a><a>test</a></p></div>');
    }

    public function testGetPrevious()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getPrevious()
            ->text('test');

        $this->assertEquals($div, '<div><p><a></a><a>test</a><a></a></p></div>');
    }

    public function testGetNext()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getParent()
            ->addElement('a')
            ->getFirst()
            ->getNext()
            ->text('test');

        $this->assertEquals($div, '<div><p><a></a><a>test</a><a></a></p></div>');
    }

    public function testGetTop()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->addElement('p')
            ->addElement('a')
            ->getTop()
            ->text('test');

        $this->assertEquals($div, '<div><p><a></a></p>test</div>');
    }

    public function testRemoveXSS()
    {
        $div = HtmlGenerator\Markup::createElement('div')
            ->text('test');

        $this->assertEquals(HtmlGenerator\Markup::unXSS($div), '&lt;div&gt;test&lt;/div&gt;');
    }

    public function testMagicStatic()
    {

        $div = HtmlGenerator\Markup::div()
            ->text('test');

        $this->assertEquals($div, '<div>test</div>');
    }

    public function testMagic()
    {

        $div = HtmlGenerator\Markup::div()
            ->b()
            ->text('test');

        $this->assertEquals($div, '<div><b>test</b></div>');
    }

    public function testMagicWithAttributes()
    {

        $div = HtmlGenerator\Markup::div()
            ->b(array('id' => 'testId', 'tag' => 'tagTest'))
            ->text('test');

        $this->assertEquals($div, '<div><b id="testId" tag="tagTest">test</b></div>');
    }
}
