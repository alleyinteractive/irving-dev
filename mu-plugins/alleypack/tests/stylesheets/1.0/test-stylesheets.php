<?php
/**
 * Class Stylesheets
 *
 * @package Alleypack
 */

namespace Alleypack\Tests;

/**
 * Test cases for CSS Modules module.
 *
 * @group stylesheets
 */
class StylesheetsTest extends \WP_UnitTestCase {
	protected function ob( $callback, $args = array() ) {
		ob_start();
		call_user_func_array( $callback, $args );
		return ob_get_clean();
	}

	public function setUp() {
		\Alleypack\load_module( 'stylesheets', '1.0' );

		ob_start();
		\Alleypack\Stylesheets::instance()->setup( dirname( __FILE__, 2 ) . '/mocks/mock-classnames.json' );
		ob_get_clean();

		ai_use_stylesheet( 'mock-one' );
	}

	public function test_use_stylesheet() {
		$test_classnames = [
			'alignleft' => 'mock-one__alignleft___1cXIA',
			'alignright' => 'mock-one__alignright___18ifD',
			'title' => 'mock-one__title___2kGA7 _typography__header-main___2IME8',
			'article-body' => 'mock-one__article-body___RnJE3',
		];

		$this->assertEquals( $test_classnames, \Alleypack\Stylesheets::instance()->current_stylesheet_classnames );
		$this->assertEquals( 'mock-one', \Alleypack\Stylesheets::instance()->current_stylesheet );
	}

	public function test_get_classnames() {
		$classnames = ai_get_classnames( [ 'alignleft' ] );
		$this->assertEquals( 'mock-one__alignleft___1cXIA', $classnames );
	}

	public function test_the_classnames() {
		$classnames = $this->ob( 'ai_the_classnames', [
			'static_classes' => [ 'alignleft' ],
		] );
		$this->assertEquals( 'mock-one__alignleft___1cXIA', $classnames );
	}

	public function test_get_multiple_classnames() {
		$classnames = ai_get_classnames( [ 'alignleft', 'alignright' ] );
		$this->assertEquals( 'mock-one__alignleft___1cXIA mock-one__alignright___18ifD', $classnames );
	}

	public function test_get_multiple_classnames_complex() {
		$classnames = ai_get_classnames( [
			'alignleft',
			'test-class',
		],
		[ 'alignright' => false ] );
		$this->assertEquals( 'mock-one__alignleft___1cXIA test-class', $classnames );
	}

	public function test_get_fix_stylesheet_args() {
		$classnames = ai_get_classnames( [ 'alignleft' ], 'mock-two' );
		$this->assertEquals( 'mock-two__alignleft___Lww3s', $classnames );
	}

	public function test_get_classnames_with_global() {
		$classnames = ai_get_classnames_with_global( [ 'alignleft', 'alignright' ] );
		$this->assertEquals( 'alignleft alignright mock-one__alignleft___1cXIA mock-one__alignright___18ifD', $classnames );
	}

	public function test_get_classnames_alternate_stylesheet() {
		$classnames = ai_get_classnames( [ 'alignleft' ], [], 'mock-two' );
		$this->assertEquals( 'mock-two__alignleft___Lww3s', $classnames );
	}
}
