<?php
namespace Moto\Application\Fonts\InputFilter; use Moto; use Moto\InputFilter\AbstractInputFilter; class UpdateFont extends AbstractInputFilter { protected $_name = 'fonts.save'; protected $_currentModel; public function init() { $this->add(array( 'name' => 'id', 'required' => true, 'break_on_failure' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'Digits', 'break_chain_on_failure' => true, ), array( 'name' => 'Db\RecordExists', 'options' => array( 'table' => Moto\Config::get('database.prefix') . 'fonts', 'field' => 'id', 'adapter' => Moto\System::getDatabaseAdapter(), ) ), ), )); $this->add(array( 'name' => 'active_variants', 'type' => 'Zend\InputFilter\ArrayInput', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'InArray', 'options' => array( 'haystack' => array('100', '100italic', '200', '200italic', '300', '300italic', '400', '400italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', 'bold', '700italic', '800', '800italic', '900', '900italic',), ) ), array( 'name' => 'Callback', 'options' => array( 'callback' => array($this, 'InArrayOnModelProperty'), 'callbackOptions' => array( 'variants', ), ) ), ), )); $this->add(array( 'name' => 'active_subsets', 'type' => 'Zend\InputFilter\ArrayInput', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), ), 'validators' => array( array( 'name' => 'Regex', 'options' => array( 'pattern' => '/^[a-z]{1,}[a-z0-9\_\-]*$/', ) ), array( 'name' => 'Callback', 'options' => array( 'callback' => array($this, 'InArrayOnModelProperty'), 'callbackOptions' => array( 'subsets', ), ) ), ), )); } public function InArrayOnModelProperty($value, $context, $property) { $model = $this->_getModelInstanceByContext($context); if (!$model) { return false; } if (empty($property) || !property_exists($model, $property)) { return false; } $variants = $model->{$property}; return (is_array($variants) && in_array($value, $variants)); } protected function _getModelInstanceByContext($context) { if ($this->_currentModel) { return $this->_currentModel; } $id = Moto\Util::getValue($context, 'id', 0) * 1; if ($id < 1) { return null; } $table = Moto\System::getDbTable('fonts'); $table->useResultAsModel(true); $model = $table->getById($context['id']); return $model; } } 