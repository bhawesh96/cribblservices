<?php
namespace Website\Widgets\Row; use Moto; class Widget extends Moto\System\Widgets\AbstractContainerWidget { protected $_name = 'row'; protected static $_defaultProperties = array( 'fixed' => false, 'gutter' => true, 'justify' => false, 'grid' => 'sm', 'bgFixed' => false, 'bgParallaxed' => false, 'anchor' => '', 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), ); protected $_templateType = 'templates'; protected $_templatePath = '@websiteWidgets/row/templates/default.twig.html'; protected $_widgetId = false; public $customAttributes = []; public function getCustomAttributes() { return $this->customAttributes; } public function preRender() { parent::preRender(); if ($this->getPropertyValue('bgParallaxed')) { $this->customAttributes['data-stellar-background-ratio'] = '0.5'; } } public function getCssClasses() { $result = ''; if ($this->getPropertyValue('fixed', false)) { $result .= ' row-fixed'; } if (!$this->getPropertyValue('gutter', true)) { $result .= ' row-gutter-0'; } if ($this->getPropertyValue('justify', false)) { $result .= ' moto-justify-content_' . $this->getPropertyValue('justify'); } if ($this->getPropertyValue('styles.background-color', false)) { $result .= ' ' . $this->getCssClassColor($this->getPropertyValue('styles.background-color'), 'moto-bg-'); } $result .= ' ' . $this->getSpacing('classes'); return $result; } public function getStylesValue() { return $this->getPropertyValue('styles'); } } 