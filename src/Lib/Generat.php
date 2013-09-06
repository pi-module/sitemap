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
    protected $path = '';
    protected $limit = '';

    public function __construct($name = 'sitemap.xml', $module = '', $table = '', $setindex = true, $settop = true, $setlist = true)
    {
    	$this->name = $name;
        $this->setindex = $setindex;
        $this->settop = $settop;
        $this->setlist = $setlist;
        $this->module = $module;
        $this->table = $table;

        $this->config();
    }
    
    public function config()
    {
        $config = Pi::service('registry')->config->read('sitemap', 'sitemap');
        // Set path
        $this->path = trim($config['sitemap_location'], '/');
        if (!empty($this->path)) {
            Pi::service('file')->mkdir($this->path);
        }
        // Set limit
        $this->limit = intval($config['sitemap_limit']);
    }

    public function content()
    {
    	$content = new Content;
        $content = $content->make();

        if(empty($content)) {
            $message = __('Please set sitemap content');
            throw new \Exception($message);
        }
        return $content;
    }	

    public function write($xml)
    {
        // Set path
        if (empty($this->path)) {
            $sitemap = $sitemaps = Pi::path($this->name);
        } else {
            $sitemap = $sitemaps[] = Pi::path(sprintf('%s/%s', $this->path, $this->name));
            $sitemaps[] = Pi::path($this->name);
        }
        // Remove old file
        if (Pi::service('file')->exists($sitemaps)) {
            Pi::service('file')->remove($sitemaps);
        }
        // write to file
        $file = fopen($sitemap, "x+");
        fwrite($file, $xml);
        fclose($file);
        // Save history
        $this->history();
    }

    public function history()
    {
        $row = Pi::model('history', 'sitemap')->find($this->name, 'file');
        if ($row) {
            $row->module = $this->module;
            $row->table = $this->table;
            $row->path = $this->path;
            $row->create = time();
            $row->save();
        } else {
            $values = array();
            $values['file'] = $this->name;
            $values['path'] = $this->path;
            $values['module'] = $this->module;
            $values['table'] = $this->table;
            $values['create'] = time();
            $row = Pi::model('history', 'sitemap')->createRow();
            $row->assign($values);
            $row->save();
        }
    }
}	