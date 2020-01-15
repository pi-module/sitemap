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
use Pi\Application\Installer\Action\Update as BasicUpdate;
use Pi\Application\Installer\SqlSchema;
use Zend\EventManager\Event;

class Update extends BasicUpdate
{
    /**
     * {@inheritDoc}
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('update.pre', [$this, 'updateSchema']);
        parent::attachDefaultListeners();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSchema(Event $e)
    {
        $moduleVersion = $e->getParam('version');

        // Set url_list model
        $urlListModel   = Pi::model('url_list', $this->module);
        $urlListTable   = $urlListModel->getTable();
        $urlListAdapter = $urlListModel->getAdapter();

        // Set url_top model
        $urlTopModel   = Pi::model('url_top', $this->module);
        $urlTopTable   = $urlTopModel->getTable();
        $urlTopAdapter = $urlTopModel->getAdapter();

        // Set url model
        $urlModel   = Pi::model('url', $this->module);
        $urlTable   = $urlModel->getTable();
        $urlAdapter = $urlModel->getAdapter();

        // Update to version 1.2.0
        if (version_compare($moduleVersion, '1.2.0', '<')) {


            // Alter table drop index `loc_unique`
            $sql = sprintf(
                "ALTER TABLE %s DROP INDEX loc_unique;",
                $urlListTable
            );
            try {
                $urlListAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // Alter table add field `top`
            $sql = sprintf(
                "ALTER TABLE %s ADD `top` tinyint(1) unsigned NOT NULL default '0' , ADD INDEX (`top`) ;",
                $urlListTable
            );
            try {
                $urlListAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }

            // insert information from url_top to url_list
            $select = $urlTopModel->select();
            $rowset = $urlTopModel->selectWith($select);
            foreach ($rowset as $row) {
                // Add link
                $listData = [
                    'loc'         => $row->loc,
                    'lastmod'     => $row->lastmod,
                    'changefreq'  => $row->changefreq,
                    'priority'    => $row->priority,
                    'time_create' => $row->time_create,
                    'module'      => '',
                    'table'       => '',
                    'item'        => '',
                    'status'      => 1,
                    'top'         => 1,
                ];
                $urlListModel->insert($listData);
            }

            // Drop not used `url_top` table
            try {
                $sql = sprintf(
                    'DROP TABLE IF EXISTS %s',
                    $urlTopTable
                );
                $urlTopAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table drop failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }
        }

        // Update to version 1.2.1
        if (version_compare($moduleVersion, '1.2.1', '<')) {

            $sql = sprintf(
                'RENAME TABLE `%s` TO `%s`',
                $urlListTable, $urlTable
            );

            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'SQL schema query for author table failed: '
                            . $exception->getMessage(),
                    ]
                );

                return false;
            }
        }

        // Update to version 1.2.3
        if (version_compare($moduleVersion, '1.2.3', '<')) {

            // Set changefreq and priority
            $changefreq = 'weekly';
            $priority   = '0.5';

            // Update url
            $select = $urlModel->select();
            $rowset = $urlModel->selectWith($select);
            foreach ($rowset as $row) {
                // Set changefreq and priority
                switch ($row->top) {
                    case 0:
                        switch ($row->module) {
                            case 'news' :
                                switch ($row->table) {
                                    case 'story';
                                        $changefreq = 'daily';
                                        $priority   = '0.7';
                                        break;

                                    case 'microblog';
                                        $changefreq = 'daily';
                                        $priority   = '0.4';
                                        break;

                                    case 'topic';
                                        $changefreq = 'weekly';
                                        $priority   = '0.3';
                                        break;

                                    case 'author';
                                        $changefreq = 'weekly';
                                        $priority   = '0.3';
                                        break;
                                }
                                break;

                            case 'guide' :
                                switch ($row->table) {
                                    case 'item';
                                        $changefreq = 'daily';
                                        $priority   = '0.8';
                                        break;

                                    case 'event';
                                        $changefreq = 'daily';
                                        $priority   = '0.6';
                                        break;

                                    case 'category';
                                        $changefreq = 'weekly';
                                        $priority   = '0.3';
                                        break;

                                    case 'location';
                                        $changefreq = 'weekly';
                                        $priority   = '0.3';
                                        break;
                                }
                                break;

                            case 'shop' :
                                switch ($row->table) {
                                    case 'product';
                                        $changefreq = 'weekly';
                                        $priority   = '0.6';
                                        break;

                                    case 'category';
                                        $changefreq = 'weekly';
                                        $priority   = '0.3';
                                        break;
                                }
                                break;

                            case 'page' :
                                $changefreq = 'weekly';
                                $priority   = '0.3';
                                break;

                            case 'portfolio' :
                                $changefreq = 'weekly';
                                $priority   = '0.3';
                                break;

                            case 'event' :
                                $changefreq = 'daily';
                                $priority   = '0.6';
                                break;

                            case 'blog' :
                                $changefreq = 'daily';
                                $priority   = '0.6';
                                break;

                            case 'gallery' :
                                $changefreq = 'daily';
                                $priority   = '0.6';
                                break;
                        }
                        break;

                    case 1:
                        $changefreq = 'weekly';
                        $priority   = '0.4';
                        break;
                }

                $row->changefreq = $changefreq;
                $row->priority   = $priority;
                $row->save();
            }


            // Alter table : ADD index
            $sql = sprintf("ALTER TABLE %s ADD INDEX `list_order` (`priority`, `top`, `time_create`)", $urlTable);
            try {
                $urlAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult(
                    'db', [
                        'status'  => false,
                        'message' => 'Table alter query failed: '
                            . $exception->getMessage(),
                    ]
                );
                return false;
            }
        }
    }
}