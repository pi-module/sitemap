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
    	$sitemap = Pi::path($this->name);
    	// Remove old file
        if (file_exists($sitemap)) {
        	if (!@unlink($sitemap)) {
        		$message = sprintf(__('Unable to remove %s , please remove file manual and generat again'), $sitemap);
                throw new \Exception($message);
        	}
        }
        // Set and Check content
        $content = Content::make();
        if(empty($content)) {
        	$message = __('Content doesnt set');
        	throw new \Exception($message);
        }
        // Write information on sitemap.xml
        $this->write($sitemap, $content);
    }	

    public function write($sitemap, $content)
    {
        // Set validators
        $validatorLoc = new \Zend\Validator\Sitemap\Loc();
        $validatorLastmod = new \Zend\Validator\Sitemap\Lastmod();
        $validatorChangefreq = new \Zend\Validator\Sitemap\Changefreq();
        $validatorPriority = new \Zend\Validator\Sitemap\Priority();

        // Open file
        $file = fopen($sitemap, "x+");
        fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>');
        fwrite($file, PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        // Write links
        foreach ($content as $link) {
        	fwrite($file, PHP_EOL . '	<url>');
        	if (!empty($link['loc']) && $validatorLoc->isValid($link['loc'])) {
        		fwrite($file, PHP_EOL . '		<loc>' . $this->escapingUrl($link['loc']) . '</loc>');
        	}
        	if (!empty($link['lastmod']) && $validatorLastmod->isValid($link['lastmod'])) {
        		fwrite($file, PHP_EOL . '		<lastmod>' . $link['lastmod'] . '</lastmod>');
        	}
        	if (!empty($link['changefreq']) && $validatorChangefreq->isValid($link['changefreq'])) {
        		fwrite($file, PHP_EOL . '		<changefreq>' . $link['changefreq'] . '</changefreq>');
        	}
        	if (!empty($link['priority']) && $validatorPriority->isValid($link['priority'])) {
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