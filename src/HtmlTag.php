<?php
/*
 * @author Airmanbzh
 */
namespace HtmlGenerator;

if (!defined('ENT_HTML5')) {
    define('ENT_HTML5', 48);
}

class HtmlTag extends Markup
{
    /**
     * @var int The language convention used for XSS avoiding
     */
    public static $outputLanguage = ENT_HTML5;

    protected $autocloseTagsList = array(
        'img', 'br', 'hr', 'input', 'area', 'link', 'meta', 'param', 'base', 'col', 'command', 'keygen', 'source'
    );

    /**
     * Shortcut to set('id', $value)
     * @param string $value
     * @return HtmlTag instance
     */
    public function id($value)
    {
        return $this->set('id', $value);
    }

    /**
     * Add a class to classList
     * @param string $value
     * @return HtmlTag instance
     */
    public function addClass($value)
    {
        if (!isset($this->attributeList['class']) || is_null($this->attributeList['class'])) {
            $this->attributeList['class'] = array();
        }
        $this->attributeList['class'][] = $value;
        return $this;
    }

    /**
     * Remove a class from classList
     * @param string $value
     * @return HtmlTag instance
     */
    public function removeClass($value)
    {
        if (!is_null($this->attributeList['class'])) {
            unset($this->attributeList['class'][array_search($value, $this->attributeList['class'])]);
        }
        return $this;
    }

    /**
     * Add custom params from array
     * @param array $param
     * @return HtmlTag instance
     */
    public function addCustomParams($param)
    {
        if(is_array($param)) {
            foreach ($param as $key => $p) {
                if (!isset($this->attributeList[$key]) || is_null($this->attributeList[$key])) {
                    $this->attributeList[$key] = array();
                }
                $this->attributeList[$key][] = $p;
            }
            return $this;
        } else {
            throw new Exception("ERROR: The param should be an array");
        }
    }
}
