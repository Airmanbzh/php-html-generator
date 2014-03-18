<?php
/*
 * @author Airmanbzh
 */
require_once ('./Markup.class.php');

class HtmlTag extends Markup
{
    protected $autocloseTagsList = array(
        'img', 'br', 'hr', 'input', 'area', 'link', 'meta', 'param'
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
}
