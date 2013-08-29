<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Sitemap\Form;

use Pi;
use Zend\InputFilter\InputFilter;

/**
 * Top link form filter
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class TopFilter extends InputFilter
{
    public function __construct($options = array())
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // loc
        $this->add(array(
            'name' => 'loc',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // lastmod
        $this->add(array(
            'name' => 'lastmod',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // changefreq
        $this->add(array(
            'name' => 'changefreq',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // priority
        $this->add(array(
            'name' => 'priority',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
    }	
}	