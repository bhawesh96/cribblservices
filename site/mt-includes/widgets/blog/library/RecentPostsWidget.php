<?php
namespace Website\Widgets\Blog; use Moto; use Website; use Zend\Db\Sql\Where; class RecentPostsWidget extends Website\Widgets\Blog\AbstractPostsWidget { protected $_name = 'blog.recent_posts'; protected static $_defaultProperties = array( 'spacing' => array( 'top' => 'auto', 'right' => 'auto', 'bottom' => 'auto', 'left' => 'auto', ), 'item_count' => 5, 'label' => '', 'style' => array( 'heading' => array( 'font_style' => 'moto-text_system_7', ), 'title' => array( 'font_style' => 'moto-text_system_8', ), 'feature_image' => array( 'preset' => 'default', ), ), ); protected $_posts = null; public function getTemplatePath($preset = null) { return '@websiteWidgets/blog/templates/recent_posts.twig.html'; } public function preRender() { parent::preRender(); $this->_widget['posts'] = $this->getPosts(); } public function getPosts() { $table = $this->_getTable(); $count_post = $this->getPropertyValue('item_count') * 1; $count_post = max(1, $count_post); $count_post = min(20, $count_post); $where = new Where(); if (!Moto\Website\Application::getInstance()->isPreviewMode()) { $where->lessThanOrEqualTo('published', date('Y-m-d H:i:00', time())); } $where->equalTo('type', 'blog.post'); $where->equalTo('status', Moto\Application\Pages\PageModel::STATUS_PUBLISH); $this->_posts = $table->getList($where, array('*'), array('published' => 'DESC'), $count_post); return $this->_posts; } } 