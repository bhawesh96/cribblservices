<?php
namespace Moto\InputFilter\ClickAction; class Url extends Page { public function init() { parent::init(); $this->remove('id'); $this->remove('anchor'); $this->add(array( 'name' => 'url', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'StringLength', 'options' => array( 'encoding' => 'UTF-8', 'min' => 1, 'max' => 512, ), 'break_chain_on_failure' => true, ) ), )); } }