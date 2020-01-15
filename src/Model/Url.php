<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
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