<?php
/*
 * @author Airmanbzh
 */
namespace HtmlGenerator;

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

    /**
     * Returns current list of attributes as a string $key="$val" $key2="$val2"
     * Overrides the parent function to allow boolean value
     * @return string
     */
    protected function attributesToString()
    {
        $string = '';
        if (!is_null($this->attributeList)) {
            foreach ($this->attributeList as $key => $value) {
                if ($value!==false) {
                    $string.= ' ' . $key;
                    if ($value!==true) {
                        $string.= '="' . (is_array($value) ? implode(' ', $value) : $value ) . '"';
                    }
                }
            }
        }
        return $string;
    }
}
