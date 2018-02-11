<?php
namespace Website\Widgets\AudioPlayer; use Moto; class Widget extends Moto\System\Widgets\AbstractWidget { protected $_name = 'audio_player'; protected static $_defaultProperties = array( 'preset' => 'default', 'tracks' => array(), 'settings' => array( 'autoplay' => false, 'loop' => true, 'muted' => true, 'volume' => 1, ), 'buttons' => array( 'playpause' => true, 'stop' => true, 'muted' => true, 'loop' => true, ), 'animation' => '', 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), ); protected $_templateType = 'templates'; protected $_widgetId = true; public function exportButtons() { return json_encode($this->properties['buttons']); } } 