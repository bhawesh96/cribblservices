<?php
namespace Moto\System; use Moto; use Zend\Stdlib\ArrayUtils; class Request { const METHOD_GET = 'GET'; const METHOD_POST = 'POST'; const METHOD_JSON_RPC = 'JSONRPC'; const METHOD_AJAX = 'JSONRPC'; protected static $_initialized = false; protected static $_request = null; protected static $_method = null; protected static $_params = array(); protected static $_get = array(); protected static $_post = array(); protected static $_cookie = array(); protected static $_server = null; protected static $_requestUrl = null; protected static $_requestUrlMethod = 'get'; protected static $_requestUri = null; public static function init() { if (static::$_initialized) { return; } static::$_initialized = true; static::$_method = (empty($_SERVER['REQUEST_METHOD']) ? '' : strtoupper($_SERVER['REQUEST_METHOD'])); static::$_server = $_SERVER; if ($_GET) { static::$_get = $_GET; } if ($_POST) { static::$_post = $_POST; } if ($_COOKIE) { static::$_cookie = $_COOKIE; } static::fixServerEnvironment(); if (Moto\System::isInstalled()) { } static::_updateParams(); } public static function getOriginalServerVars() { if (null == static::$_server) { static::$_server = $_SERVER; } return static::$_server; } protected static function fixServerEnvironment() { static::$_server = $_SERVER; if (empty($_SERVER['REQUEST_URI'])) { if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) { $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL']; } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) { $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL']; } elseif (isset($_SERVER['REDIRECT_URL'])) { $_SERVER['REQUEST_URI'] = $_SERVER['REDIRECT_URL']; } else { $_SERVER['REQUEST_URI'] = ''; if (!isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO'])) { $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO']; } if (!empty($_SERVER['PATH_INFO'])) { $_SERVER['REQUEST_URI'] = (($_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME']) ? '' : $_SERVER['SCRIPT_NAME']); $_SERVER['REQUEST_URI'] .= $_SERVER['PATH_INFO']; } if (!empty($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; } } } if (empty($_SERVER['PHP_SELF'])) { $_SERVER['PHP_SELF'] = preg_replace('/(\?.*)?$/', '', $_SERVER['REQUEST_URI']); } } public static function setRequest($request) { static::$_request = $request; if ($request instanceof Moto\Json\Server\Request) { static::$_method = static::METHOD_JSON_RPC; static::$_post = $request->getParams(); } static::_updateParams(); } protected static function _updateParams() { static::$_params = ArrayUtils::merge(static::$_get, static::$_post); } public static function getParams() { return static::$_params; } public static function getQuery($name = null, $default = null) { if (null == $name) { return static::$_get; } return static::_safeGet(static::$_get, $name, $default); } public static function getPost($name = null, $default = null) { if (null == $name) { return static::$_post; } return static::_safeGet(static::$_post, $name, $default); } public static function getCookie($name = null, $default = null) { if (null == $name) { return static::$_cookie; } return static::_safeGet(static::$_cookie, $name, $default); } public static function getParam($name, $default = null) { return (array_key_exists($name, static::$_params) ? static::$_params[$name] : $default); } public static function getServer($name, $default) { return static::_safeGet($_SERVER, $name, $default); } public static function getReferrerUrl($default = null) { return static::_safeGet($_SERVER, 'HTTP_REFERER', $default); } public static function getRefererUrl($default = null) { return static::getReferrerUrl($default = null); } public static function getHttpHost($default = '') { $host = (empty($_SERVER['HTTP_HOST']) ? (empty($_SERVER['SERVER_NAME']) ? $default : $_SERVER['SERVER_NAME']) : $_SERVER['HTTP_HOST']); return $host; } public static function getIp($default = '') { $ip = (empty($_SERVER['REMOTE_ADDR']) ? $default : $_SERVER['REMOTE_ADDR']); return $ip; } public static function isGet() { return (static::$_method === self::METHOD_GET); } public static function isPost() { return (static::$_method === self::METHOD_POST); } public static function isJsonRpc() { return (static::$_method === self::METHOD_JSON_RPC); } public static function isAjax() { return (trim(strtolower(static::_safeGet($_SERVER, 'HTTP_X_REQUESTED_WITH', ''))) == strtolower('XMLHttpRequest')); } public static function isJson() { if (!empty($_SERVER['CONTENT_TYPE']) && preg_match('/application\/json/', strtolower($_SERVER['CONTENT_TYPE']))) { return true; } return (static::isJsonRpc() || static::isAjax()); } public static function isSSL() { $result = false; if ( isset($_SERVER['HTTPS']) ) { if ( strtolower($_SERVER['HTTPS']) === 'on' || $_SERVER['HTTPS'] == '1') { $result = true; } } elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) { $result = true; } return $result; } protected static function _safeGet($target, $name, $default = null) { if (is_array($target)) { return (array_key_exists($name, $target) ? $target[$name] : $default); } if (is_object($target)) { return (property_exists($target, $name) ? $target->{$name} : $default); } return $default; } public static function getRequestUri() { if (null == static::$_requestUri) { $uri = static::getServer('REQUEST_URI', ''); $websitePath = Moto\System::getRelativeUrl(); if (!empty($websitePath)) { if (strpos($uri, $websitePath) === 0) { $uri = substr($uri, strlen($websitePath) - 1); } } static::$_requestUri = $uri; } return static::$_requestUri; } public static function getRequestUrl() { $debug = false; $queryString = ''; if (null == static::$_requestUrl) { $slashOnBegin = false; $slashOnEnd = false; $url = self::getQuery('url', '/'); $uri = $url; $method = 'get'; if ($debug) echo "get : $url\n"; if (Moto\Website\Settings::get('permalinks')) { $slashOnEnd = true; $slashOnBegin = true; $uri = static::getRequestUri(); if ($debug) echo "uri: $uri\n"; if (strpos($uri, '?')) { $uri = explode('?', $uri, 2); $queryString = $uri[1]; $queryString = ltrim($queryString, '?'); $uri = $uri[0]; } if ($debug) echo "uri: $uri\n"; if (!empty($uri) && $uri != '/') { $method = 'permalink'; $url = $uri; } if ($debug) echo "url : $url\n"; if (strpos($url, '.html')) { $url = substr($url, 0, strpos($url, '.html')); $method = 'permalink.html'; $slashOnEnd = false; if ($debug) echo "url : $url\n"; } } static::$_requestUrlMethod = $method; if ($debug) echo "method: $method\n"; if (empty($url)) { $url = '/'; } $_url = preg_replace('/[\/\\\]+/', '/', ($slashOnEnd ? $url : rtrim($url, '/')). ($slashOnEnd ? '/' : '')); if (!$slashOnBegin) { $_url = ltrim($_url, '/'); } if ($debug) { echo "\n"; echo "slashOnEnd : $slashOnEnd\n"; echo "URI : $uri\n"; echo "URL : $url\n"; echo "_RL : $_url\n"; echo "\n"; if ($url !== $_url && $method == 'permalink') { echo "Need redirect to $_url\n"; } if ($method == 'get' && $_url !== $url && $url !== '/') { echo "Need redirect to $_url\n"; } if ($uri !== $_url . '.html' && $method == 'permalink.html') { echo "Need redirect to $_url.html\n"; } } if ($debug) { echo "url : $url\n"; echo "|" . substr($url, -1) . "|\n"; } if (!static::isPost() && !static::isAjax()) { $redirect = ''; if ($method == 'permalink' && $_url !== $url) { $redirect = $_url; } elseif (0 && $method == 'get' && $_url !== $url && $url !== '/') { $queryString = self::getQuery(); $queryString['url'] = $_url; $redirect = '?' . http_build_query($queryString, '', '&amp;'); $redirect = str_replace('%2F', '/', $redirect); $queryString = null; } elseif ($method == 'permalink.html' && $_url . '.html' !== $uri) { $redirect = $_url . '.html'; } $redirect = ltrim($redirect, '/'); if (!empty($redirect)) { if (!empty($queryString)) { $redirect .= '?' . $queryString; } if ($debug) { echo "Redirect to : $redirect\n"; } $redirect = Moto\System::getAbsoluteUrl($redirect); Moto\System::redirect($redirect); } } $url = $_url; static::$_requestUrl = $url; if ($debug) { print_r($_SERVER); exit; } } return static::$_requestUrl; } }