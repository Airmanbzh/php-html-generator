<?php
/*
 * @author Airmanbzh
 */
namespace HtmlGenerator;

class Markup
{
    protected static $_instance = null;

    protected $_top = null;
    protected $_parent = null;

    protected $tag = null;
    public $attributeList = null;
    protected $classList = null;

    protected $content = null;
    protected $text = '';

    protected $autoclosed = false;

    protected $autocloseTagsList = array();

    /**
     * Constructor
     * @param mixed $tag
     * @param Markup $top
     * @return Markup instance
     */
    protected function __construct($tag, $top = null)
    {
        $this->tag = $tag;
        $this->_top =& $top;
        $this->attributeList = array();
        $this->classList = array();
        $this->content = array();
        $this->autoclosed = in_array($this->tag, $this->autocloseTagsList);
        $this->text = '';
        return $this;
    }

    /**
     * Create a new Markup
     * @param string $tag
     * @return Markup instance
     */
    public static function createElement($tag = '')
    {
        self::$_instance = new static($tag);
        return self::$_instance;
    }

    /**
     *
     * Add element at an existing Markup
     * @param Markup|string $tag
     * @return Markup instance
     */
    public function addElement($tag)
    {
        $htmlTag = (is_object($tag) && $tag instanceof self) ? $tag : new static($tag);
        $htmlTag->_top = $this->getTop();
        $htmlTag->_parent = &$this;

        $this->content[] = $htmlTag;
        return $htmlTag;
    }

    /**
     * (Re)Define an attribute
     * @param string $name
     * @param string $value
     * @return Markup instance
     */
    public function set($name, $value)
    {
        if (is_null($this->attributeList)) {
            $this->attributeList = array();
        }
        $this->attributeList[$name] = $value;
        return $this;
    }

    /**
     * alias to method "set"
     * @param string $name
     * @param string $value
     * @return Markup instance
     */
    public function attr($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     *
     * Define text content
     * @param string $value
     * @return Markup instance
     */
    public function text($value)
    {
        $this->addElement('')->text = $value;
        return $this;
    }

    /**
     * Returns the top element
     * @return Markup
     */
    public function getTop()
    {
        return $this->_top===null ? $this : $this->_top;
    }

    /**
     *
     * Return parent of current element
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Return first child of parent of current object
     */
    public function getFirst()
    {
        return is_null($this->_parent) ? null : $this->_parent->content[0];
    }

    /**
     * Return last child of parent of current object
     * @return Markup instance
     */
    public function getPrevious()
    {
        $prev = null;
        $find = false;
        if (!is_null($this->_parent)) {
            foreach ($this->_parent->content as $c) {
                if ($c == $this) {
                    $find=true;
                    break;
                }
                if (!$find) {
                    $prev = $c;
                }
            }
        }
        return $prev;
    }

    /**
     * @return Markup last child of parent of current object
     */
    public function getNext()
    {
        $next = null;
        $find = false;
        if (!is_null($this->_parent)) {
            foreach ($this->_parent->content as $c) {
                if ($find) {
                    $next = &$c;
                    break;
                }
                if ($c == $this) {
                    $find = true;
                }
            }
        }
        return $next;
    }

    /**
     * @return Markup last child of parent of current object
     */
    public function getLast()
    {
        return is_null($this->_parent) ? null : $this->_parent->content[count($this->_parent->content) - 1];
    }

    /**
     * @return Markup return parent or null
     */
    public function remove()
    {
        $parent = $this->_parent;
        if (!is_null($parent)) {
            foreach ($parent->content as $key => $value) {
                if ($parent->content[$key] == $this) {
                    unset($parent->content[$key]);
                    return $parent;
                }
            }
        }
        return null;
    }

    /**
     * Generation method
     * @return string
     */
    public function __toString()
    {
        return $this->getTop()->toString();
    }

    /**
     * Generation method
     * @return string
     */
    public function toString()
    {
        $string = '';
        if (!empty($this->tag)) {
            $string .=  '<' . $this->tag;
            $string .= $this->attributesToString();
            if ($this->autoclosed) {
                $string .= '/>' . CHR(13) . CHR(10) . CHR(9);
            } else {
                $string .= '>' . $this->contentToString() . '</' . $this->tag . '>';
            }
        } else {
            $string .= $this->text;
            $string .= $this->contentToString();
        }
        return $string;
    }

    /**
     * return current list of attribute as a string $key="$val" $key2="$val2"
     * @return string
     */
    protected function attributesToString()
    {
        $string = '';
        if (!is_null($this->attributeList)) {
            foreach ($this->attributeList as $key => $value) {
                if (!is_null($value)) {
                    $string .= ' ' . $key . '="' . (is_array($value) ? implode(' ', $value) : $value ) . '"';
                }
            }
        }
        return $string;
    }

    /**
     * return current list of content as a string
     * @return string
     */
    protected function contentToString()
    {
        $string = '';
        if (!is_null($this->content)) {
            foreach ($this->content as $c) {
                $string .= !empty($c->tag) ? CHR(13) . CHR(10) . CHR(9) : '';
                $string .= $c->toString(); 
            }
        }
        return $string;
    }
}
