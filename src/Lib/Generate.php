<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Sitemap\Lib;

use Pi;

class Generate
{
    protected $name = 'sitemap.xml';

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get sitemap content
     *
     * @return array
     */
    public function content()
    {
        // Get config
        $config = Pi::service('registry')->config->read('sitemap', 'sitemap');

        // Set default content
        $content = [
            0 => [
                'uri'        => Pi::url('www'),
                'lastmod'    => date("Y-m-d H:i:s"),
                'changefreq' => 'daily',
                'priority'   => '1.0',
            ],
        ];

        // Set info
        $where = ['status' => 1];
        $order = ['priority DESC', 'top DESC', 'time_create DESC'];
        $limit = intval($config['sitemap_limit']);

        // Select list
        $select = Pi::model('url', 'sitemap')->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('url', 'sitemap')->selectWith($select);

        // Make content list
        foreach ($rowset as $row) {
            // Check elements
            $changefreq = (!empty($row->changefreq)) ? $row->changefreq : 'weekly';
            $priority   = (!empty($row->priority)) ? $row->priority : '0.5';

            // Set link information
            $link = [
                'uri'        => $row->loc,
                'lastmod'    => $row->lastmod,
                'changefreq' => $changefreq,
                'priority'   => $priority,
            ];

            // Set to content array
            $content[$row->id] = $link;
        }

        return $content;
    }

    /**
     * write on XML file
     *
     * @param array
     */
    public function write($xml)
    {
        // Make tmp path
        Pi::service('file')->mkdir(Pi::path('upload/sitemap'));
        
        // Set file path
        $sitemap = Pi::path(sprintf('upload/sitemap/%s', $this->name));
        
        // Remove old file
        if (Pi::service('file')->exists($sitemap)) {
            Pi::service('file')->remove($sitemap);
        }
        
        // write to file
        $file = fopen($sitemap, "x+");
        fwrite($file, $xml);
        fclose($file);
        
        // Save generate
        $this->canonizeGenerate();
    }

    /**
     * canonize generate table
     */
    public function canonizeGenerate()
    {
        $row = Pi::model('generate', 'sitemap')->find($this->name, 'file');
        if ($row) {
            $row->file        = $this->name;
            $row->time_update = time();
            $row->save();
        } else {
            $row              = Pi::model('generate', 'sitemap')->createRow();
            $row->file        = $this->name;
            $row->time_create = time();
            $row->time_update = time();
            $row->save();
        }
    }
}