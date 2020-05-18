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

namespace Module\Sitemap\Form;

use Pi;
use Laminas\InputFilter\InputFilter;

class TopFilter extends InputFilter
{
    public function __construct()
    {
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