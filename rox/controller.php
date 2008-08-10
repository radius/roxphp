<?php
/**
 * RoxPHP
 *
 * Copyright (C) 2008 Ramon Torres
 *
 * This Software is released under the MIT License.
 * See license.txt for more details.
 *
 * @package Rox
 * @author Ramon Torres
 * @copyright Copyright (c) 2008 Ramon Torres (http://roxphp.com)
 * @license http://roxphp.com/static/license.html
 * @version $Id$
 */

/**
 * Controller
 *
 * @package Rox
 * @copyright Copyright (c) 2008 Ramon Torres
 * @license http://roxphp.com/static/license.html
 */
class Controller {

	/**
	 * Controller name
	 *
	 * @var string
	 */
	protected $_name;

	/**
	 * Page title
	 *
	 * @var string
	 */
	protected $_pageTitle = 'RoxPHP';

	/**
	 * Layout name
	 *
	 * @var array  
	 */
	protected $_layout = 'default';

	/**
	 * Current action
	 *
	 * @var string
	 */
	protected $_action = '';

	/**
	 * List of models to load automatically
	 *
	 * @var array
	 */
	protected $_models = array();

	/**
	 * Posted data
	 *
	 * @var array  
	 */
	protected $_data = array();

	/**
	 * Request object
	 *
	 * @var Request
	 */
	protected $_request;

	/**
	 * View variables
	 *
	 * @var array  
	 */
	protected $_viewVars = array();

	/**
	 * Class constructor
	 */
	public function __construct() {
		if (is_null($this->_name)) {
			$this->_name = str_replace('Controller', '', get_class($this));
		}

		foreach($this->_models as $model) {
			$this->{$model} = Rox::getModel($model);
		}
	}

	/**
	 * Renders the current action
	 */
	public function render() {
		$this->set('rox_page_title', $this->_pageTitle);
		$View = new View($this->_viewVars);
		echo $View->render(strtolower($this->_name), $this->_action, $this->_layout);
	}

	/**
	 * Data setter
	 *
	 * @param array $data 
	 */
	public function setData($data) {
		$this->_data = $data;
	}

	/**
	 * Controller::getData()
	 *
	 * @param string $model
	 * @param string $field
	 * @param mixed $default
	 * @return mixed
	 */
	public function getData($model, $field, $default = null) {
		if (isset($this->_data[$model][$field])) {
			return $this->_data[$model][$field];
		}
		return $default;
	}

	/**
	 * Sets a view variable
	 *
	 * @param string $varName
	 * @param mixed $value
	 */
	public function set($varName, $value = null) {
		$this->_viewVars[$varName] = $value;
	}

	/**
	 * Page title setter
	 *
	 * @param $title
	 */
	public function setPageTitle($title) {
		$this->_pageTitle = $title;
	}

	/**
	 * Action setter
	 *
	 * @param $action
	 */
	public function setAction($action) {
		$this->_action = $action;
	}

	/**
	 * Layout name setter
	 *
	 * @param $layoutName
	 */
	public function setLayout($layout) {
		$this->_layout = $layout;
	}

	/**
	 * Controller::setRequest()
	 * 
	 * @param Request $request
	 * @return void
	 */
	public function setRequest(Request $request) {
		$this->_request = $request;
	}

	/**
	 * Controller::getRequest()
	 * 
	 * @return Request
	 */
	public function getRequest() {
		return $this->_request;
	}

	/**
	 * Send redirect headers and exit
	 *
	 * @param string $url
	 */
	protected function redirect($url) {
		header('HTTP/1.1 301');
		header('Location: ' . Router::url($url));
		exit;
	}

	/**
	 * Redirects to referer
	 *
	 * @param string $default
	 */
	protected function redirectToReferer($default = '/') {
		if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
			$referer = $_SERVER['HTTP_REFERER'];
		} else {
			$referer = Router::url($default);
		}

		header('HTTP/1.1 301');
		header('Location: ' . $referer);
		exit;
	}
}