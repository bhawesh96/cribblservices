<?php
namespace Website\Widgets\DividerHorizontal; use Moto; class Widget extends Moto\System\Widgets\AbstractWidget { protected $_name = 'divider_horizontal'; protected static $_defaultProperties = array( 'align' => array( 'desktop' => 'left', 'tablet' => '', 'mobile-v' => '', 'mobile-h' => '', ), 'preset' => 'default', 'width' => 100, 'fixedWidth' => 1200, 'isFixed' => false, 'animation' => '', 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), ); protected $_templateType = 'templates'; protected $_widgetId = true; } 