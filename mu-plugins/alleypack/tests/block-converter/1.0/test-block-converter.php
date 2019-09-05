<?php
/**
 * Class Block Converter
 *
 * @package Alleypack
 */

namespace Alleypack\Tests;

use Alleypack\Block\Converter;

/**
 * Test case for Block Converter Module.
 * 
 * @group block
 */
class Test_Block_Converter extends \WP_UnitTestCase {

    public function setUp() {
        parent::setUp();
        
        \Alleypack\load_module( 'block-converter', '1.0' );
	}

	public function test_class_exist() {
        $class = new Converter( '<h1>Foo</h1><p>bar</p>' );

		$this->assertTrue( class_exists( 'Alleypack\Block\Converter' ) );
        $this->assertInstanceOf( 'Alleypack\Block\Converter', $class );
    }

    public function test_convert_content_to_blocks() {
        $html      = '<p>Content to migrate</p><h1>Heading 01</h1>';
        $converter = new Converter( $html );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
'<!-- wp:paragraph -->
<p>Content to migrate</p>
<!-- /wp:paragraph --><!-- wp:heading {"level":1} -->
<h1>Heading 01</h1>
<!-- /wp:heading -->'
        );
    }

    public function test_convert_heading_h1_to_block() {
        $html = '<h1>Another content</h1>';
        $converter = new Converter( $html );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
'<!-- wp:heading {"level":1} -->
' . $html . '
<!-- /wp:heading -->'
        );
    }

    public function test_convert_heading_h2_to_block() {
        $html = '<h2>Another content</h2>';
        $converter = new Converter( $html );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
'<!-- wp:heading {"level":2} -->
' . $html . '
<!-- /wp:heading -->'
        );
    }

    public function test_convert_ol_to_block() {
        $html = '<ol><li>Random content</li><li>Another random content</li></ol>';
        $converter = new Converter( $html );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
'<!-- wp:list {"ordered":true} -->
' . $html . '
<!-- /wp:list -->'
        );
    }

    public function test_convert_ul_to_block() {
        $html = '<ul><li>Random content</li><li>Another random content</li></ul>';
        $converter = new Converter( $html );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
"<!-- wp:list -->
{$html}
<!-- /wp:list -->"
        );
    }
    
    public function test_convert_paragraphs_to_block() {
        $converter = new Converter( '<p>bar</p>' );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
'<!-- wp:paragraph -->
<p>bar</p>
<!-- /wp:paragraph -->'
        );
    }

    public function test_convert_with_empty_paragraphs_to_block() {
        $converter = new Converter( '<p>bar</p><p></p>' );
        $block     = $converter->convert_to_block();

        $this->assertNotEmpty( $block );
        $this->assertSame(
            $block,
'<!-- wp:paragraph -->
<p>bar</p>
<!-- /wp:paragraph -->'
        );
    }
}
