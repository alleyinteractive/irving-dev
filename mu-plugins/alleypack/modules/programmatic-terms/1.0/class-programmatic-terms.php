<?php
/**
 * Create and manage terms programmatically.
 *
 * @package Alleypack
 * @version 1.0
 */

namespace Alleypack;

/**
 * Class for programmatic terms.
 */
class Programmatic_Terms {

	/**
	 * Taxonomy.
	 *
	 * @var string
	 */
	public $taxonomy = '';

	/**
	 * All taxonomies with a programmatic term setup.
	 *
	 * @var array
	 */
	public static $taxonomies = [];

	/**
	 * Constructor.
	 *
	 * @param string $taxonomy Taxonomy to use.
	 */
	public function __construct( $taxonomy ) {

		// Store var for this instance.
		$this->taxonomy = $taxonomy;

		// Add to record for all instances.
		self::$taxonomies[] = $taxonomy;

		// Validate the taxonomy.
		add_action(
			'wp_loaded',
			function() use ( $taxonomy ) {
				if ( ! taxonomy_exists( $taxonomy ) ) {
					wp_die( esc_html__( 'Missing taxonomy.', 'alleypack' ) );
				}
			}
		);

		// Create, update, and delete terms as necessary.
		add_action( 'init', [ $this, 'create_and_update_terms' ], 11 );
	}

	/**
	 * Ensure that terms are correct.
	 */
	public function create_and_update_terms() {

		/**
		 * Filter the term options available.
		 *
		 * @param array  $options  Associative array of term options ( slug => name ). Default empty array.
		 * @param string $taxonomy Taxonomy.
		 */
		$options = apply_filters( 'alleypack_programmatic_terms_options', [], $this->taxonomy );

		// Determine if the terms have changed, and update acccordingly.
		$one_timer = new \Alleypack\One_Timer( "{$this->taxonomy}_terms", $options );

		if ( ! $one_timer->is_unchanged() ) {

			// Loop through options.
			foreach ( $options as $slug => $name ) {

				// Does this already exist?
				$term = get_term_by( 'slug', $slug, $this->taxonomy );
				if ( $term instanceof \WP_Term ) {

					// Update the name if necessary.
					if ( $term->name !== $name ) {
						wp_update_term(
							$term->term_id,
							$this->taxonomy,
							[
								'name' => $name,
							]
						);
					}
				} else {
					// Create the new term.
					wp_insert_term( $name, $this->taxonomy, [ 'slug' => $slug ] );
				}
			}

			// Ensure we _only_ have terms we want.
			array_map(
				function( $term ) use ( $options ) {

					// Validate term.
					if ( ! $term instanceof \WP_Term ) {
						return;
					}

					// Delete if it's slug isn't in the options anymore.
					if ( ! in_array( $term->slug, array_keys( $options ), true ) ) {
						wp_delete_term( $term->term_id, $term->taxonomy );
					}
				},
				(array) get_terms(
					[
						'hide_empty' => false,
						'taxonomy'   => $this->taxonomy,
					]
				)
			);

			$one_timer->save_change();
		}
	}
}
