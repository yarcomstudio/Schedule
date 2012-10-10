<?php
/**
 * Schedule
 *
 * Copyright 2010 by Shaun McCormick <shaun+schedule@modx.com>
 *
 * Schedule is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Schedule is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Schedule; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package schedule
 */
/**
 * @package schedule
 */
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * {@inheritdoc}
 *
 * @package schedule
 * @extends modRequest
 */
class ScheduleControllerRequest extends modRequest {
    public $Schedule = null;
    public $actionVar = 'action';
    public $defaultAction = 'home';

    function __construct(Schedule &$Schedule) {
        parent :: __construct($Schedule->modx);
        $this->Schedule =& $Schedule;
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * {@inheritdoc}
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        return $this->_respond();
    }

    /**
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @access public
     * @return boolean True if the response is properly prepared.
     */
    private function _respond() {
        $modx =& $this->modx;
        $Schedule =& $this->Schedule;

        $viewHeader = include $this->Schedule->config['corePath'].'controllers/mgr/header.php';

        $f = $this->Schedule->config['corePath'].'controllers/mgr/'.$this->action.'.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
}