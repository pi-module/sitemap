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

namespace Module\Sitemap\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\Expression;

class ListController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $page = $this->params('page', 1);
        $link = [];

        // Set info
        $order  = ['id DESC', 'time_create DESC'];
        $limit  = intval($this->config('admin_perpage'));
        $offset = (int)($page - 1) * $this->config('admin_perpage');

        // Get info
        $select = $this->getModel('url')->select()->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('url')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $link[$row->id]                = $row->toArray();
            $link[$row->id]['time_create'] = _date($link[$row->id]['time_create']);
        }

        // Get count
        $count  = ['count' => new Expression('count(*)')];
        $select = $this->getModel('url')->select()->columns($count);
        $count  = $this->getModel('url')->selectWith($select)->current()->count;

        // Set paginator
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(
            [
                'router' => $this->getEvent()->getRouter(),
                'route'  => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
                'params' => array_filter(
                    [
                        'module'     => $this->getModule(),
                        'controller' => 'list',
                        'action'     => 'index',
                    ]
                ),
            ]
        );

        // Set view
        $this->view()->setTemplate('list-index');
        $this->view()->assign('links', $link);
        $this->view()->assign('paginator', $paginator);
    }

    public function topAddAction()
    {
        $id  = $this->params('id');
        $row = $this->getModel('url')->find($id);
        if ($row) {
            $row->top = 1;
            $row->save();

            // jump
            $this->jump(['action' => 'index'], __('This link add as top link'));
        } else {
            $this->jump(['action' => 'index'], __('Please select link'));
        }

        // Set view
        $this->view()->setTemplate(false);
    }

    public function deleteLinkAction()
    {
        $id  = $this->params('id');
        $row = $this->getModel('url')->find($id);
        if ($row) {
            $row->delete();
            $this->jump(['action' => 'index'], __('This link deleted'));
        } else {
            $this->jump(['action' => 'index'], __('Please select link'));
        }

        // Set view
        $this->view()->setTemplate(false);
    }

    public function deleteAllLinkAction()
    {
        $urlModel = $this->getModel('url');
        $urlModel->getAdapter()->query('TRUNCATE TABLE `' . $urlModel->getTable() . '`')->execute();

        $this->jump(['action' => 'index'], __('All links deleted'));
    }
}