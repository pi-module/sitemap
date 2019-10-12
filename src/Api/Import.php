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
 * Pi::api('import', 'sitemap')->generateLinks();
 */
class Import extends AbstractApi
{
    public function generateLinks()
    {
        $links = [];

        // ToDo : Add link list

        return $links;
    }
}