<?php
namespace Website\Widgets\Disqus\InputFilter; use Moto; class SetOptions extends Moto\InputFilter\AbstractInputFilter { public function init() { $this->add(array( 'name' => 'shortname', 'required' => false, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'Regex', 'break_chain_on_failure' => true, 'options' => array( 'pattern' => '/^([a-z0-9][a-z0-9\-]{1,62}[a-z0-9])$/i', ) ), ), )); } }