<?php
namespace Moto\Widgets\GridGallery; use Website; use Moto; class Widget extends Website\Widgets\GridGallery\Widget { public function init() { Moto\System\Log::notice('DEPRECATED_FILE', array( 'class' => __CLASS__, 'file' => __FILE__ )); parent::init(); } }