<?php
/**
 * RoxPHP
 *
 * Copyright (C) 2008 - 2009 Ramon Torres
 *
 * This Software is released under the MIT License.
 * See license.txt for more details.
 *
 * @package Rox
 * @author Ramon Torres
 * @copyright Copyright (C) 2008 - 2009 Ramon Torres
 * @license http://roxphp.com/static/license.html
 * @version $Id$
 */

// Set the default timezone used by all date/time functions
date_default_timezone_set('America/New_York');

session_name('ROXAPP');
session_start();

// Init the cache class
Rox_Cache::init(Rox_Cache::ADAPTER_FILE);
