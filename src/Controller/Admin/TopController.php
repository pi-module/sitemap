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

namespace Module\Sitemap\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Module\Sitemap\Form\TopForm;
use Module\Sitemap\Form\TopFilter;
use Zend\Db\Sql\Predicate\Expression;

class TopController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $page = $this->params('page', 1);
        $link = null;

        // Set info
        $where  = ['top' => 1];
        $order  = ['id DESC', 'time_create DESC'];
        $limit  = intval($this->config('admin_perpage'));
        $offset = (int)($page - 1) * $this->config('admin_perpage');

        // Get info
        $select = $this->getModel('url')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('url')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $link[$row->id]                = $row->toArray();
            $link[$row->id]['time_create'] = _date($link[$row->id]['time_create']);
        }

        // Get count
        $count  = ['count' => new Expression('count(*)')];
        $select = $this->getModel('url')->select()->columns($count)->where($where);
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
                        'controller' => 'top',
                        'action'     => 'index',
                    ]
                ),
            ]
        );

        // Set view
        $this->view()->setTemplate('top-index');
        $this->view()->assign('links', $link);
        $this->view()->assign('paginator', $paginator);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');

        // Set form
        $form = new TopForm();
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new TopFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Add / update time
                $values['time_create'] = time();
                $values['top']         = 1;

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('url')->find($id);
                } else {
                    $row = $this->getModel('url')->createRow();
                }
                $row->assign($values);
                $row->save();

                // jump
                $message = __('Link saved successfully.');
                $url     = ['action' => 'index'];
                $this->jump($url, $message);
            }
        } else {
            if ($id) {
                $values = $this->getModel('url')->find($id)->toArray();
                $form->setData($values);
            }
        }

        // Set view
        $this->view()->setTemplate('top-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a link'));
    }

    public function importAction()
    {
        $linkList = Pi::api('import', 'sitemap')->generateLinks();
        foreach ($linkList as $linkSingle) {
            Pi::api('sitemap', 'sitemap')->singleLink($linkSingle['loc'], $linkSingle['status'], $linkSingle['module']);
        }

        // Set view
        $this->view()->setTemplate('top-import');
        $this->view()->assign('linkList', $linkList);
    }
}