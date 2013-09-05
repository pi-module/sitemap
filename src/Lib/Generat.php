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

    public function write($content)
    {
        $sitemap = Pi::path($this->name);
        // Remove old file
        if (file_exists($sitemap)) {
            if (!@unlink($sitemap)) {
                $message = sprintf(__('Unable to remove %s , please remove file manual and generat again'), $sitemap);
                throw new \Exception($message);
            }
        }
        // write to file
        $file = fopen($sitemap, "x+");
        fwrite($file, $content);
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