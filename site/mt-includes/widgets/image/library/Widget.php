<?php
namespace Website\Widgets\Image; use Moto; class Widget extends Moto\System\Widgets\AbstractWidget { protected $_name = 'image'; protected static $_defaultProperties = array( 'id' => null, 'src' => '', 'alt' => '', 'title' => '', 'align' => array( 'desktop' => 'left', 'tablet' => '', 'mobile-v' => '', 'mobile-h' => '', ), 'preset' => 'default', 'thumbnail' => '', 'animation' => '', 'link' => array( 'action' => 'none', 'properties' => array(), ), 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), ); protected $_templateType = 'templates'; protected $_widgetId = true; } 