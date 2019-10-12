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
use Module\Sitemap\Lib\Generate;

class IndexController extends ActionController
{
    /**
     * Default action
     */
    public function indexAction()
    {
        // Get info
        $select = $this->getModel('generate')->select()->order(['time_update DESC']);
        $rowset = $this->getModel('generate')->selectWith($select);

        // Make list
        foreach ($rowset as $row) {
            $generate[$row->id]                     = $row->toArray();
            $generate[$row->id]['time_create']      = _date($generate[$row->id]['time_create']);
            $generate[$row->id]['time_update']      = ($generate[$row->id]['time_update']) ? _date($generate[$row->id]['time_update']) : __('Never');
            $generate[$row->id]['file_url']         = Pi::url($generate[$row->id]['file']);
            $generate[$row->id]['file_path']        = Pi::path($generate[$row->id]['file']);
            $generate[$row->id]['file_exists']      = (Pi::service('file')->exists($generate[$row->id]['file'])) ? 1 : 0;
            $generate[$row->id]['file_main_url']    = Pi::url(sprintf('upload/sitemap/%s', $generate[$row->id]['file']));
            $generate[$row->id]['file_main_path']   = Pi::path(sprintf('upload/sitemap/%s', $generate[$row->id]['file']));
            $generate[$row->id]['file_main_exists'] = (Pi::service('file')->exists(sprintf('upload/sitemap/%s', $generate[$row->id]['file']))) ? 1 : 0;
            $generate[$row->id]['generate_link']    = $this->url(
                '', [
                    'module' => 'sitemap',
                    'action' => 'generate',
                    'file'   => $generate[$row->id]['file'],
                ]
            );
        }

        // Set sitemap.xml if not exist
        if (empty($generate)) {
            $generate[0]['file']             = 'sitemap.xml';
            $generate[0]['time_create']      = _date(time());
            $generate[0]['file_url']         = Pi::url('sitemap.xml');
            $generate[0]['file_path']        = Pi::path('sitemap.xml');
            $generate[0]['file_exists']      = (Pi::service('file')->exists('sitemap.xml')) ? 1 : 0;
            $generate[0]['file_main_url']    = Pi::url('upload/sitemap/sitemap.xml');
            $generate[0]['file_main_path']   = Pi::path('upload/sitemap/sitemap.xml');
            $generate[0]['file_main_exists'] = (Pi::service('file')->exists('upload/sitemap/sitemap.xml')) ? 1 : 0;
            $generate[0]['generate_link']    = $this->url(
                '', [
                    'module' => 'sitemap',
                    'action' => 'generate',
                    'file'   => 'sitemap.xml',
                ]
            );
        }

        // Set view
        $this->view()->setTemplate('index-index');
        $this->view()->assign('generate', $generate);
    }

    /**
     * Top action
     */
    public function topAction()
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

        // Set paginator
        $count     = ['count' => new \Zend\Db\Sql\Predicate\Expression('count(*)')];
        $select    = $this->getModel('url')->select()->columns($count)->where($where);
        $count     = $this->getModel('url')->selectWith($select)->current()->count;
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
                        'controller' => 'index',
                        'action'     => 'top',
                    ]
                ),
            ]
        );

        // Set view
        $this->view()->setTemplate('index-top');
        $this->view()->assign('links', $link);
        $this->view()->assign('paginator', $paginator);
    }

    /**
     * List action
     */
    public function listAction()
    {
        // Get info
        $page   = $this->params('page', 1);
        $link   = [];

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

        // Set paginator
        $count     = ['count' => new \Zend\Db\Sql\Predicate\Expression('count(*)')];
        $select    = $this->getModel('url')->select()->columns($count);
        $count     = $this->getModel('url')->selectWith($select)->current()->count;
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
                        'controller' => 'index',
                        'action'     => 'list',
                    ]
                ),
            ]
        );

        // Set view
        $this->view()->setTemplate('index-list');
        $this->view()->assign('links', $link);
        $this->view()->assign('paginator', $paginator);
    }

    /**
     * Top update action
     */
    public function updateAction()
    {
        // Get id
        $id     = $this->params('id');

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
                if (!empty($values['id'])) {
                    $row = $this->getModel('url')->find($values['id']);
                } else {
                    $row = $this->getModel('url')->createRow();
                }
                $row->assign($values);
                $row->save();

                // jump
                $message = __('Link saved successfully.');
                $url     = ['action' => 'top'];
                $this->jump($url, $message);
            }
        } else {
            if ($id) {
                $values = $this->getModel('url')->find($id)->toArray();
                $form->setData($values);
            }
        }

        // Set view
        $this->view()->setTemplate('index-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a link'));
    }

    /**
     * Generate sitemap.xml
     */
    public function generateAction()
    {
        $file = $this->params('file', 'sitemap.xml');

        // Remove old files if exists
        $fileRoot = Pi::path($file);
        $fileMain = Pi::path(sprintf('upload/sitemap/%s', $file));

        // remove fileRoot
        if (Pi::service('file')->exists($fileRoot)) {
            Pi::service('file')->remove($fileRoot);
        }

        // remove fileMain
        if (Pi::service('file')->exists($fileMain)) {
            Pi::service('file')->remove($fileMain);
        }

        // Generate sitemap
        $generate = new Generate($file);
        $sitemap  = $this->view()->navigation($generate->content())->sitemap();
        $sitemap  = $sitemap->setFormatOutput(true)->render();
        $generate->write($sitemap);

        // Set copy URL
        $url = $this->url(
            '', [
                'action' => 'copyfile',
                'file'   => $file,
            ]
        );

        // Set view
        $this->view()->setTemplate('file-generate');
        $this->view()->assign('url', $url);
    }

    /**
     * Copy XML file to website root
     */
    public function copyfileAction()
    {
        $file = $this->params('file');
        if (!$file) {
            $this->jump(['action' => 'index'], __('Please selete file'));
        } else {
            $fileRoot = Pi::path($file);
            $fileMain = Pi::path(sprintf('upload/sitemap/%s', $file));
            if (Pi::service('file')->exists($fileRoot)) {
                $this->jump(['action' => 'index'], __('Your origin file path is website root'));
            } else {
                Pi::service('file')->copy($fileMain, $fileRoot, true);
            }
        }

        // Set copy URL
        $url = $this->url(
            '', [
                'action' => 'index',
            ]
        );

        // Set view
        $this->view()->setTemplate('file-copy');
        $this->view()->assign('url', $url);
    }

    /**
     * Delete XML file
     */
    public function deletefileAction()
    {
        $file = $this->params('file');
        if ($file) {
            $fileRoot = Pi::path($file);
            $fileMain = Pi::path(sprintf('upload/sitemap/%s', $file));

            // remove fileRoot
            if (Pi::service('file')->exists($fileRoot)) {
                Pi::service('file')->remove($fileRoot);
            }

            // remove fileMain
            if (Pi::service('file')->exists($fileMain)) {
                Pi::service('file')->remove($fileMain);
            }

            $this->jump(['action' => 'index'], __('Selected file delete'));
        } else {
            $this->jump(['action' => 'index'], __('Please selete file'));
        }

        // Set view
        $this->view()->setTemplate(false);
    }

    /**
     * Delete XML build method
     */
    public function deleteAction()
    {
        $file = $this->params('file');
        if ($file == 'sitemap.xml') {
            $this->jump(['action' => 'index'], __('You can not delete sitemap.xml build method'));
        } else {
            $row = $this->getModel('generate')->find($file, 'file');
            if ($row) {
                $row->delete();
                $this->jump(['action' => 'index'], __('This sitemap method deleted'));
            } else {
                $this->jump(['action' => 'index'], __('Please select sitemap method'));
            }
        }

        // Set view
        $this->view()->setTemplate(false);
    }

    /**
     * Add link as top
     */
    public function topaddAction()
    {
        $id  = $this->params('id');
        $row = $this->getModel('url')->find($id);
        if ($row) {
            $row->top = 1;
            $row->save();

            // jump
            $this->jump(['action' => 'list'], __('This link add as top link'));
        } else {
            $this->jump(['action' => 'list'], __('Please select link'));
        }

        // Set view
        $this->view()->setTemplate(false);
    }

    /**
     * delete link action
     */
    public function deleteLinkAction()
    {
        $id  = $this->params('id');
        $row = $this->getModel('url')->find($id);
        if ($row) {
            $row->delete();
            $this->jump(['action' => 'list'], __('This link deleted'));
        } else {
            $this->jump(['action' => 'list'], __('Please select link'));
        }

        // Set view
        $this->view()->setTemplate(false);
    }

    /**
     * delete all link action
     */
    public function deleteAllLinkAction()
    {
        $urlModel = $this->getModel('url');
        $urlModel->getAdapter()->query('TRUNCATE TABLE `' . $urlModel->getTable() . '`')->execute();

        $this->jump(['action' => 'list'], __('All links deleted'));
    }
}