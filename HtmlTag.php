<?php
/**
 * Add's the tags information more dynamically.
 *
 * PHP version 5.3
 *
 * @category HtmlTag
 * @package  HtmlGenerator
 * @author   Airmanbzh <noemail@noemail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/airmanbzh/php-html-generator
 */
namespace HtmlGenerator;
/**
 * Defines our HTML5 element integer.
 */
if (!defined('ENT_HTML5')) {
    define('ENT_HTML5', 48);
}
/**
 * Add's the tags information more dynamically.
 *
 * PHP version 5.3
 *
 * @category HtmlTag
 * @package  HtmlGenerator
 * @author   Airmanbzh <noemail@noemail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/airmanbzh/php-html-generator
 */
class HtmlTag extends Markup
{
    /**
     * The language convention used for XSS avoiding.
     *
     * @var int
     */
    public static $outputLanguage = ENT_HTML5;
    /**
     * The auto closed tags list (or void elements.)
     *
     * @var array
     */
    protected $autocloseTagsList = array(
        'area',
        'base',
        'basefont',
        'bgsound',
        'br',
        'col',
        'command',
        'embed',
        'frame',
        'hr',
        'image',
        'img',
        'input',
        'isindex',
        'keygen',
        'link',
        'menuitem',
        'meta',
        'nextid',
        'param',
        'source',
        'track',
        'wbr'
    );
    /**
     * Shortcut to set('id', $value)
     *
     * @param string $value The value to set.
     *
     * @return HtmlTag instance
     */
    public function id($value)
    {
        return $this->set('id', $value);
    }
    /**
     * Add a class to classList
     *
     * @param string|array $value The value to set.
     *
     * @return HtmlTag instance
     */
    public function addClass($value)
    {
        /**
         * If the attribute is not set or is null,
         * initialize into an array.
         */
        if (!isset($this->attributeList['class'])
            || is_null($this->attributeList['class'])
        ) {
            $this->attributeList['class'] = [];
        }
        /**
         * Classes are separated by spaces.
         * Attempt exploding the values on just a space.
         */
        if (!is_array($value)) {
            if (false !== strpos($value, ' ')) {
                $value = explode(' ', $value);
            }
        }
        /**
         * If value is an array of values perform actions.
         */
        if (is_array($value)) {
            /**
             * Trim all values of the array.
             */
            $value = array_map(
                'trim',
                $value
            );
            /**
             * Filter our values.
             */
            $value = array_filter($value);
            /**
             * If count of values is 0 return.
             */
            if (0 === count($value)) {
                return $this;
            }
            /**
             * Ensure all values are unique.
             */
            $value = array_unique($value);
            /**
             * Join the values into our class list.
             */
            $this->attributeList['class'] += $value;
            /**
             * Unique the attribute list.
             */
            $this->attributeList['class'] = array_unique(
                $this->attributeList['class']
            );
            /**
             * Sort nicely.
             */
            natsort($this->attributeList['class']);
            /**
             * Order nicely.
             */
            $this->attributeList['class'] = array_values(
                $this->attributeList['class']
            );
            /**
             * Return.
             */
            return $this;
        }
        /**
         * Trim the value.
         */
        $value = trim($value);
        /**
         * If the value is empty, return immediately.
         */
        if (empty($value)) {
            return $this;
        }
        /**
         * Add the value to the class list.
         */
        $this->attributeList['class'][] = $value;
        /**
         * Make sure all values are unique.
         */
        $this->attributeList['class'] = array_unique(
            $this->attributeList['class']
        );
        /**
         * Sort nicely.
         */
        natsort($this->attributeList['class']);
        /**
         * Order nicely.
         */
        $this->attributeList['class'] = array_values(
            $this->attributeList['class']
        );
        /**
         * Return.
         */
        return $this;
    }
    /**
     * Remove a class from classList
     *
     * @param string|array $value The value to remove.
     *
     * @return HtmlTag instance
     */
    public function removeClass($value)
    {
        /**
         * If the class list is not set or not an array
         * return immediately.
         */
        if (!(isset($this->attributeList['class'])
            && is_array($this->attributeList['class']))
        ) {
            return $this;
        }
        /**
         * If our attributeList has no items in it, we 
         * don't need to perform any action. Return
         * immediately.
         */
        if (1 > count($this->attributeList['class'])) {
            return $this;
        }
        /**
         * Classes are separated by spaces.
         * Attempt exploding the values on just a space.
         */
        if (!is_array($value)) {
            if (false !== strpos($value, ' ')) {
                $value = explode(' ', $value);
            }
        }
        /**
         * If value is an array process it as such.
         */
        if (is_array($value)) {
            /**
             * Trim all the entries.
             */
            $value = array_map(
                'trim',
                $value
            );
            /**
             * Filter our results to remove null/blanks.
             */
            $value = array_filter($value);
            /**
             * If there is no data return immediately.
             */
            if (1 > count($value)) {
                return $this;
            }
            /**
             * Ensure the values are all unique.
             */
            $value = array_unique($value);
            /**
             * This just gets the difference between
             * the current list and what we want to remove.
             */
            $diff = array_diff(
                $this->attributeList['class'],
                $value
            );
            /**
             * If there is no data return immediately.
             */
            if (1 > count($diff)) {
                return $this;
            }
            /**
             * Ensure our diff values are unique.
             */
            $diff = array_unique($diff);
            /**
             * Sort nicely.
             */
            natsort($diff);
            /**
             * Order nicely.
             */
            $diff = array_values($diff);
            /**
             * Set up our new list.
             */
            $this->attributeList['class'] = $diff;
            /**
             * Return.
             */
            return $this;
        }
        /**
         * Trim the entry.
         */
        $value = trim($value);
        /**
         * If there is no data return immediately.
         */
        if (empty($value)) {
            return $this;
        }
        /**
         * Attempt to find the index that the value resides under.
         */
        $index = array_search(
            $value,
            $this->attributeList['class']
        );
        /**
         * If the index isn't found, return immediately.
         */
        if (false === $index) {
            return $this;
        }
        /**
         * Unset the element.
         */
        unset($this->attributeList['class'][$index]);
        /**
         * Ensure all items are unique.
         */
        $this->attributeList['class'] = array_unique(
            $this->attributeList['class']
        );
        /**
         * Sort nicely.
         */
        natsort($this->attributeList['class']);
        /**
         * Reorder nicely.
         */
        $this->attributeList['class'] = array_values(
            $this->attributeList['class']
        );
        /**
         * Return.
         */
        return $this;
    }
}
