<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Sitemap\Api;

use Pi;
use Pi\Application\AbstractApi;
use Module\Sitemap\Lib\Generat;

/**
* Pi::api('sitemap', 'sitemap')->add($module, $table, $item, $loc);
* Pi::api('sitemap', 'sitemap')->update($module, $table, $item, $loc);
* Pi::api('sitemap', 'sitemap')->remove($loc);
*/
class Sitemap extends AbstractApi
{ 
	/**
    * Add new link to url_list table
    * 
    * @param  string $module
    * @param  string $table
    * @param  int    $item
    * @param  string  $loc
    */
    public function add($module, $table, $item, $loc)
    {
        // Set
        $values = array();
        $values['loc'] = $loc;
        $values['lastmod'] = date("Y-m-d H:i:s");
        $values['changefreq'] = 'daily';
        $values['priority'] = '';
        $values['time_create'] = time();
        $values['module'] = $module;
        $values['table'] = $table;
        $values['item'] = intval($item);
        $values['status'] = 1;
        // Save
        $row = Pi::model('url_list', 'sitemap')->createRow();
        $row->assign($values);
        $row->save();
    }

    /**
    * Update link to url_list table
    * 
    * @param  string $module
    * @param  string $table
    * @param  int    $item
    * @param  string  $loc
    */
    public function update($module, $table, $item, $loc)
    {
        $where = array('module' => $module, 'table' => $table, 'item' => $item);
        $select = Pi::model('url_list', 'sitemap')->select()->where($where)->limit(1);
        $row = Pi::model('url_list', 'sitemap')->selectWith($select)->current();
        if (!empty($row) && is_object($row)) {
            $row->loc = $loc;
            $row->lastmod = date("Y-m-d H:i:s");
            $row->save();
        } else {
            $this->add($module, $table, $item, $loc);
        }
    }

    /**
    * Remove link from url_list table
    * 
    * @param  string $loc
    */
    public function remove($loc)
    {
        $row = Pi::model('url_list', 'sitemap')->find($loc, 'loc');
        $row->delete();  
    }	
}