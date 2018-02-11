<?php
namespace Website\Widgets\CompletionBars; use Moto; class Widget extends Moto\System\Widgets\AbstractWidget { protected $_name = 'completion_bars'; protected static $_defaultProperties = array( 'items' => array(), 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), 'visible_on' => 'mobile-v', 'preset' => 'default', 'animation' => '', 'labelTextStyle' => 'moto-text_system_10', 'progressTextStyle' => 'moto-text_system_10', 'stripeHeight' => 6, ); protected $_templateType = 'templates'; protected $_widgetId = true; public function getBackgroundColorClass($value) { return $this->getCssClassColor($value, 'moto-bg-'); } public function getCssInlineValue($rule, $value, $postfix = '') { $result = ''; if (!isset($value, $rule)) return $result; if ($value[0] !== '@') { if (intval($value) !== 0) { $value .= $postfix; } $result = $rule . ':' . $value . ';'; } return $result; } } 