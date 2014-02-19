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
return array(
    'admin' => array(
        'generat' => array(
            'label'         => __('Generate'),
            'route'         => 'admin',
            'module'        => 'sitemap',
            'controller'    => 'index',
            'action'        => 'index',
        ),
        'top' => array(
            'label'         => __('Top links'),
            'route'         => 'admin',
            'module'        => 'sitemap',
            'controller'    => 'index',
            'action'        => 'top',
        ),
        'list' => array(
            'label'         => __('List links'),
            'route'         => 'admin',
            'module'        => 'sitemap',
            'controller'    => 'index',
            'action'        => 'list',
        ),
    ),
);