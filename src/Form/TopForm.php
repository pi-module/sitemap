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
use Pi\Form\Form as BaseForm;

class TopForm extends BaseForm
{

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new TopFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // loc
        $this->add(
            [
                'name'       => 'loc',
                'options'    => [
                    'label' => __('URL'),
                ],
                'attributes' => [
                    'type'     => 'text',
                    'required' => true,
                ],
            ]
        );

        // lastmod
        $this->add(
            [
                'name'       => 'lastmod',
                'options'    => [
                    'label' => __('Last modification'),
                ],
                'attributes' => [
                    'type'     => 'text',
                    'value'    => date("Y-m-d H:i:s"),
                    'required' => true,
                ],
            ]
        );

        // changefreq
        $this->add(
            [
                'name'       => 'changefreq',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Change frequency'),
                    'value_options' => [
                        'always'  => __('Always'),
                        'hourly'  => __('Hourly'),
                        'daily'   => __('Daily'),
                        'weekly'  => __('Weekly'),
                        'monthly' => __('Monthly'),
                        'yearly'  => __('Yearly'),
                        'never'   => __('Never'),
                    ],
                ],
                'attributes' => [
                    'value'    => 'daily',
                    'required' => true,
                ],
            ]
        );

        // priority
        $this->add(
            [
                'name'       => 'priority',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Change frequency'),
                    'value_options' => [
                        '1.0' => '1.0',
                        '0.9' => '0.9',
                        '0.8' => '0.8',
                        '0.7' => '0.7',
                        '0.6' => '0.6',
                        '0.5' => '0.5',
                        '0.4' => '0.4',
                        '0.3' => '0.3',
                        '0.2' => '0.2',
                        '0.1' => '0.1',
                    ],
                ],
                'attributes' => [
                    'value'    => '0.5',
                    'required' => true,
                ],
            ]
        );

        // status
        $this->add(
            [
                'name'       => 'status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}	