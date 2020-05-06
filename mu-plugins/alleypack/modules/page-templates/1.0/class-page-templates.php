<?php
/**
 * Easily leverage WordPress page templates to create unique layouts.
 *
 * @package Alleypack\Page_Templates
 */

namespace Alleypack\Page_Templates;

/**
 * Page templates registration and management.
 */
class Page_Templates {

	use \Alleypack\Singleton;

	/**
	 * Templates.
	 *
	 * @var array
	 */
	public $templates = [];

	/**
	 * Singleton Initialization.
	 */
	public function setup() {
		add_action( 'fm_post_page', [ $this, 'add_meta_box' ] );
		add_action( 'theme_templates', [ $this, 'theme_templates' ] );
	}

	/**
	 * Register a new page template.
	 *
	 * @param string $name   Template name.
	 * @param string $slug   Template slug.
	 * @param array  $fields Array of FM fields.
	 */
	public function register_page_template( string $name, string $slug, array $fields = [] ) {

		$slug = sanitize_title( $slug );

		$this->templates[] = compact( [ 'name', 'slug', 'fields' ] );
	}

	/**
	 * Get a mapping of template slugs to names for the FM dropdown.
	 *
	 * @return array
	 */
	public function get_options() : array {
		$options = [];
		foreach ( $this->templates as $template ) {
			$options[ $template['slug'] ] = $template['name'];
		}
		return $options;
	}

	/**
	 * Filter the theme templates to include the registered templates.
	 *
	 * @param array $templates Page templates.
	 * @return array
	 */
	public function theme_templates( array $templates ) : array {
		return array_merge(
			$templates,
			$this->get_options()
		);
	}

	/**
	 * Display a metabox of FM fields to select a page template and fill in
	 * associated fields.
	 */
	public function add_meta_box() {

		$label = apply_filters( 'alleypack_page_templates_metabox_label', __( 'Template:', 'alleypack' ) );

		// Create template dropdown.
		$children = [
			'_wp_page_template' => new \Fieldmanager_Select(
				[
					'label'       => $label,
					'options'     => $this->get_options(),
					'first_empty' => true,
				]
			),
		];

		// For each registered template, add a FM group with template fields.
		foreach ( $this->templates as $template ) {

			// Ensure we have FM fields.
			if ( empty( $template['fields'] ) ) {
				break;
			};

			// Add a new group for this page template.
			$children[ $template['slug'] ] = new \Fieldmanager_Group(
				[
					'children'   => (array) $template['fields'],
					'display_if' => [
						'src'   => '_wp_page_template',
						'value' => $template['slug'],
					],
				]
			);
		}

		$fm = new \Fieldmanager_Group(
			[
				'name'           => 'fm-page-templates',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => $children,
			]
		);

		$context = apply_filters( 'alleypack_page_templates_metabox_context', 'normal' );

		$fm->add_meta_box( __( 'Templates', 'alleypack' ), [ 'page' ], $context, 'high' );
	}
}
