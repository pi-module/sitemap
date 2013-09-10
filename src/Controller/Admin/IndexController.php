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
use Module\Sitemap\Form\TopForm;
use Module\Sitemap\Form\TopFilter;
use Module\Sitemap\Lib\Generat;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
class IndexController extends ActionController
{
    protected $listColumns = array( 'id', 'loc', 'lastmod', 'changefreq', 'priority', 'create', 'module', 'table', 'status');
    protected $topColumns = array('id', 'loc', 'lastmod', 'changefreq', 'priority', 'create', 'order');

    /**
     * Default action
     */
    public function indexAction()
    {
        // Get info
        $select = $this->getModel('history')->select()->order(array('id DESC', 'create DESC'));
        $rowset = $this->getModel('history')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $history[$row->id] = $row->toArray();
            // Check last sitemap file path
            if (empty($history[$row->id]['path'])) {
                $file = $history[$row->id]['file'];
            } else {
                $file = sprintf('%s/%s', $history[$row->id]['path'], $history[$row->id]['file']);
                $fileRoot = $history[$row->id]['file'];
            }
            // Set array
            $history[$row->id]['file_create'] = _date($history[$row->id]['create']);
            $history[$row->id]['file_url'] = Pi::url($file);
            $history[$row->id]['file_path'] = Pi::path($file);
            $history[$row->id]['file_root_url'] = (isset($fileRoot)) ?  Pi::url($fileRoot) : '';
            $history[$row->id]['file_root_path'] = (isset($fileRoot)) ?  Pi::path($fileRoot) : '';
            $history[$row->id]['exists'] = (Pi::service('file')->exists($file)) ? 1 : 0;
            $history[$row->id]['exists_root'] = (Pi::service('file')->exists($fileRoot)) ? 1 : 0;
            $history[$row->id]['update'] = ($history[$row->id]['create'] > (intval(time() - 86400))) ? 1 : 0;
            // Set generat link
            $generat = array();
            $generat['action'] = 'generat';
            $generat['select-file'] = $history[$row->id]['file'];
            if (!empty($history[$row->id]['module']) && !empty($history[$row->id]['table'])) {
                $generat['select-module'] = $history[$row->id]['module'];
                $generat['select-table'] = $history[$row->id]['table'];
            }
            $history[$row->id]['generat'] = $this->url('', $generat);
        }

        if (empty($history)) {
            $history[0]['file'] = 'sitemap.xml';
            $history[0]['file_create'] = _date(time());
            $history[0]['file_url'] = Pi::url('sitemap.xml');
            $history[0]['file_path'] = Pi::path('sitemap.xml');
            $history[0]['exists'] = 0;
            $history[0]['update'] = 0;
            $history[0]['generat'] = $this->url('', array('action' => 'generat', 'select-file' => 'sitemap.xml'));
        }

        // Get info
        $select = $this->getModel('item')->select()->order(array('id DESC', 'count DESC'));
        $rowset = $this->getModel('item')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $item[$row->id] = $row->toArray();
            $item[$row->id]['file'] = sprintf('%s-%s-sitemap.xml', $item[$row->id]['module'], $item[$row->id]['table']);
            $item[$row->id]['generat'] = $this->url('', array(
                'action' => 'generat', 
                'select-file' => $item[$row->id]['file'], 
                'select-module' => $item[$row->id]['module'], 
                'select-table' => $item[$row->id]['table'])
            );
            $exists = (Pi::service('file')->exists(Pi::path($item[$row->id]['file']))) ? 1 : 0;
            // unset exists files
            if ($exists) {
                unset($item[$row->id]);
            }
        }

        // Set view
        $this->view()->setTemplate('index_index');
        $this->view()->assign('historys', $history);
        $this->view()->assign('items', $item);
    }

    public function generatAction()
    {
        $file = $this->params('select-file', 'sitemap.xml');
        $module = $this->params('select-module', '');
        $table = $this->params('select-table', '');
        // Set table where
        if (!empty($module) && !empty($table)) {
            $setindex = false;
            $settop = false;
        } else {
            $setindex = true;
            $settop = true;
        }
        // Generat sitemap
        $generat = new Generat($file, $module, $table, $setindex, $settop);
        $sitemap = $this->view()->navigation($generat->content())->sitemap();
        $sitemap = $sitemap->setFormatOutput(true)->render();
        $generat->write($sitemap);
        // Set view
        $this->view()->setTemplate(false);
        $this->jump(array('action' => 'index'), __('working ... '));
    }  

    public function deletefileAction()
    {
        $this->view()->setTemplate(false);
        $file = $this->params('file');
        if ($file) {
            // Set file path
            $path = trim($this->config('sitemap_location'), '/');
            if (empty($path)) {
                $file = Pi::path($file);
                // remove file
                if (Pi::service('file')->exists($file)) {
                    Pi::service('file')->remove($file);
                }
            } else {
                $file = Pi::path(sprintf('%s/%s', $path, $file));
                // remove file
                if (Pi::service('file')->exists($file)) {
                    Pi::service('file')->remove($file);
                }
                // remove file
                $file = Pi::path($file);
                if (Pi::service('file')->exists($file)) {
                    Pi::service('file')->remove($file);
                }
            }    
            $this->jump(array('action' => 'index'), __('Selected file delete')); 
        } else {
            $this->jump(array('action' => 'index'), __('Please selete file')); 
        }
    } 

    public function copyfileAction()
    {
        $this->view()->setTemplate(false);
        $file = $this->params('file');
        if ($file) {
            // Set file path
            $path = trim($this->config('sitemap_location'), '/');
            if (!empty($path)) {
                $originFile = Pi::path(sprintf('%s/%s', $path, $file));
                $targetFile = Pi::path($file);
                Pi::service('file')->copy($originFile, $targetFile, true);
                $this->jump(array('action' => 'index'), __('Selected file copy to root'));
            } else {
                $this->jump(array('action' => 'index'), __('Your origin file path is website root')); 
            }
        } else {
            $this->jump(array('action' => 'index'), __('Please selete file')); 
        }
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
        // Get info
        $module = $this->params('module');
        $page = $this->params('p', 1);
        // Get info
        $select = $this->getModel('url_top')->select()->order(array('id DESC', 'create DESC'));
        $rowset = $this->getModel('url_top')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $link[$row->id] = $row->toArray();
            $link[$row->id]['create'] = _date($link[$row->id]['create']);
        }
        // Go to update page if empty
        if (empty($link)) {
            return $this->redirect()->toRoute('', array('action' => 'topupdate'));
        }
        // Set paginator
        $paginator = \Pi\Paginator\Paginator::factory($link);
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            // Use router to build URL for each page
            'pageParam' => 'p',
            'totalParam' => 't',
            'router' => $this->getEvent()->getRouter(),
            'route' => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params' => array(
                'module' => $this->getModule(),
                'controller' => 'index',
                'action' => 'top',
            ),
        ));
        // Set view
        $this->view()->setTemplate('index_top');
        $this->view()->assign('links', $paginator);
    }

    /**
     * Top update action
     */
    public function topupdateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Set form
        $form = new TopForm();
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new TopFilter());
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just story fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->topColumns)) {
                        unset($values[$key]);
                    }
                }
                // Add / update time 
                $values['create'] = time();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('url_top')->find($values['id']);
                } else {
                    $row = $this->getModel('url_top')->createRow();
                }
                $row->assign($values);
                $row->save();
                // jump
                $message = __('Link saved successfully.');
                $url = array('action' => 'top');
                $this->jump($url, $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
                $values = $this->getModel('url_top')->find($id)->toArray();
                $form->setData($values);
                $message = 'You can edit this link';
            } else {
                $message = 'You can add new link';
            }
        }
        // Set view
        $this->view()->setTemplate('index_topadd');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a link'));
        $this->view()->assign('message', $message);	
    }
    
    /**
     * Top update action
     */
    public function topdeleteAction()
    {
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('url_top')->find($id);
        if ($row) {
        	$row->delete();
            $this->jump(array('action' => 'top'), __('This link deleted'));
        } else {
        	$this->jump(array('action' => 'top'), __('Please select link'));	
        }
    }

    /**
     * List update action
     */
    public function topaddAction()
    {
        // Set view
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row_list = $this->getModel('url_list')->find($id);
        if ($row_list) {
            $values['loc'] = $row_list->loc;
            $values['lastmod'] = $row_list->lastmod;
            $values['changefreq'] = $row_list->changefreq;
            $values['priority'] = $row_list->priority;
            $values['create'] = time();
            // Save
            $row_top = $this->getModel('url_top')->createRow();
            $row_top->assign($values);
            $row_top->save();
            // Delete
            Pi::api('sitemap', 'sitemap')->item($row_list->module, $row_list->table, false);
            $row_list->delete();
            // jump
            $this->jump(array('action' => 'list'), __('This link add as top link'));
        } else {
            $this->jump(array('action' => 'list'), __('Please select link'));   
        }
    }
    	
    /**
     * List action
     */
    public function listAction()
    {
        // Get info
        $module = $this->params('module');
        $page = $this->params('p', 1);
        // Get info
        $select = $this->getModel('url_list')->select()->order(array('id DESC', 'create DESC'));
        $rowset = $this->getModel('url_list')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $link[$row->id] = $row->toArray();
            $link[$row->id]['create'] = _date($link[$row->id]['create']);
        }
        // Go to update page if empty
        if (empty($link)) {
            return $this->redirect()->toRoute('', array('action' => 'index'));
        }
        // Set paginator
        $paginator = \Pi\Paginator\Paginator::factory($link);
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            // Use router to build URL for each page
            'pageParam' => 'p',
            'totalParam' => 't',
            'router' => $this->getEvent()->getRouter(),
            'route' => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params' => array(
                'module' => $this->getModule(),
                'controller' => 'index',
                'action' => 'list',
            ),
        ));
        // Set view
        $this->view()->setTemplate('index_list');
        $this->view()->assign('links', $paginator);
    }

    /**
     * Top update action
     */
    public function listdeleteAction()
    {
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('url_list')->find($id);
        if ($row) {
            Pi::api('sitemap', 'sitemap')->item($row->module, $row->table, false);
        	$row->delete();
            $this->jump(array('action' => 'list'), __('This link deleted'));
        } else {
        	$this->jump(array('action' => 'list'), __('Please select link'));	
        }
    }
}