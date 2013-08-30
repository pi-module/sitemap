<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Sitemap\Lib;

use Pi;
use Module\Sitemap\Lib\Generat;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class Content extends Generat
{
    public function make()
    {
    	$content = array();
        // Set index page url
        if ($this->setindex) {
        	$content = self::indexurl($content);
        }
        // Set top urls
        if ($this->settop) {
        	$content = self::toplink($content);
        }
        // Set list urls
        if ($this->setlist) {
        	$content = self::listlink($content);
        }
        $content = array_slice($content, 0, 499);
    	return $content;
    }

    public function indexurl($content)
    {
        $content[] = array(
    		'loc' => Pi::url('www'),
    		'lastmod' => date("Y-m-d H:i:s"),
    		'changefreq' => 'daily',
    		'priority' => '',
    	);
    	return $content;
    }

    public function toplink($content)
    {
    	$order = array('id DESC', 'create DESC');
        $select = Pi::model('url_top', 'sitemap')->select()->order($order);
        $rowset = Pi::model('url_top', 'sitemap')->selectWith($select);
        foreach ($rowset as $row) {
            $url_top[$row->id] = $row->toArray();
            $link['loc'] = $url_top[$row->id]['loc'];
            $link['lastmod'] = $url_top[$row->id]['lastmod'];
            $link['changefreq'] = $url_top[$row->id]['changefreq'];
            $link['priority'] = $url_top[$row->id]['priority'];
            $content[] = $link;
        }
        return $content;
    }

    public function listlink($content)
    {
    	// Set info
        $limit = intval(500 - count($content));
    	$order = array('id DESC', 'create DESC');
        $where = array('status' => 1);
        // Set table where
        if (!empty($this->module) && !empty($this->table)) {
            $where['module'] = $this->module;
            $where['table'] = $this->table;
        }
        // Make list
        $select = Pi::model('url_list', 'sitemap')->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('url_list', 'sitemap')->selectWith($select);
        foreach ($rowset as $row) {
            $url_top[$row->id] = $row->toArray();
            $link['loc'] = $url_top[$row->id]['loc'];
            $link['lastmod'] = $url_top[$row->id]['lastmod'];
            $link['changefreq'] = $url_top[$row->id]['changefreq'];
            $link['priority'] = $url_top[$row->id]['priority'];
            $content[] = $link;
        }
        return $content;
    }
}