<?php
namespace Moto\Application\MediaLibrary\InputFilter; use Moto\InputFilter\AbstractInputFilter; use Zend\InputFilter\Exception; use Zend\InputFilter\InputFilterInterface; use Moto; class DownloadFile extends AbstractInputFilter { protected $_name = 'mediaLibraryItem.downloadFile'; public function init() { $this->add(array( 'name' => 'url', 'required' => true, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), array( 'name' => 'Moto\Filter\UriNormalize', 'options' => array( 'removeFragment' => true ) ), ), 'validators' => array( array( 'name' => 'Uri', 'options' => array( 'allowAbsolute' => true, 'allowRelative' => false, ), ) ), )); $this->add(array( 'name' => 'folder_id', 'required' => false, 'filters' => array( array( 'name' => 'Moto\Filter\DefaultFilter', 'options' => array( 'value' => 0 ) ), array('name' => 'StripTags'), array('name' => 'StringTrim'), array('name' => 'Moto\Filter\IntValue'), ), 'validators' => array( array( 'name' => 'Digits', 'break_chain_on_failure' => true, ), array( 'name' => 'Moto\Validator\ParentId', 'options' => array( 'table' => Moto\Config::get('database.prefix') . 'media_folders', 'field' => 'id', 'adapter' => Moto\Config::get('databaseAdapter'), ) ) ), )); $form = $this; $this->add(array( 'name' => 'filename', 'required' => false, 'filters' => array( array('name' => 'StripTags'), array('name' => 'StringTrim'), array( 'name' => 'Callback', 'options' => array( 'callback' => function($filename) use($form) { $filename = trim($filename); $filename = strip_tags($filename); $url = $form->getRawValue('url'); $urlInfo = pathinfo($url); if (empty($filename)) { $filename = Moto\Util::getFrom($urlInfo, 'filename', ''); } $filename = str_replace(array("\n", "\r", "\t", '/', '\\'), '', $filename); $transliterateFilter = new Moto\Filter\Transliterate(); $filename = $transliterateFilter->filter($filename); $filename = strtolower($filename); $filename = preg_replace('/[^a-z0-9_\-\.]/', '', $filename); $filename = preg_replace('/([_\-\.])(\\1+)/', '$1', $filename); $fileInfo = pathinfo($filename); if (empty($fileInfo['extension'])) { $filename .= '.' . Moto\Util::getFrom($urlInfo, 'extension'); } return $filename; }, ) ) ), 'validators' => array( array( 'name' => 'Callback', 'options' => array( 'callback' => function($filename, $context) { $allowed_ext_list = Moto\Config::get('allowedExtList'); $info = pathinfo($filename); $allow = !empty($info['extension']) && in_array($info['extension'], $allowed_ext_list); return $allow; }, ) ) ), )); } }