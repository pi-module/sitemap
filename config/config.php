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
return [
    'category' => [
        [
            'title' => _a('Admin'),
            'name'  => 'admin',
        ],
        [
            'title' => _a('Sitemap'),
            'name'  => 'sitemap',
        ],
        [
            'title' => _a('Cron'),
            'name'  => 'cron',
        ],
    ],
    'item'     => [
        // Admin
        'admin_perpage' => [
            'category'    => 'admin',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 250,
        ],
        // Sitemap
        'sitemap_limit' => [
            'category'    => 'sitemap',
            'title'       => _a('Sitemap limit'),
            'description' => _a('Limited link in each file'),
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 5000,
        ],
        // Cron
        'module_cron'   => [
            'category'    => 'cron',
            'title'       => _a('Active this module cron system'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
    ],
];