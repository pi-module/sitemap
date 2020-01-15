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

namespace Module\Sitemap\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Install as BasicInstall;
use Zend\EventManager\Event;

class Install extends BasicInstall
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('install.post', [$this, 'postInstall'], 1);
        parent::attachDefaultListeners();
        return $this;
    }

    public function postInstall(Event $e)
    {
        // Set model
        $module        = $e->getParam('module');
        $generateModel = Pi::model('generate', $module);

        // Add link
        $generateData = [
            'file'        => 'sitemap.xml',
            'time_create' => time(),
        ];
        $generateModel->insert($generateData);

        // Result
        $result = [
            'status'  => true,
            'message' => __('Default sitemap information added.'),
        ];
        $this->setResult('post-install', $result);
    }
}