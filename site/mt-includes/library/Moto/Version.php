<?php
namespace Moto; use Moto; class Version { public static function getBuild() { return Moto\Update\Upgrade::BUILD; } public static function getVersion() { return Moto\Update\Upgrade::VERSION; } public static function getCurrentBuild($force = false) { return Moto\System\Settings::get('build', 0); } public static function getCurrentVersion($force = false) { return Moto\System\Settings::get('version', '3.0.0'); } }