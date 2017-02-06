<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt BSD 3-Clause License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Sitemap\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Validator\Uri as UriValidator;

/**
 * Pi::api('sitemap', 'sitemap')->singleLink($loc, $status, $module, $table, $item);
 * Pi::api('sitemap', 'sitemap')->groupLink($loc, $status, $module, $table, $item);
 * Pi::api('sitemap', 'sitemap')->remove($loc);
 * Pi::api('sitemap', 'sitemap')->removeAll($module, $table);
 */
class Sitemap extends AbstractApi
{
    /**
     * Old method , will remove
     * Add new link to url table
     *
     * @param  string $module
     * @param  string $table
     * @param  int $item
     * @param  string $loc
     */
    public function add($module, $table, $item, $loc, $status = 1)
    {
        $this->singleLink($loc, $status, $module, $table, $item);
    }

    /**
     * Old method , will remove
     * Update link to url table
     *
     * @param  string $module
     * @param  string $table
     * @param  int $item
     * @param  string $loc
     * @param  int $status
     */
    public function update($module, $table, $item, $loc, $status = 1)
    {
        $this->singleLink($loc, $status, $module, $table, $item);
    }

    /**
     * Add or Update link to url table
     *
     * @param  string $loc
     * @param  int $status
     * @param  string $module
     * @param  string $table
     * @param  int $item
     */
    public function singleLink($loc, $status = 1, $module = '', $table = '', $item = 0)
    {
        // Check loc not empty
        if (empty($loc)) {
            return '';
        }
        // Check loc is valid
        $validator = new UriValidator;
        if (!$validator->isValid($loc)) {
            return '';
        }

        // Set changefreq and priority
        $changefreq = 'weekly';
        $priority = '0.5';
        switch ($module) {
            case 'news' :
                switch ($table) {
                    case 'story';
                        $changefreq = 'daily';
                        $priority = '0.7';
                        break;

                    case 'microblog';
                        $changefreq = 'daily';
                        $priority = '0.4';
                        break;

                    case 'topic';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;

                    case 'author';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;
                }
                break;

            case 'guide' :
                switch ($table) {
                    case 'item';
                        $changefreq = 'daily';
                        $priority = '0.8';
                        break;

                    case 'event';
                        $changefreq = 'daily';
                        $priority = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;

                    case 'location';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;
                }
                break;

            case 'shop' :
                switch ($table) {
                    case 'product';
                        $changefreq = 'weekly';
                        $priority = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;
                }
                break;

            case 'page' :
                $changefreq = 'weekly';
                $priority = '0.3';
                break;

            case 'portfolio' :
                $changefreq = 'weekly';
                $priority = '0.3';
                break;
        }

        // Check loc exist or not
        $row = Pi::model('url', 'sitemap')->find($loc, 'loc');
        if (!empty($row) && is_object($row)) {
            $row->loc = $loc;
            $row->lastmod = date("Y-m-d H:i:s");
            $row->status = intval($status);
            $row->changefreq = $changefreq;
            $row->priority = $priority;
            $row->save();
        } else {
            // Set
            $values = array();
            $values['loc'] = $loc;
            $values['lastmod'] = date("Y-m-d H:i:s");
            $values['changefreq'] = $changefreq;
            $values['priority'] = $priority;
            $values['time_create'] = time();
            $values['module'] = $module;
            $values['table'] = $table;
            $values['item'] = intval($item);
            $values['status'] = intval($status);
            // Save
            $row = Pi::model('url', 'sitemap')->createRow();
            $row->assign($values);
            $row->save();
        }
    }

    /**
     * Add group of links to url table whitout check is exist or not
     *
     * @param  string $loc
     * @param  int $status
     * @param  string $module
     * @param  string $table
     * @param  int $item
     */
    public function groupLink($loc, $status = 1, $module = '', $table = '', $item = 0)
    {
        // Check loc not empty
        if (empty($loc)) {
            return '';
        }
        // Check loc is valid
        $validator = new UriValidator;
        if (!$validator->isValid($loc)) {
            return '';
        }

        // Set changefreq and priority
        $changefreq = 'weekly';
        $priority = '0.5';
        switch ($module) {
            case 'news' :
                switch ($table) {
                    case 'story';
                        $changefreq = 'daily';
                        $priority = '0.7';
                        break;

                    case 'microblog';
                        $changefreq = 'daily';
                        $priority = '0.4';
                        break;

                    case 'topic';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;

                    case 'author';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;
                }
                break;

            case 'guide' :
                switch ($table) {
                    case 'item';
                        $changefreq = 'daily';
                        $priority = '0.8';
                        break;

                    case 'event';
                        $changefreq = 'daily';
                        $priority = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;

                    case 'location';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;
                }
                break;

            case 'shop' :
                switch ($table) {
                    case 'product';
                        $changefreq = 'weekly';
                        $priority = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority = '0.3';
                        break;
                }
                break;

            case 'page' :
                $changefreq = 'weekly';
                $priority = '0.3';
                break;

            case 'portfolio' :
                $changefreq = 'weekly';
                $priority = '0.3';
                break;

            case 'event' :
                $changefreq = 'daily';
                $priority = '0.6';
                break;

            case 'blog' :
                $changefreq = 'daily';
                $priority = '0.6';
                break;

            case 'gallery' :
                $changefreq = 'daily';
                $priority = '0.6';
                break;
        }

        // Set
        $values = array();
        $values['loc'] = $loc;
        $values['lastmod'] = date("Y-m-d H:i:s");
        $values['changefreq'] = $changefreq;
        $values['priority'] = $priority;
        $values['time_create'] = time();
        $values['module'] = $module;
        $values['table'] = $table;
        $values['item'] = intval($item);
        $values['status'] = intval($status);
        // Save
        $row = Pi::model('url', 'sitemap')->createRow();
        $row->assign($values);
        $row->save();
    }

    /**
     * Remove link from url table
     */
    public function remove($loc)
    {
        // Check module
        if (empty($loc)) {
            return '';
        }
        // Remove
        $where = array('loc' => $loc);
        Pi::model('url', 'sitemap')->delete($where);
    }

    /**
     * Remove link from url table
     */
    public function removeAll($module, $table = '')
    {
        // Check module
        if (empty($module)) {
            return '';
        }
        // Check table
        if (empty($table)) {
            $where = array('module' => $module);
        } else {
            $where = array('module' => $module, 'table' => $table);
        }
        // Remove
        Pi::model('url', 'sitemap')->delete($where);
    }
}