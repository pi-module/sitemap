<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

namespace Module\Sitemap\Installer\Action;
use Pi;
use Pi\Application\Installer\Action\Install as BasicInstall;
use Zend\EventManager\Event;

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */ 

class Install extends BasicInstall
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('install.pre', array($this, 'preInstall'), 1000);
        $events->attach('install.post', array($this, 'postInstall'), 1);
        parent::attachDefaultListeners();
        return $this;
    }

    public function preInstall(Event $e)
    {
        $result = array(
            'status' => true,
            'message' => sprintf('Called from %s', __METHOD__),
        );
        $e->setParam('result', $result);
    }

    public function postInstall(Event $e)
    {
    	// Set model
    	$module = $e->getParam('module');
    	$historyModel =  Pi::model('history', $module);

    	// Add link
        $historyData = array(
            'file' => 'sitemap.xml',
            'module' => '',
            'table' => '',
            'create' => time(),
        );
        $historyModel->insert($historyData);

        // Result
        $result = array(
            'status' => true,
            'message' => __('Default sitemap information added.'),
        );
        $this->setResult('post-install', $result);
    }
}	