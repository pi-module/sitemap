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
use Module\Sitemap\Lib\Generate;
use Zend\Db\Sql\Predicate\Expression;

class IndexController extends ActionController
{
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

    public function copyFileAction()
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

    public function deleteFileAction()
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

    public function checkHealthAction()
    {
        // Clean params
        $params = [];
        foreach ($_GET as $key => $value) {
            $params[$key] = $value;
        }

        // Get info from url
        $params['module']  = $this->params('module');
        $params['page']    = $this->params('page', 1);
        $params['limit']   = $this->params('page', 50);
        $params['count']   = $this->params('count', 0);
        $params['percent'] = 0;

        // Set info
        $order  = ['id ASC'];
        $offset = (int)($params['page'] - 1) * $params['limit'];
        $where  = ['item > ?' => 0];

        // Get info
        $select = $this->getModel('url')->select()->where($where)->order($order)->offset($offset)->limit($params['limit']);
        $rowset = $this->getModel('url')->selectWith($select);

        // Get count
        if ($params['count'] == 0) {
            $count           = ['count' => new Expression('count(*)')];
            $select          = $this->getModel('url')->select()->columns($count)->where($where);
            $params['count'] = $this->getModel('url')->selectWith($select)->current()->count;
        }

        // Check and update health
        foreach ($rowset as $row) {
            if (Pi::service('module')->isActive($row->module)) {
                $urlRow = Pi::model($row->table, $row->module)->find($row->item);
                if ($urlRow) {
                    if ($urlRow->status != $row->status) {
                        $row->status = $urlRow->status;
                        $row->save();
                    }
                } else {
                    if ($row->status != 0) {
                        $row->status = 0;
                        $row->save();
                    }
                }
            } else {
                if ($row->status != 0) {
                    $row->status = 0;
                    $row->save();
                }
            }
        }

        // Set page
        $params['lastPage'] = intval($params['count'] / $params['limit']) + 1;
        if ($params['lastPage'] == $params['page']) {
            $nextUrl           = '';
            $params['percent'] = 100;
        } else {
            $params['percent'] = (100 * $params['page']) / $params['lastPage'];
            $params['page']    = $params['page'] + 1;
            $nextUrl           = Pi::url(
                    $this->url(
                        '', [
                            'controller' => 'index',
                            'action'     => 'checkHealth',
                        ]
                    )
                ) . '?' . http_build_query($params);
        }

        // Set view
        $this->view()->setTemplate('check-health');
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('params', $params);
    }
}