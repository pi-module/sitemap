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
     * @param string $module
     * @param string $table
     * @param int    $item
     * @param string $loc
     * @param int    $status
     */
    public function add($module, $table, $item, $loc, $status = 1)
    {
        $this->singleLink($loc, $status, $module, $table, $item);
    }

    /**
     * Old method , will remove
     * Update link to url table
     *
     * @param string $module
     * @param string $table
     * @param int    $item
     * @param string $loc
     * @param int    $status
     */
    public function update($module, $table, $item, $loc, $status = 1)
    {
        $this->singleLink($loc, $status, $module, $table, $item);
    }

    /**
     * Add or Update link to url table
     *
     * @param string $loc
     * @param int    $status
     * @param string $module
     * @param string $table
     * @param int    $item
     *
     * @return boolean
     */
    public function singleLink($loc, $status = 1, $module = '', $table = '', $item = 0)
    {
        // Check loc not empty
        if (empty($loc)) {
            return false;
        }
        // Check loc is valid
        $validator = new UriValidator;
        if (!$validator->isValid($loc)) {
            return false;
        }

        // Set changefreq and priority
        $changefreq = 'weekly';
        $priority   = '0.5';
        switch ($module) {
            case 'news' :
                switch ($table) {
                    case 'story';
                        $changefreq = 'daily';
                        $priority   = '0.7';
                        break;

                    case 'microblog';
                        $changefreq = 'daily';
                        $priority   = '0.4';
                        break;

                    case 'topic';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;

                    case 'author';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'guide' :
                switch ($table) {
                    case 'item';
                        $changefreq = 'daily';
                        $priority   = '0.8';
                        break;

                    case 'event';
                        $changefreq = 'daily';
                        $priority   = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;

                    case 'location';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'shop' :
                switch ($table) {
                    case 'product';
                        $changefreq = 'weekly';
                        $priority   = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'video' :
                switch ($table) {
                    case 'video';
                        $changefreq = 'weekly';
                        $priority   = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'page' :
                $changefreq = 'weekly';
                $priority   = '0.7';
                break;

            case 'portfolio' :
                $changefreq = 'weekly';
                $priority   = '0.3';
                break;

            case 'event' :
                $changefreq = 'daily';
                $priority   = '0.6';
                break;

            case 'blog' :
                $changefreq = 'daily';
                $priority   = '0.6';
                break;

            case 'gallery' :
                $changefreq = 'daily';
                $priority   = '0.6';
                break;
        }

        // Check item exist
        $row = Pi::model('url', 'sitemap')->find($loc, 'loc');

        // Check
        if (empty($row) || !is_object($row)) {
            if (!empty($module) && !empty($table) && $item > 0) {
                $limit  = 1;
                $where  = ['module' => $module, 'table' => $table, 'item' => $item];
                $select = Pi::model('url', 'sitemap')->select()->where($where)->limit($limit);
                $row    = Pi::model('url', 'sitemap')->selectWith($select)->current();
            }
        }

        // Check row exist or not
        if (!empty($row) && is_object($row)) {
            $row->loc        = $loc;
            $row->lastmod    = date("Y-m-d H:i:s");
            $row->status     = intval($status);
            $row->changefreq = $changefreq;
            $row->priority   = $priority;
            $row->save();
        } else {
            // Set values
            $values = [
                'loc'         => $loc,
                'lastmod'     => date("Y-m-d H:i:s"),
                'changefreq'  => $changefreq,
                'priority'    => $priority,
                'time_create' => time(),
                'module'      => $module,
                'table'       => $table,
                'item'        => intval($item),
                'status'      => intval($status),
            ];

            // Save
            $row = Pi::model('url', 'sitemap')->createRow();
            $row->assign($values);
            $row->save();
        }

        return true;
    }

    /**
     * Add group of links to url table whitout check is exist or not
     *
     * @param string $loc
     * @param int    $status
     * @param string $module
     * @param string $table
     * @param int    $item
     *
     * @return boolean
     */
    public function groupLink($loc, $status = 1, $module = '', $table = '', $item = 0)
    {
        // Check loc not empty
        if (empty($loc)) {
            return false;
        }

        // Check loc is valid
        $validator = new UriValidator;
        if (!$validator->isValid($loc)) {
            return false;
        }

        // Set changefreq and priority
        $changefreq = 'weekly';
        $priority   = '0.5';
        switch ($module) {
            case 'news' :
                switch ($table) {
                    case 'story';
                        $changefreq = 'daily';
                        $priority   = '0.7';
                        break;

                    case 'microblog';
                        $changefreq = 'daily';
                        $priority   = '0.4';
                        break;

                    case 'topic';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;

                    case 'author';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'guide' :
                switch ($table) {
                    case 'item';
                        $changefreq = 'daily';
                        $priority   = '0.8';
                        break;

                    case 'event';
                        $changefreq = 'daily';
                        $priority   = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;

                    case 'location';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'shop' :
                switch ($table) {
                    case 'product';
                        $changefreq = 'weekly';
                        $priority   = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'video' :
                switch ($table) {
                    case 'video';
                        $changefreq = 'weekly';
                        $priority   = '0.6';
                        break;

                    case 'category';
                        $changefreq = 'weekly';
                        $priority   = '0.3';
                        break;
                }
                break;

            case 'page' :
                $changefreq = 'weekly';
                $priority   = '0.7';
                break;

            case 'portfolio' :
                $changefreq = 'weekly';
                $priority   = '0.3';
                break;

            case 'event' :
                $changefreq = 'daily';
                $priority   = '0.6';
                break;

            case 'blog' :
                $changefreq = 'daily';
                $priority   = '0.6';
                break;

            case 'gallery' :
                $changefreq = 'daily';
                $priority   = '0.6';
                break;
        }

        // Set
        $values                = [];
        $values['loc']         = $loc;
        $values['lastmod']     = date("Y-m-d H:i:s");
        $values['changefreq']  = $changefreq;
        $values['priority']    = $priority;
        $values['time_create'] = time();
        $values['module']      = $module;
        $values['table']       = $table;
        $values['item']        = intval($item);
        $values['status']      = intval($status);

        // Save
        $row = Pi::model('url', 'sitemap')->createRow();
        $row->assign($values);
        $row->save();

        return true;
    }

    /**
     * Remove link from url table
     *
     * @param string $loc
     *
     * @return boolean
     */
    public function remove($loc)
    {
        // Check module
        if (empty($loc)) {
            return false;
        }

        // Remove
        $where = ['loc' => $loc];
        Pi::model('url', 'sitemap')->delete($where);
    }

    /**
     * Remove link from url table
     *
     * @param string $module
     * @param string $table
     *
     * @return boolean
     */
    public function removeAll($module, $table = '')
    {
        // Check module
        if (empty($module)) {
            return false;
        }

        // Check table
        if (empty($table)) {
            $where = ['module' => $module];
        } else {
            $where = ['module' => $module, 'table' => $table];
        }

        // Remove
        Pi::model('url', 'sitemap')->delete($where);
    }
}