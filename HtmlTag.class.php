<?php
class HtmlTag{
	
	private static $_instance = null;
	
	private $_top = null;
	
	private $tag = null;
	private $attributes = null;
	private $text = '';
	
	private $content = null;
	
	private $autoclosed = true;
	private $textFirst = false;
	
	private function __construct($balise, $top = null){
		$this->tag = $balise;
		$this->_top =& $top;
		return $this;
	}
	
	public static function createElement($balise = ''){
		if(is_null(self::$_instance)) {
			self::$_instance = new HtmlTag($balise);  
		}
		return self::$_instance;
	}
	
	public function addElement($balise){
		if(is_null($this->content)){
			$this->content = array();
			$this->autoclosed = false;
		}
		$htmlTag = new HtmlTag($balise, (is_null($this->_top) ? $this : $this->_top ));
		$this->content[] = $htmlTag;
		return $htmlTag;
	}
	
	public function set($name,$value){
		if(is_null($this->attributes)) $this->attributes = array();
		$this->attributes[$name] = $value;
		return $this;
	}
	
	public function id($value){
		return $this->attr('id',$value);
	}
	
	public function addClass($value){
		if(!is_null($this->attributes) && !array_key_exists('class', $this->attributes))
			return $this->set('class',$value);
		else
			return $this->set('class',$this->attributes['class'] . ' ' . $value);
	}

	public function removeClass($value){
		if(!is_null($this->attributes) && array_key_exists('class', $this->attributes))
			return $this->set('class',str_replace( array($value),'  ', '', $this->attributes['class']));
	}
	
	public function setText($value){
		$this->text = $value;
		return $this;
	}
	
	public function showTextBeforeContent($bool){
		$this->textFirst = $bool;
	}
	
	public function __toString(){
		return (is_null($this->_top) ? $this->toString() : $this->_top->toString() );
	}
	
	public function toString(){
		$string = '';
		if(!empty($this->tag)){
			$string .=  '<' . $this->tag;
			$string .= $this->attributesToString();
			if($this->autoclosed && empty($this->text)) $string .= '/>' . CHR(13) . CHR(10) . CHR(9);
			else $string .= '>' . ($this->textFirst ?  $this->text.$this->contentToString() : $this->contentToString().$this->text ). '</' . $this->tag . '>';
		}
		else{
			$string .= $this->contentToString();
		}		
		return $string;
	}
	
	private function attributesToString(){
		$string = '';
		if(!is_null($this->attributes)){
			foreach($this->attributes as $key => $value){
				if(!empty($value))
					$string .= ' ' . $key . '="' . $value . '"';
			}
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