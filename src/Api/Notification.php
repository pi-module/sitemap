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
namespace Module\Sitemap\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Module\Sitemap\Lib\Generate;

/*
 * Pi::api('notification', 'Sitemap')->doCron();
 */

class Notification extends AbstractApi
{
    public function doCron()
    {
        // Set log
        Pi::service('audit')->log('cron', 'sitemap - Start cron on server');
        // Set info
        $file = 'sitemap.xml';
        // Remove old files if exists
        $fileRoot = Pi::path($file);
        $fileMain = Pi::path(sprintf('upload/sitemap/%s', $file));
        // remove fileRoot
        if (Pi::service('file')->exists($fileRoot)) {
            Pi::service('file')->remove($fileRoot);
        }
        // Set log
        Pi::service('audit')->log('cron', sprintf('sitemap - remove %s', $fileRoot));
        // remove fileMain
        if (Pi::service('file')->exists($fileMain)) {
            Pi::service('file')->remove($fileMain);
        }
        // Set log
        Pi::service('audit')->log('cron', sprintf('sitemap - remove %s', $fileMain));
        // Generat sitemap
        $generate = new Generate($file);
        $navigation = Pi::service('view')->getHelper('navigation');
        $sitemap = $navigation($generate->content())->sitemap();
        $sitemap = $sitemap->setFormatOutput(true)->render();
        $generate->write($sitemap);
        // Set log
        Pi::service('audit')->log('cron', 'sitemap - generate new sitemap');
        // Copy
        Pi::service('file')->copy($fileMain, $fileRoot, true);
        // Set log
        Pi::service('audit')->log('cron', sprintf('sitemap - copy %s to %s', $fileMain, $fileRoot));
        // Set log
        Pi::service('audit')->log('cron', 'sitemap - End cron on server');
    }
}