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
        // Set sitemap.xml path
        $sitemap = Pi::path('sitemap.xml');
        // Set url
        if (file_exists($sitemap)) {
        	$url = Pi::url('sitemap.xml');
        	$this->view()->assign('url', $url);
        }
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
        // Get info
        $module = $this->params('module');
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
        // Set view
        $this->view()->setTemplate('index_top');
        $this->view()->assign('links', $link);
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

    /**
     * Top update action
     */
    public function listdeleteAction()
    {
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('url_list')->find($id);
        if ($row) {
        	$row->delete();
            $this->jump(array('action' => 'list'), __('This link deleted'));
        } else {
        	$this->jump(array('action' => 'list'), __('Please select link'));	
        }
    }

    /**
     * Generat sitemap action
     */
    public function GeneratAction()
    {
        
        // Set view
        $this->view()->setTemplate(false);
        // Set sitemap.xml path
        $sitemap = Pi::path('sitemap.xml');
        // Remove old file
        if (file_exists($sitemap)) {
        	if (!@unlink($sitemap)) {
        		$message = sprintf(__('Unable to remove %s , please remove file manual and generat again'), $sitemap);
        		$this->jump(array('action' => 'index'), $message);
        	}
        }
        // Get content
        $content = $this->Content();
        // Write information on sitemap.xml
        $sm = fopen($sitemap, "x+");
        fwrite($sm, '<?xml version="1.0" encoding="UTF-8"?>');
        fwrite($sm, PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        // Write links
        foreach ($content as $link) {
        	fwrite($sm, PHP_EOL . '	<url>');
        	if (!empty($link['loc'])) {
        		fwrite($sm, PHP_EOL . '		<loc>' . $this->escapingUrl($link['loc']) . '</loc>');
        	}
        	if (!empty($link['lastmod'])) {
        		fwrite($sm, PHP_EOL . '		<lastmod>' . $link['lastmod'] . '</lastmod>');
        	}
        	if (!empty($link['changefreq'])) {
        		fwrite($sm, PHP_EOL . '		<changefreq>' . $link['changefreq'] . '</changefreq>');
        	}
        	if (!empty($link['priority'])) {
        		fwrite($sm, PHP_EOL . '		<priority>' . $link['priority'] . '</priority>');
        	}
        	fwrite($sm, PHP_EOL . '	</url>');
        }	
        // Close file
        fwrite($sm, PHP_EOL . '</urlset>');
        fclose($sm);
        // jump
        $this->jump(array('action' => 'index'), __('Finish'));
    }

    public function Content()
    {
    	$content = array();

    	// Add website index url
    	$content[] = array(
    		'loc' => Pi::url('www'),
    		'lastmod' => date("Y-m-d H:i:s"),
    		'changefreq' => 'daily',
    		'priority' => '',
    	);

    	// Make info from url_top table
    	$order = array('id DESC', 'create DESC');
        $select = $this->getModel('url_top')->select()->order($order);
        $rowset = $this->getModel('url_top')->selectWith($select);
        foreach ($rowset as $row) {
            $url_top[$row->id] = $row->toArray();
            $link['loc'] = $url_top[$row->id]['loc'];
            $link['lastmod'] = $url_top[$row->id]['lastmod'];
            $link['changefreq'] = $url_top[$row->id]['changefreq'];
            $link['priority'] = $url_top[$row->id]['priority'];
            $content[] = $link;
        }
        $columns = array('count' => new \Zend\Db\Sql\Expression('count(*)'));
        $select = $this->getModel('url_top')->select()->columns($columns);
        $count = $this->getModel('url_top')->selectWith($select)->current()->count;

        // Make info from url_list table
        $limit = intval(500 - intval($count));
        $where = array('status' => 1);
        $select = $this->getModel('url_list')->select()->where($where)->order($order)->limit($limit);
        $rowset = $this->getModel('url_list')->selectWith($select);
        foreach ($rowset as $row) {
            $url_top[$row->id] = $row->toArray();
            $link['loc'] = $url_top[$row->id]['loc'];
            $link['lastmod'] = $url_top[$row->id]['lastmod'];
            $link['changefreq'] = $url_top[$row->id]['changefreq'];
            $link['priority'] = $url_top[$row->id]['priority'];
            $content[] = $link;
        }

        // Set just 500 urls for sitemap.xml
        $content = array_slice($content, 0, 499);
    	return $content;
    }

    public function escapingUrl($url)
    {
    	$url = htmlspecialchars($url, ENT_QUOTES);
    	//$url = rawurlencode($url);
    	$url = urldecode($url);
    	$url = str_replace(' ', '%20', $url);
    	return $url;
    }
}