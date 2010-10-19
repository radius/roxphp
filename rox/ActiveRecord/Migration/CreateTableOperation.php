<?php

class Rox_ActiveRecord_Migration_CreateTableOperation {

	protected $_tableName;
	protected $_options = array('engine' => 'InnoDB');
	protected $_columns;

	public function __construct($tableName, $options = array()) {
		$this->_tableName = $tableName;
		$this->_options = array_merge($this->_options, $options);
	}

	public function column($name, $type, $options = array()) {
		$this->_columns[] = compact('name', 'type', 'options');
	}

	public function __call($method, $args = array()) {
		if (count($args) < 1) {
			throw new Rox_Exception("Missing name argument");
		}

		$name = $args[0];
		$type = $method;
		$options = array_slice($args, 1);

		$this->column($name, $type, $options);
	}

	public function timestamps() {
		$this->datetime('created_at', array('null' => false));
		$this->datetime('updated_at', array('null' => false));
	}

	public function finish() {
		$colsDef = array();
		$colsDef[] = "`id` INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT";

		foreach ($this->_columns as $column) {
			$expanded = Rox_ActiveRecord_Migration_Connection::expandColumn($column['type'], $column['options']);
			$colsDef[] = sprintf("`%s` %s", $column['name'], $expanded);
		}

		$colsDef[] = "PRIMARY KEY (`id`)";

		$sql = array();
		$sql[] = sprintf("CREATE TABLE `%s` (", $this->_tableName);
		$sql[] = implode(",\n", $colsDef);
		$sql[] = sprintf(") ENGINE %s;", $this->_options['engine']);
		$sql = implode("\n", $sql);

		$datasource = Rox_ConnectionManager::getDataSource();
		$datasource->execute($sql);
	}
}
