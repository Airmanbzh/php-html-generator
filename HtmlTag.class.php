<?php
/*
 * @author brice_leboulch
 */
class HtmlTag{

	private static $_instance = null;

	private $_top = null;
	private $_parent = null;

	private $tag = null;
	public $attributeList = null;
	private $classList = null;

	private $content = null;
	private $text = '';

	private $autoclosed = false;

	private $autocloseTagsList = array(
		'img','br','hr','input','area','link','meta','param'
	);

	/**
	 * 
	 * Constructor
	 * @param mixed $tag
	 * @param HtmlTag $top
	 * @return htmlTagInstance
	 */
	private function __construct($tag, $top = null){
		$this->tag = $tag;
		$this->_top =& $top;
		$this->attributeList = array();
		$this->classList = array();
		$this->content = array();
		$this->text = '';
		return $this;
	}
	/**
	 * 
	 * Create a new HtmlTag
	 * @param string $tag
	 * @return htmlTagInstance
	 */

	public static function createElement($tag = ''){
		self::$_instance = new HtmlTag($tag);
		return self::$_instance;
	}
	/**
	 * 
	 * Add element at an existing HtmlTag 
	 * @param HtmlTag $tag
	 * @return htmlTagInstance
	 */

	public function addElement($tag){
		$htmlTag = null;
		$this->autoclosed = in_array($this->tag,$this->autocloseTagsList);
		if(is_object($tag) && get_class($tag) == get_class($this)){
			$htmlTag = $tag;
			$htmlTag->_top = $this->_top;
			$this->content[] = $htmlTag;
		}
		else{
			$htmlTag = new HtmlTag($tag, (is_null($this->_top) ? $this : $this->_top ));
			$this->content[] = $htmlTag;
		}
		$htmlTag->_parent = &$this;
		return $htmlTag;
	}
	/**
	 * 
	 * (Re)Define an attribute
	 * @param string $name
	 * @param string $value
	 */

	public function set($name,$value){
		if(is_null($this->attributeList)) $this->attributeList = array();
		$this->attributeList[$name] = $value;
		return $this;
	}
	/**
	 * 
	 * Shortcut to set('id',$value)
	 * @param string $value
	 */

	public function id($value){
		return $this->set('id',$value);
	}
	/**
	 * 
	 * Add a class to classList
	 * @param string $value
	 */

	public function addClass($value){
		if(is_null($this->classList))
			$this->classList = array();
		$this->classList[] = $value;
		return $this;
	}
	/**
	 * 
	 * Remove a class from classList
	 * @param string $value
	 */

	public function removeClass($value){
		if(!is_null($this->classList)){
			unset($this->classList[array_search($value, $this->classList)]);
		}
		return $this;
	}
	/**
	 * 
	 * Define text content
	 * @param string $value
	 */

	public function text($value){
		$find = false;
		foreach($this->content as $k=>$v){
			if($this->content[$k]->tag == ''){
				$this->content[$k]->text = $value;
				$find=true;
			}
		}
		if(!$find) $this->addElement('')->text = $value;
		return $this;
	}
	/**
	 * (Deprecated)
	 * Define text is generated before content 
	 * @param boolean $bool
	 */

	public function showTextBeforeContent($bool){
		$this->textFirst = $bool;
	}
	/**
	 * 




	 * Return parent of current element
	 */
	public function getParent(){
		return $this->_parent;
	}
	/**
	 * Return first child of parent of current object
	 */ 
	public function getFirst(){
		return is_null($this->_parent) ? null : $this->_parent->content[0];
	}
	/**
	 * Return last child of parent of current object
	 */
	public function getPrevious(){
		$prev = null;
		$find = false;
		if(!is_null($this->_parent)){
			foreach($this->_parent->content as $c){
				if( $c == $this ){
					$find=true;
					break;
				}
				if( !$find ) {
					$prev = $c;
				}
			}
		}
		return $prev;
	}
	/**
	 * 
	 * Return last child of parent of current object
	 */
	public function getNext(){
		$next = null;
		$find = false;
		if(!is_null($this->_parent)){
			foreach($this->_parent->content as $c){
				if($find){
					$next = &$c;
					break;
				}
				if( $c == $this ) $find = true;

			}
		}
		return $next;
	}
	/**
	 * 
	 * Return last child of parent of current object
	 */
	public function getLast(){
		return is_null($this->_parent) ? null : $this->_parent->content[count($this->_parent->content) - 1];
	}
	/**
	 * 
	 * Delete current child from parent
	 */
	public function remove(){
		$parent = $this->_parent;
		if(!is_null($parent)){
			foreach($parent->content as $key=>$value){
				if( $parent->content[$key] == $this ){
					unset($parent->content[$key]);
					return $parent;
				}

			}
		}
		return null;
	}

	/**
	 * 
	 * Generation method
	 */


	public function __toString(){
		return (is_null($this->_top) ? $this->toString() : $this->_top->toString() );
	}

	public function toString(){
		$string = '';
		if(!empty($this->tag)){
			$string .=  '<' . $this->tag;
			$string .= $this->attributesToString();
			if($this->autoclosed) $string .= '/>' . CHR(13) . CHR(10) . CHR(9);
			else $string .= '>' . $this->contentToString() . '</' . $this->tag . '>';
		}
		else{
			$string .= $this->text;
			$string .= $this->contentToString();
		}		
		return $string;
	}

	private function attributesToString(){
		$string = '';
		if(!is_null($this->attributeList)){
			foreach($this->attributeList as $key => $value){
				if(!is_null($value))
					$string .= ' ' . $key . '="' . $value . '"';
			}
		}
		if(!is_null($this->classList) && count($this->classList) > 0 ){
			$string .= ' class="' . implode(' ',$this->classList) . '"';
		}
		return $string;
	}

	private function contentToString(){
		$string = '';
		if(!is_null($this->content)){
			foreach($this->content as $c){
				$string .= CHR(13) . CHR(10) . CHR(9) . $c->toString();
			}
		}
		return $string;
	}
}
?>