<?php
/*
 * Copyright 2016 Fedor A. Fetisov <faf@mibew.ru>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @file The main file of FAF:VisitorsListFilter plugin.
 *
 */

namespace FAF\Mibew\Plugin\VisitorsListFilter;

use Mibew\EventDispatcher\EventDispatcher;
use Mibew\EventDispatcher\Events;

/**
 * Defenition of the main plugin class.
 *
 */
class Plugin extends \Mibew\Plugin\AbstractPlugin implements \Mibew\Plugin\PluginInterface
{

    protected $initialized = true;

    private $filter_mode = '';

    /**
     * Plugin's constructor.
     *
     *
     * Check the one and only configuration param (i.e. 'filter_mode') to be
     * in place and valid.
     *
     * @param array $config Associative array of configuration params from the
     * main config file.
     */
    public function __construct($config)
    {
        if (!isset($config['filter_mode'])) {
            $this->initialized = false;
            throw new \RuntimeException('Operational mode not specified!');
        }
        else if (!in_array($config['filter_mode'], array('strict', 'light'))) {
            $this->initialized = false;
            throw new \RuntimeException('Invalid operational mode!');
        }
        else {
            $this->filterMode = $config['filter_mode'];
        }
    }

    /**
     * The main entry point of a plugin
     *
     * Attach listener to 'Alter visitors' event (see
     * http://docs.mibew.org/development/server-side-events.html#users-events
     * for details).
     */
    public function run()
    {
        $dispatcher = EventDispatcher::getInstance();
        $dispatcher->attachListener(Events::USERS_UPDATE_VISITORS_ALTER, $this, 'visitorsAlterHandler');
    }

    /**
     * Returns verision of the plugin.
     *
     * @return string Plugin's version.
     */
    public static function getVersion()
    {
        return '0.0.1';
    }

    /**
     * Filter duplicate entries from the list of visitors. If filter mode set
     * to 'strict' all entries with duplicates will be removed even the
     * original ones.
     *
     *
     * @see \Mibew\EventDispatcher\Events::USERS_UPDATE_VISITORS_ALTER
     */
    public function visitorsAlterHandler(&$array)
    {
        if (!isset($array['visitors']) || !count($array['visitors'])) {
            return;
        }

        $hashes = array();
        $visitors = array();
        $idx = 0;

        foreach ($array['visitors'] as $visitor) {
            $hash = hash('md5', $visitor['userName'] . $visitor['userIp'] . $visitor['userAgent']);
            if (!isset($hashes[$hash])) {
                $hashes[$hash] = $idx;
                $visitors[$idx++] = $visitor;
            }

            // Remove original entries in case if duplicates were seen.
            if ($this->filter_mode == 'strict') {
                foreach ($hashes as $hash) {
                    if (isset($visitors[$hashes[$hash]])) {
                        unset($visitors[$hashes[$hash]]);
                    }
                }
            }
        }
        $array['visitors'] = $visitors;
    }
}
