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
return [
    // Hide from front menu
    'front' => false,
    // Admin side
    'admin' => [
        'generat' => [
            'label'      => _a('Generate'),
            'route'      => 'admin',
            'module'     => 'sitemap',
            'controller' => 'index',
            'action'     => 'index',
            'pages'      => [
                'list' => [
                    'label'      => _a('Generate'),
                    'route'      => 'admin',
                    'module'     => 'sitemap',
                    'controller' => 'index',
                    'action'     => 'index',
                ],
                'add'  => [
                    'label'      => _a('Check health'),
                    'route'      => 'admin',
                    'module'     => 'sitemap',
                    'controller' => 'index',
                    'action'     => 'checkHealth',
                ],
            ],
        ],
        'top'     => [
            'label'      => _a('Top links'),
            'route'      => 'admin',
            'module'     => 'sitemap',
            'controller' => 'top',
            'action'     => 'index',
            'pages'      => [
                'list' => [
                    'label'      => _a('List'),
                    'route'      => 'admin',
                    'module'     => 'sitemap',
                    'controller' => 'top',
                    'action'     => 'index',
                ],
                'add'  => [
                    'label'      => _a('Add'),
                    'route'      => 'admin',
                    'module'     => 'sitemap',
                    'controller' => 'top',
                    'action'     => 'update',
                ],
                'import'  => [
                    'label'      => _a('Import'),
                    'route'      => 'admin',
                    'module'     => 'sitemap',
                    'controller' => 'top',
                    'action'     => 'import',
                ],
            ],
        ],
        'list'    => [
            'label'      => _a('List links'),
            'route'      => 'admin',
            'module'     => 'sitemap',
            'controller' => 'list',
            'action'     => 'index',
        ],
    ],
];