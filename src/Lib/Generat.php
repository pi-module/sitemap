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
use Module\Sitemap\Lib\Content;


/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class Generat
{
    protected $name = 'sitemap.xml';
    protected $setindex = true;
    protected $settop = true;
    protected $setlist = true;
    protected $module = '';
    protected $table = '';

    public function __construct($name = 'sitemap.xml', $module = '', $table = '', $setindex = true, $settop = true, $setlist = true)
    {
    	$this->name = $name;
        $this->setindex = $setindex;
        $this->settop = $settop;
        $this->setlist = $setlist;
        $this->module = $module;
        $this->table = $table;
    }
    

    public function file()
    {
    	$return = array();
    	$sitemap = Pi::path($this->name);
    	// Remove old file
        if (file_exists($sitemap)) {
        	if (!@unlink($sitemap)) {
        		$return['message'] = sprintf(__('Unable to remove %s , please remove file manual and generat again'), $sitemap);
        		$return['status'] = 0;
        		return $return;
        	}
        }
        // Set and Check content
        $content = Content::make();
        if(empty($content)) {
        	$return['message'] = __('Content doesnt set');
        	$return['status'] = 0;
        	return $return;
        }
        // Write information on sitemap.xml
        $return = $this->write($sitemap, $content);
        return $return;
    }	

    public function write($sitemap, $content)
    {
        $return = array();
        $file = fopen($sitemap, "x+");
        fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>');
        fwrite($file, PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        // Write links
        foreach ($content as $link) {
        	fwrite($file, PHP_EOL . '	<url>');
        	if (!empty($link['loc'])) {
        		fwrite($file, PHP_EOL . '		<loc>' . $this->escapingUrl($link['loc']) . '</loc>');
        	}
        	if (!empty($link['lastmod'])) {
        		fwrite($file, PHP_EOL . '		<lastmod>' . $link['lastmod'] . '</lastmod>');
        	}
        	if (!empty($link['changefreq'])) {
        		fwrite($file, PHP_EOL . '		<changefreq>' . $link['changefreq'] . '</changefreq>');
        	}
        	if (!empty($link['priority'])) {
        		fwrite($file, PHP_EOL . '		<priority>' . $link['priority'] . '</priority>');
        	}
        	fwrite($file, PHP_EOL . '	</url>');
        }	
        // Close file
        fwrite($file, PHP_EOL . '</urlset>');
        fclose($file);
        // Unset
        unset($content);
        // Save history
        $this->history();
        // Return
        $return['message'] = __('Sitemap build');
        $return['status'] = 1;
        return $return;
    }

    public function escapingUrl($url)
    {
    	$url = htmlspecialchars($url, ENT_QUOTES);
    	$url = urldecode($url);
    	$url = str_replace(' ', '%20', $url);
    	return $url;
    }

    public function history()
    {
        $row = Pi::model('history', 'sitemap')->find($this->name, 'file');
        if ($row) {
            $row->module = $this->module;
            $row->table = $this->table;
            $row->create = time();
            $row->save();
        } else {
            $values = array();
            $values['file'] = $this->name;
            $values['module'] = $this->module;
            $values['table'] = $this->table;
            $values['create'] = time();
            $row = Pi::model('history', 'sitemap')->createRow();
            $row->assign($values);
            $row->save();
        }
    }
}	