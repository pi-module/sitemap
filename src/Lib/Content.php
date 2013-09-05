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
    protected $content = array();

    public function __construct($content = '')
    {
        if (!empty($content) && is_array($content)) {
            $this->content = $content;
        } 
    }

    public function make()
    {
        // Set index page url
        if ($this->setindex) {
            $this->content = $this->indexurl($this->content);
        }
        // Set top urls
        if ($this->settop) {
            $this->content = $this->topurl($this->content);
        }
        // Set list urls
        if ($this->setlist) {
            $this->content = $this->listurl($this->content);
        }
        // Return content array
        return $this->content;
    }

    public function indexurl($content)
    {
        $content[] = array(
    		'uri' => Pi::url('www'),
    		'lastmod' => date("Y-m-d H:i:s"),
    		'changefreq' => 'daily',
    		'priority' => '',
    	);
    	return $content;
    }

    public function topurl($content)
    {
    	$order = array('id DESC', 'create DESC');
        $select = Pi::model('url_top', 'sitemap')->select()->order($order);
        $rowset = Pi::model('url_top', 'sitemap')->selectWith($select);
        foreach ($rowset as $row) {
            $url_top[$row->id] = $row->toArray();
            $link['uri'] = $url_top[$row->id]['loc'];
            $link['lastmod'] = $url_top[$row->id]['lastmod'];
            $link['changefreq'] = $url_top[$row->id]['changefreq'];
            $link['priority'] = $url_top[$row->id]['priority'];
            $content[] = $link;
        }
        return $content;
    }

    public function listurl($content)
    {
    	// Set info
    	$order = array('id DESC', 'create DESC');
        $where = array('status' => 1);
        // Set table where
        if (!empty($this->module) && !empty($this->table)) {
            $where['module'] = $this->module;
            $where['table'] = $this->table;
        }
        // Make list
        $select = Pi::model('url_list', 'sitemap')->select()->where($where)->order($order);
        $rowset = Pi::model('url_list', 'sitemap')->selectWith($select);
        foreach ($rowset as $row) {
            $url_top[$row->id] = $row->toArray();
            $link['uri'] = $url_top[$row->id]['loc'];
            $link['lastmod'] = $url_top[$row->id]['lastmod'];
            $link['changefreq'] = $url_top[$row->id]['changefreq'];
            $link['priority'] = $url_top[$row->id]['priority'];
            $content[] = $link;
        }
        return $content;
    }
}