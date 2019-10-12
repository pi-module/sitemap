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

namespace Module\Sitemap\Model;

use Pi\Application\Model\Model;

class Url extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'loc',
            'lastmod',
            'changefreq',
            'priority',
            'time_create',
            'module',
            'table',
            'item',
            'status',
            'top',
        ];
}