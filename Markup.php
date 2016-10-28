<?php
/*
 * @author Airmanbzh
 */
namespace HtmlGenerator;

use ArrayAccess;

class Markup implements ArrayAccess
{
    /** @var boolean Specifies if attribute values and text input sould be protected from XSS injection */
    public static $avoidXSS = false;

    /** @var int The language convention used for XSS avoiding */
    public static $outputLanguage = ENT_XML1;

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
     * Builds markup from static context
     * @param string $tag The tag name
     * @param array  $content The content of the current tag, first argument can be an array containing the attributes
     * @return Markup
     */
    public static function __callStatic($tag, $content)
    {
        return self::createElement($tag)
            ->attr(count($content) && is_array($content[0]) ? array_pop($content) : array())
            ->text(implode('', $content));
    }

    /**
     * Add a children to the current element
     * @param string $tag The name of the tag
     * @param array  $content The content of the current tag, first argument can be an array containing the attributes
     * @return Markup instance
     */
    public function __call($tag, $content)
    {
        return $this
            ->addElement($tag)
            ->attr(count($content) && is_array($content[0]) ? array_pop($content) : array())
            ->text(implode('', $content));
    }

    /**
     * Alias for getParent()
     * @return Markup
     */
    public function __invoke()
    {
        return $this->getParent();
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
    public function addElement($tag = '')
    {
        $htmlTag = (is_object($tag) && $tag instanceof self) ? $tag : new static($tag);
        $htmlTag->_top = $this->getTop();
        $htmlTag->_parent = &$this;

        $this->content[] = $htmlTag;
        return $htmlTag;
    }

    /**
     * (Re)Define an attribute or many attributes
     * @param string|array $attribute
     * @param string $value
     * @return Markup instance
     */
    public function set($attribute, $value = null)
    {
        if(is_array($attribute)) {
            foreach ($attribute as $key => $value) {
                $this[$key] = $value;
            }
        } else {
            $this[$attribute] = $value;
        }
        return $this;
    }

    /**
     * alias to method "set"
     * @param string|array $attribute
     * @param string $value
     * @return Markup instance
     */
    public function attr($attribute, $value = null)
    {
        return call_user_func_array([$this, 'set'], func_get_args());
    }

    /**
     * Checks if an attribute is set for this tag and not null
     *
     * @param string $attribute The attribute to test
     * @return boolean The result of the test
     */
    public function offsetExists($attribute)
    {
        return isset($this->attributeList[$attribute]);
    }

    /**
     * Returns the value the attribute set for this tag
     *
     * @param string $attribute The attribute to get
     * @return mixed The stored result in this object
     */
    public function offsetGet($attribute)
    {
        return $this->offsetExists($attribute) ? $this->attributeList[$attribute] : null;
    }

    /**
     * Sets the value an attribute for this tag
     *
     * @param string $attribute The attribute to set
     * @param mixed $value The value to set
     * @return void
     */
    public function offsetSet($attribute, $value)
    {
        $this->attributeList[$attribute] = $value;
    }

    /**
     * Removes an attribute
     *
     * @param mixed $attribute The attribute to unset
     * @return void
     */
    public function offsetUnset($attribute)
    {
        if ($this->offsetExists($attribute))
            unset($this->attributeList[$attribute]);
    }

    /**
     *
     * Define text content
     * @param string $value
     * @return Markup instance
     */
    public function text($value)
    {
        $this->addElement('')->text = static::$avoidXSS ? static::unXSS($value) : $value;
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
     * Return previous element or itself
	 * 
     * @return Markup instance
     */
    public function getPrevious()
    {
        $prev = $this;
        $find = false;
        if (!is_null($this->_parent)) {
            foreach ($this->_parent->content as $c) {
                if ($c === $this) {
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
        $XMLConvention = in_array(static::$outputLanguage, [ENT_XML1, ENT_XHTML]);
        if (!empty($this->attributeList)) {
            foreach ($this->attributeList as $key => $value) {
                if ($value!==null && ($value!==false || $XMLConvention)) {
                    $string.= ' ' . $key;
                    if($value===true) {
                        if ($XMLConvention) {
                            $value = $key;
                        } else {
                            continue;
                        }
                    }
                    $string.= '="' . implode(
                        ' ',
                        array_map(
                            static::$avoidXSS ? 'static::unXSS' : 'strval',
                            is_array($value) ? $value : [$value]
                        )
                    ) . '"';
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
//                $string .= !empty($c->tag) ? CHR(13) . CHR(10) . CHR(9) : '';
                $string .= $c->toString();
            }
        }

        return $string;
    }

    /**
     * Protects value from XSS injection by replacing some characters by XML / HTML entities
     * @param string $input The unprotected value
     * @return string A safe string
     */
    public static function unXSS($input)
    {
        return htmlentities($input, ENT_QUOTES | ENT_DISALLOWED | static::$outputLanguage);
    }
}
