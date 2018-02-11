<?php
namespace Moto\Sql; class Parser { protected $_options = array( 'cleanRemoveIncrement' => true, 'cleanEngine' => false, 'explodeAsBlock' => false, 'tablePrefix' => '', ); function setTablePrefix($value) { $this->_options['tablePrefix'] = $value; } function parseFile($file) { if (!file_exists($file)) { throw new \Exception (__CLASS__ . ' : File not exists.'); } $data = file_get_contents($file); $data = $this->explodeSQL($data); return $data; } function explodeSQL($sql, $options = array()) { $result = array(); $rows = explode("\n", $sql); $block = ''; $options = array_merge($this->_options, $options); for ($i = 0, $count = count($rows); $i < $count; $i++) { $raw = $rows[$i]; $row = trim($raw); $semicolon = (substr($row, -1) == ';'); $row = rtrim($row, ';'); if (empty($row) || substr($row, 0, 2) == '--' || (substr($row, 0, 2) == '/*' && substr($row, -2) == '*/') ) { continue; } if ($options['cleanRemoveIncrement']) { $raw = preg_replace('/( AUTO_INCREMENT=[0-9]+)/', '', $raw); } if ($options['cleanEngine']) { $raw = preg_replace('/( ENGINE=(InnoDB|MyISAM)+)/', '', $raw); } $block .= $raw; if ($options['explodeAsBlock']) { $block .= "\n"; } if ($semicolon) { $result[] = $this->prepareBlock($block); $block = ''; } } if (!empty($block)) { $result[] = $this->prepareBlock($block); } return $result; } function prepareBlock($sql, $options = array()) { $options = array_merge($this->_options, $options); $sql = trim($sql); if (empty($sql) || preg_match('/^SET SQL_MODE/i', $sql)) { return null; } $result = array( 'action' => null, 'sql' => $sql, 'table' => null, ); $parts = explode(' ', $sql); $action = strtolower($parts[0]); $result['action'] = $action; $regexp = null; switch ($action) { case 'drop': $regexp = '/^(DROP TABLE (IF EXISTS )?`)([^`]+)/i'; break; case 'truncate': $regexp = '/^(TRUNCATE TABLE (IF EXISTS )?`)([^`]+)/i'; break; case 'alter': $regexp = '/^(ALTER TABLE (IF EXISTS )?`)([^`]+)/i'; break; case 'create': $regexp = '/^(CREATE TABLE (IF NOT EXISTS )?`)([^`]+)/i'; break; case 'insert': $regexp = '/^(INSERT[\s]*(IGNORE[\s]*)?INTO `)([^`]+)/i'; break; case 'update': $regexp = '/^(UPDATE ()?`)([^`]+)/i'; break; default: throw new \Exception(__CLASS__ . ' : Unknown sql action: ' . $action); break; } if ($regexp) { if (preg_match($regexp, $sql, $match)) { $result['table'] = $match[3]; } if (!empty($options['tablePrefix'])) { $sql = preg_replace($regexp, '$1' . $options['tablePrefix'] . '$3', $sql); } } $result['sql'] = $sql; return $result; } }