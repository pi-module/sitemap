<?php
/**
* Pi Engine (http://pialog.org)
*
* @link http://code.pialog.org for the Pi Engine source repository
* @copyright Copyright (c) Pi Engine http://pialog.org
* @license http://pialog.org/license.txt New BSD License
*/

namespace Module\Sitemap\Api;

use Pi;
use Pi\Application\AbstractApi;
use Module\Sitemap\Lib\Generat;

/**
* Sitemap APIs
*
* @author Hossein Azizabadi <azizabadi@faragostaresh.com>
*
* Pi::service('api')->sitemap(array('Sitemap', 'add'), $module, $table, $link);
* Pi::service('api')->sitemap(array('Sitemap', 'item'), $module, $table, $plus);
* Pi::service('api')->sitemap(array('Sitemap', 'generat'), $name, $module, $table, $setindex, $settop, $setlist);
*
* Pi::api('sitemap', 'sitemap')->add($module, $table, $link);
* Pi::api('sitemap', 'sitemap')->item($module, $table, $plus);
* Pi::api('sitemap', 'sitemap')->generat($name, $module, $table, $setindex, $settop, $setlist);
* 
* if (Pi::service('module')->isActive('sitemap')) {
* 	$link = array();
* 	$link['loc'] = Pi::url('YOUR ROTE URL');
* 	$link['lastmod'] = date("Y-m-d H:i:s"); // Or set empty
* 	$link['changefreq'] = 'daily'; // Or set empty
* 	$link['priority'] = 1; // Or set empty
* 	Pi::api('sitemap', 'sitemap')->add($module, $table, $link);
* }	
* 
*/
class Sitemap extends AbstractApi
{ 
	public function add($module, $table, $link)
    {
    	// Set
    	$values = array();
    	$values['loc'] = $link['loc'];
    	$values['lastmod'] = (!empty($link['lastmod'])) ? $link['lastmod'] : date("Y-m-d H:i:s");
    	$values['changefreq'] = (!empty($link['changefreq'])) ? $link['changefreq'] : 'daily';
    	$values['priority'] = (!empty($link['priority'])) ? $link['priority'] : '';
    	$values['create'] = time();
    	$values['module'] = $module;
    	$values['table'] = $table;
    	$values['status'] = 1;
    	// Save
    	$row = Pi::model('url_list', $this->getModule())->createRow();
        $row->assign($values);
        $row->save();
        // Update item table
        $this->item($module, $table);
    }	

    public function item($module, $table, $plus = true)
    {
        // Select row
        $where = array('module' => $module, 'table' => $table);
    	$select = Pi::model('item', $this->getModule())->select()->where($where)->limit(1);
    	$rowset = Pi::model('item', $this->getModule())->selectWith($select);
    	$row = $rowset->current();
    	if ($row) {
            $row->count = ($plus) ? $row->count + 1 : $row->count - 1;
            $row->save();
        } else {
            $values = array();
            $values['module'] = $module;
    	    $values['table'] = $table;
    	    $values['count'] = 1;
            $row = Pi::model('item', $this->getModule())->createRow();
            $row->assign($values);
            $row->save();
        }
    }

    public function generat($name = 'sitemap.xml', $module = '', $table = '', $setindex = true, $settop = true, $setlist = true)
    {
        $sitemap = new Generat($name, $module, $table, $setindex, $settop, $setlist);
        $sitemap->file();
    }
}