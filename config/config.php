<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * Module meta
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return array(
    'category' => array(
        array(
            'title' => _t('Admin'),
            'name' => 'admin'
        ),
        array(
            'title' => _t('Sitemap'),
            'name' => 'sitemap'
        ),
    ),
    'item' => array(
        // Admin
        'admin_perpage' => array(
            'category' => 'admin',
            'title' => _t('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 250
        ),
        // Sitemap
        'sitemap_limit' => array(
            'category' => 'sitemap',
            'title' => _t('Sitemap limit'),
            'description' => _t('Limited link in each file'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1000
        ),
        'sitemap_location' => array(
            'category' => 'sitemap',
            'title' => _t('Sitemap location'),
            'description' => _t('Set empty for add sitemaps on website root, or add pach like : upload/sitemap'),
            'edit' => 'text',
            'filter' => 'string',
            'value' => ''
        ),
    ),
);