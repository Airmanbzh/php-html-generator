<?php
/**
 * Add's the tags information more dynamically.
 *
 * PHP version 5.3
 *
 * @category Markup
 * @package  HtmlGenerator
 * @author   Airmanbzh <noemail@noemail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/airmanbzh/php-html-generator
 */
namespace HtmlGenerator;
use ArrayAccess;
if (!defined('ENT_XML1')) {
    define('ENT_XML1', 16);
}
if (!defined('ENT_XHTML')) {
    define('ENT_XHTML', 32);
}
/**
 * Add's the tags information more dynamically.
 *
 * PHP version 5.3
 *
 * @category Markup
 * @package  HtmlGenerator
 * @author   Airmanbzh <noemail@noemail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/airmanbzh/php-html-generator
 */
class Markup implements ArrayAccess
{
    /**
     * Specifies if attribute values and text input
     * should be protected from XSS injection.
     *
     * @var boolean
     */
    public static $avoidXSS = false;
    /**
     * The language convention used for XSS avoiding.
     *
     * @var int
     */
    public static $outputLanguage = ENT_XML1;
    /**
     * The working instance.
     *
     * @var object
     */
    protected static $instance = null;
    /**
     * Top elements.
     *
     * @var mixed
     */
    protected $top = null;
    /**
     * Parent elements.
     *
     * @var mixed
     */
    protected $parent = null;
    /**
     * The tag.
     *
     * @var string
     */
    protected $tag = null;
    /**
     * The attribute list.
     *
     * @var mixed
     */
    public $attributeList = null;
    /**
     * The class list.
     *
     * @var mixed
     */
    protected $classList = null;
    /**
     * The content.
     *
     * @var string
     */
    protected $content = null;
    /**
     * The text.
     *
     * @var string
     */
    protected $text = '';
    /**
     * Auto closed.
     *
     * @var bool
     */
    protected $autoclosed = false;
    /**
     * Void elements list.
     *
     * @var array
     */
    protected $autocloseTagsList = array();
    /**
     * Constructor
     *
     * @param mixed  $tag The Tag to set.
     * @param Markup $top The top to set.
     *
     * @return Markup
     */
    protected function __construct($tag, $top = null)
    {
        $this->tag = $tag;
        $this->top =& $top;
        $this->attributeList = array();
        $this->classList = array();
        $this->content = array();
        $this->autoclosed = in_array($this->tag, $this->autocloseTagsList);
        $this->text = '';
        return $this;
    }

    /**
     * Builds markup from static context.
     *
     * @param string $tag     The tag name.
     * @param array  $content The content of the current tag,
     *                        first argument can be an array containing
     *                        the attributes
     *
     * @return Markup
     */
    public static function __callStatic($tag, $content)
    {
        return self::createElement($tag)
            ->attr(
                (
                    count($content) && is_array($content[0]) ?
                    array_pop($content) :
                    array()
                )
            )->text(
                implode('', $content)
            );
    }
    /**
     * Add a children to the current element
     *
     * @param string $tag     The name of the tag
     * @param array  $content The content of the current tag,
     *                        first argument can be an array
     *                        containing the attributes
     *
     * @return Markup
     */
    public function __call($tag, $content)
    {
        return $this
            ->addElement($tag)
            ->attr(
                (
                    count($content) && is_array($content[0]) ?
                    array_pop($content) :
                    array()
                )
            )->text(
                implode('', $content)
            );
    }
    /**
     * Alias for getParent method.
     *
     * @return Markup
     */
    public function __invoke()
    {
        return $this->getParent();
    }
    /**
     * Create a new Markup
     *
     * @param string $tag The tag to create.
     *
     * @return Markup
     */
    public static function createElement($tag = '')
    {
        self::$instance = new static($tag);
        return self::$instance;
    }

    /**
     * Add element at an existing Markup.
     *
     * @param Markup|string $tag The element to add.
     *
     * @return Markup instance
     */
    public function addElement($tag = '')
    {
        $htmlTag = (
            is_object($tag) && $tag instanceof self ?
            $tag :
            new static($tag)
        );
        $htmlTag->top = $this->getTop();
        $htmlTag->parent = &$this;
        $this->content[] = $htmlTag;
        return $htmlTag;
    }
    /**
     * (Re)Define an attribute or many attributes
     *
     * @param string|array $attribute The attribute to add.
     * @param string       $value     The value of the attribute to add.
     *
     * @return Markup instance
     */
    public function set($attribute, $value = null)
    {
        if (is_array($attribute)) {
            foreach ($attribute as $key => &$value) {
                $this[$key] = $value;
                unset($value);
            }
        } else {
            $this[$attribute] = $value;
        }
        return $this;
    }
    /**
     * Alias to method "set".
     *
     * @param string|array $attribute The attribute to add.
     * @param string       $value     The value of the attribute to add.
     *
     * @return Markup instance
     */
    public function attr($attribute, $value = null)
    {
        return call_user_func_array(
            array($this, 'set'),
            func_get_args()
        );
    }
    /**
     * Checks if an attribute is set for this tag and not null
     *
     * @param string $attribute The attribute to test
     *
     * @return boolean The result of the test
     */
    public function offsetExists($attribute)
    {
        return isset($this->attributeList[$attribute]);
    }
    /**
     * Returns the value the attribute set for this tag.
     *
     * @param string $attribute The attribute to get.
     *
     * @return mixed The stored result in this object.
     */
    public function offsetGet($attribute)
    {
        return (
            $this->offsetExists($attribute) ?
            $this->attributeList[$attribute] :
            null
        );
    }
    /**
     * Sets the value an attribute for this tag
     *
     * @param string $attribute The attribute to set.
     * @param mixed  $value     The value to set.
     *
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
     *
     * @return void
     */
    public function offsetUnset($attribute)
    {
        if ($this->offsetExists($attribute)) {
            unset($this->attributeList[$attribute]);
        }
    }
    /**
     * Define text content.
     *
     * @param string $value The value of the text.
     *
     * @return Markup
     */
    public function text($value)
    {
        $this->addElement('')->text = (
            static::$avoidXSS ?
            static::unXSS($value) :
            $value
        );
        return $this;
    }
    /**
     * Returns the top element.
     *
     * @return Markup
     */
    public function getTop()
    {
        return (
            $this->top === null ?
            $this :
            $this->top
        );
    }
    /**
     * Return parent of current element
     *
     * @return Markup
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Return first child of parent of current object.
     *
     * @return mixed
     */
    public function getFirst()
    {
        return (
            is_null($this->parent) ?
            null :
            $this->parent->content[0]
        );
    }
    /**
     * Return previous element or itself.
     *
     * @return Markup
     */
    public function getPrevious()
    {
        $prev = $this;
        $find = false;
        if (!is_null($this->parent)) {
            foreach ($this->parent->content as $c) {
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
     * Returns Next child of parent of current object.
     *
     * @return Markup
     */
    public function getNext()
    {
        $next = null;
        $find = false;
        if (!is_null($this->parent)) {
            foreach ($this->parent->content as $c) {
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
     * Returns Last child of parent of current object.
     *
     * @return Markup
     */
    public function getLast()
    {
        return (
            is_null($this->parent) ?
            null :
            $this->parent->content[count($this->parent->content) - 1]
        );
    }
    /**
     * Removes parent.
     *
     * @return Markup
     */
    public function remove()
    {
        $parent = $this->parent;
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
     * Generation method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTop()->toString();
    }
    /**
     * Generation method
     *
     * @return string
     */
    public function toString()
    {
        $string = '';
        if (!empty($this->tag)) {
            $string .=  '<' . $this->tag;
            $string .= $this->attributesToString();
            if ($this->autoclosed) {
                $string .= '/>';
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
     * Returns current list of attributes as string.
     *
     * @return string
     */
    protected function attributesToString()
    {
        $string = '';
        $XMLConvention = in_array(
            static::$outputLanguage,
            array(ENT_XML1, ENT_XHTML)
        );
        if (!empty($this->attributeList)) {
            foreach ($this->attributeList as $key => &$value) {
                if ($value !== null
                    && ($value!==false
                    || $XMLConvention)
                ) {
                    $string .= sprintf(
                        ' %s',
                        $key
                    );
                    if ($value === true) {
                        if ($XMLConvention) {
                            $value = $key;
                        } else {
                            continue;
                        }
                    }
                    $string .= sprintf(
                        '="%s"',
                        implode(
                            ' ',
                            array_map(
                                (
                                    static::$avoidXSS ?
                                    'static:unXSS' :
                                    'strval'
                                ),
                                (
                                    is_array($value) ?
                                    $value :
                                    array($value)
                                )
                            )
                        )
                    );
                }
            }
        }
        return $string;
    }

    /**
     * Return current list of content as a string.
     *
     * @return string
     */
    protected function contentToString()
    {
        $string = '';
        if (!is_null($this->content)) {
            foreach ($this->content as $c) {
                $string .= $c->toString();
            }
        }

        return $string;
    }
    /**
     * Protects value from XSS injection by replacing some
     * characters by XML / HTML entities.
     *
     * @param string $input The unprotected value
     *
     * @return string
     */
    public static function unXSS($input)
    {
        $return = '';
        if (version_compare(phpversion(), '5.4', '<')) {
            $return = htmlspecialchars($input);
        } else {
            $return = htmlentities(
                $input,
                ENT_QUOTES | ENT_DISALLOWED | static::$outputLanguage
            );
        }
        return $return;
    }
}
