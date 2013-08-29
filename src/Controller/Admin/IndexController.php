<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Sitemap\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class IndexController extends ActionController
{
    /**
     * Default action
     */
    public function indexAction()
    {
        // Set view
        $this->view()->setTemplate('index_index');
    }

    /**
     * Tools action
     */
    public function toolsAction()
    {
        // Set view
        $this->view()->setTemplate('index_tools');
    }

    /**
     * Top action
     */
    public function topAction()
    {
        // Set view
        $this->view()->setTemplate('index_top');
    }

    /**
     * Top update action
     */
    public function topupdateAction()
    {
        // Set view
        $this->view()->setTemplate('index_topadd');
    }

    /**
     * List action
     */
    public function listAction()
    {
        // Set view
        $this->view()->setTemplate('index_list');
    }

    /**
     * List update action
     */
    public function listupdateAction()
    {
        // Set view
        $this->view()->setTemplate('index_listadd');
    }
}