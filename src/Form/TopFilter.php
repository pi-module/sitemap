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

namespace Module\Sitemap\Form;

use Pi;
use Zend\InputFilter\InputFilter;

class TopFilter extends InputFilter
{
    public function __construct()
    {
        // id
        $this->add(
            [
                'name'     => 'id',
                'required' => false,
            ]
        );
        // loc
        $this->add(
            [
                'name'     => 'loc',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // lastmod
        $this->add(
            [
                'name'     => 'lastmod',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // changefreq
        $this->add(
            [
                'name'     => 'changefreq',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // priority
        $this->add(
            [
                'name'     => 'priority',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => true,
            ]
        );
    }
}	