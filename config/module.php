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
    'meta'  => array(
        'title'         => __('Sitemap'),
        'description'   => __('For generate sitemap.xml.'),
        'version'       => '1.0.1',
        'license'       => 'New BSD',
        'logo'          => 'image/logo.png',
        'readme'        => 'docs/readme.txt',
        'demo'          => 'http://pialog',
        'icon'          => 'fa fa-sitemap',
    ),
    // Author information
    'author'    => array(
        'dev'       => 'Hossein Azizabadi',
        'email'     => 'azizabadi@faragostaresh.com',
        'architect' => '@voltan',
        'design'    => '@voltan'
    ),
    // Resource
    'resource' => array(
        'database'      => 'database.php',
        'config'        => 'config.php',
        'navigation'    => 'navigation.php',
    ),
);