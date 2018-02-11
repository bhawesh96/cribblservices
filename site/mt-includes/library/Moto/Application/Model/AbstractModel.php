<?php
namespace Moto\Application\Model; use Moto; use Zend\InputFilter\Factory as InputFactory; use Zend\InputFilter\InputFilter; use Zend\InputFilter\InputFilterAwareInterface; use Zend\InputFilter\InputFilterInterface; class AbstractModel { protected $_fields = array(); protected $_isValid = null; protected $_validationMessages = null; protected $_skipDefaults = false; protected $_cleanData = array(); protected $_modifiedFields = array(); public function __construct() { $this->initFields(); } public function initFields() { if (empty($this->_fields)) return; reset($this->_fields); foreach ($this->_fields as $field => $options) { if (!isset($this->{$field})) { if (!isset($options['virtual']) || $options['virtual']) { $this->{$field} = (isset($options['default']) ? $options['default'] : null); } if (!empty($options['type'])) { $this->{$field} = Moto\Util::decodeValue($this->{$field}, $options['type']); } } } $this->_modifiedFields = array(); } public function exchangeArray($data) { reset($this->_fields); if (is_array($data)) { foreach ($this->_fields as $field => $options) { if (array_key_exists($field, $data)) { unset($this->_modifiedFields[$field]); if (is_object($this->{$field}) && method_exists($this->{$field}, 'exchangeArray')) { $this->{$field}->exchangeArray($data[$field]); $this->_cleanData[$field] = $this->{$field}->getCleanData(); } else { if (!empty($options['type'])) { $data[$field] = Moto\Util::decodeValue($data[$field], $options['type']); } $this->{$field} = $data[$field]; $this->_cleanData[$field] = $data[$field]; } } } } $this->_postExchangeArray($data); } protected function _postExchangeArray($data) { } public function isModified($field = null) { if (null !== $field) { return !empty($this->_modifiedFields[$field]); } return !empty($this->_modifiedFields); } public function getModifiedFields() { return $this->_modifiedFields; } public function getCleanData() { return $this->_cleanData; } public function getOriginal($key = null, $default = null) { if ($key === null) { return $this->_cleanData; } return Moto\Util::getFrom($this->_cleanData, $key, $default); } public function set($name, $value) { if (array_key_exists($name, $this->_fields) && $this->{$name} !== $value) { $this->{$name} = $value; $this->_modifiedFields[$name] = $value; } return $this; } public function setFromArray($data) { $data = $this->_preSetFromArray($data); reset($this->_fields); foreach ($this->_fields as $field => $default) { if (array_key_exists($field, $data)) { $method = 'set' . ucfirst($field); if (method_exists($this, $method)) $this->$method($data[$field]); else { if (null === $data[$field]) { if (isset($default['ignore_if_null']) && $default['ignore_if_null']) { continue; } } if (is_object($this->{$field})) { if ($this->{$field} instanceof AbstractModel) { $this->{$field}->setFromArray($data[$field]); if ($this->{$field}->isModified()) $this->_modifiedFields[$field] = true; } else { if (is_array($data[$field])) { $data[$field] = json_decode(json_encode($data[$field])); } if (is_object($data[$field])) { $this->{$field} = $data[$field]; } else { $this->{$field} = json_decode($data[$field]); } } } else { if ($this->{$field} !== $data[$field]) { $this->{$field} = $data[$field]; $this->_modifiedFields[$field] = true; } } } } } $this->_postSetFromArray($data); } protected function _postSetFromArray($data) { } protected function _preSetFromArray($data) { return $data; } public function toArray() { return $this->getData(); } public function getData($skipVirtual = false) { $data = array(); foreach ($this->_fields as $field => $options) { if (!$skipVirtual || !isset($options['virtual']) || !$options['virtual']) { if (is_object($this->{$field})) { if ($this->{$field} instanceof AbstractModel) { $value = $this->{$field}->getData($skipVirtual); } else { $value = $this->{$field}; } if (is_object($value)) { $value = json_decode(json_encode($value), true); } if (null !== $value) $data[$field] = $value; } else { if (array_key_exists('default', $options) && $this->_skipDefaults && $options['default'] === $this->{$field}) { continue; } $data[$field] = isset($this->{$field}) ? $this->{$field} : null; } } } return $data; } public function getArrayCopy() { return $this->toArray(); } public function toInsert() { return $this->getData(true); } public function toUpdate() { return $this->getData(true); } public function setSkipDefaults($skip = true) { $this->_skipDefaults = (bool) $skip; foreach ($this->_fields as $field => $options) { if (is_object($this->{$field}) && method_exists($this->{$field}, 'setSkipDefaults')) { $this->{$field}->setSkipDefaults($skip); } } } public function isExistsField($name) { return array_key_exists($name, $this->_fields); } }