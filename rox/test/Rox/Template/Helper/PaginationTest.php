<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/helper.php';

/**
 * Test case for Pagination Helper
 *
 * @package Rox_Test
 */
class Rox_Template_Helper_PaginationTest extends PHPUnit_Framework_TestCase {

	/**
	 * Tests the Rox_Helper_Pagination::links() method
	 *
	 * @return void
	 */
	public function testLinks() {
		/*
		   collection    = empty Array
		   pages         = 3
		   current page  = 1
		   next page     = 2
		   previous page = 1
		 */
		$paginationResult = new Rox_ActiveRecord_PaginationResult(array(), 3, 1, 2, 1, 40);
		$paginationHelper = new Rox_Template_Helper_Pagination;

		$result   = $paginationHelper->links($paginationResult);
		$expected = '<div class="pagination"><em>1</em> <a href="?page=2">2</a> <a href="?page=3">3</a> <a href="?page=2" rel="next" class="next-page">Next &raquo;</a></div>';

		$this->assertEquals($result, $expected);
	}

	/**
	 * Tests the Rox_Helper_Pagination::links() method with options
	 *
	 * @return void
	 */
	public function testLinksWithOptions() {
		/*
		   collection    = empty Array
		   pages         = 20
		   current page  = 3
		   next page     = 4
		   previous page = 2
		 */
		$paginationResult = new Rox_ActiveRecord_PaginationResult(array(), 20, 3, 4, 2, 40);
		$paginationHelper = new Rox_Template_Helper_Pagination;

		$result = $paginationHelper->links($paginationResult, array(
			'class'          => 'my-custom-pagination-class',
			'prev_label' => 'My Prev Label',
			'next_label'     => 'My Next Label',
			'max_items'      => 10
		));

		$matcher = array(
			'tag' => 'div',
			'attributes' => array(
				'class' => 'my-custom-pagination-class'
			)
		);

		$this->assertTag($matcher, $result);
	
		// Next page link
		$matcher = array(
			'tag' => 'a',
			'parent' => array('tag' => 'div'),
			'content' => 'My Prev Label'
		);

		$this->assertTag($matcher, $result);

		// Previous page link
		$matcher = array(
			'tag' => 'a',
			'parent' => array('tag' => 'div'),
			'content' => 'My Next Label'
		);

		$this->assertTag($matcher, $result);

		// Last page link
		$matcher = array(
			'tag' => 'a',
			'parent' => array('tag' => 'div'),
			'content' => '20',
			'attributes' => array(
				'href' => '?page=20'
			)
		);

		$this->assertTag($matcher, $result);

		$matcher = array(
			'tag' => 'div',
			'child' => array(
				'tag'     => 'span',
				'content' => '...',
			)
		);

		$this->assertTag($matcher, $result);

		$matcher = array(
			'tag' => 'a',
			'content' => '11'
		);

		$this->assertNotTag($matcher, $result);
	}
}