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
use Pi\Form\Form as BaseForm;

/**
 * Top link form
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
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
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // loc
        $this->add(array(
            'name' => 'loc',
            'options' => array(
                'label' => __('URL'),
            ),
            'attributes' => array(
                'type' => 'text',
            )
        ));
        // lastmod
        $this->add(array(
            'name' => 'lastmod',
            'options' => array(
                'label' => __('Last modification'),
            ),
            'attributes' => array(
                'type' => 'text',
                'value' => date("Y-m-d H:i:s"),
            )
        ));
        // changefreq
        $this->add(array(
            'name' => 'changefreq',
            'type' => 'select',
            'options' => array(
                'label' => __('Change frequency'),
                'value_options' => array(
                    'always' => __('Always'),
                    'hourly' => __('Hourly'),
                    'daily' => __('Daily'),
                    'weekly' => __('Weekly'),
                    'monthly' => __('Monthly'),
                    'yearly' => __('Yearly'),
                    'never' => __('Never'),
                ),
            ),
            'attributes' => array(
                'value' => 'daily',
            ),
        ));
        // priority
        $this->add(array(
            'name' => 'priority',
            'options' => array(
                'label' => __('priority (optional)'),
            ),
            'attributes' => array(
                'type' => 'text',
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }	
}	