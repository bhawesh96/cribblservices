<?php
$t0 = microtime(1); require_once __DIR__ . '/common.php'; $app = Moto\System::getApplication(); $response = $app->handle(); if (Moto\System::getStage() == Moto\System::ENV_DEVELOPMENT) { $response->setHeader('X-memory_usage', number_format(memory_get_usage())); $response->setHeader('X-memory_peak', number_format(memory_get_peak_usage())); $response->setHeader('X-microtime', microtime(1) - $t0); $response->setHeader('X-redirect', $response->getHeader('Location')); } echo $response; 