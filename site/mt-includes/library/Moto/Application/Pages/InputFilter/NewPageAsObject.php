<?php
namespace Moto\Application\Pages\InputFilter; use Moto; class NewPageAsObject extends NewPage { protected $_name = 'pages.new:object'; public function init() { parent::init(); $this->remove('content'); $this->add(array( 'name' => 'content_data', 'required' => false, 'allow_empty' => true, 'filters' => array( array('name' => 'Moto\Filter\BodyContentObjects'), ), 'validators' => array( array( 'name' => 'Moto\Validator\BodyContentObjects', ) ), )); } } 