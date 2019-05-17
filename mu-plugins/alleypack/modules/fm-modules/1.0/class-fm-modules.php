<?php
/**
 * Class file for FM_Modules.
 *
 * @package Alleypack
 */

namespace Alleypack;

/**
 * FM Modules allow editors to easily build their own layouts.
 */
class FM_Modules {

	/**
	 * Unique slug.
	 *
	 * @var string
	 */
	public $slug = '';

	/**
	 * Module options.
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Components.
	 *
	 * @var array
	 */
	public $components = [];

	/**
	 * Component classes.
	 *
	 * @var array
	 */
	public $component_classes = [];

	/**
	 * Whether the first item in the select field is empty.
	 *
	 * @var bool
	 */
	public $first_empty = true;

	/**
	 * Constructor.
	 *
	 * @param string $slug              Unique slug.
	 * @param array  $component_classes Component classes.
	 * @param bool   $first_empty       Whether the first item in the select field is empty.
	 */
	public function __construct( $slug = '', $component_classes = [], $first_empty = true ) {
		$this->slug              = $slug;
		$this->component_classes = apply_filters( 'fm_modules_components', $component_classes, $slug );
		$this->first_empty       = $first_empty;

		// Get the components.
		foreach ( $this->component_classes as $class ) {
			if ( class_exists( $class ) ) {
				// Get an instance of this component.
				$this->components[] = new $class();
			}
		}

		// Derive the FM options from the components.
		foreach ( $this->components as $component ) {
			$this->options[ $component->name ] = $component->get_label();
		}
	}

	/**
	 * Helper function that returns modules as FM fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {

		// Build module fields.
		$fields = [
			'module_type' => new \Fieldmanager_Select(
				[
					'first_empty' => $this->first_empty,
					'options'     => $this->options,
				]
			),
		];

		// Get the fields for each module.
		foreach ( $this->components as $component ) {
			// Create a group for this component.
			$fields[ $component->name ] = new \Fieldmanager_Group(
				[
					'label'       => $component->get_label(),
					'label_macro' => $component->get_label_macro(),
					'display_if'  => [
						'src'   => 'module_type',
						'value' => $component->name,
					],
					'children'    => $component->get_fm_fields(),
				]
			);
		}

		return $fields;
	}

	/**
	 * Get a Fieldmanager Group for the modules.
	 *
	 * @return \Fieldmanager_Group
	 */
	public function get_fm_group() : \Fieldmanager_Group {
		return new \Fieldmanager_Group(
			[
				'add_more_label' => __( 'Add Module', 'alleypack' ),
				'children'       => $this->get_fm_fields(),
				'collapsed'      => true,
				'extra_elements' => 0,
				'label'          => __( 'New Module', 'alleypack' ),
				'label_macro'    => [ 'Module: %s', 'module_type' ],
				'limit'          => 0,
				'sortable'       => true,
				'group_is_empty' => function( $value ) {
					return empty( $value['module_type'] );
				},
			]
		);
	}

	/**
	 * Get FM children array for modules.
	 *
	 * @return array
	 */
	public function get_fm_children() {
		return [
			'modules' => $this->get_fm_group(),
		];
	}

	/**
	 * Get an array of components using saved FM data.
	 *
	 * @param  array $modules Modules stored as FM data.
	 * @return array Array of components.
	 */
	public function get_components_from_fm_data( array $modules ) : array {

		// Build components array.
		$components = [];

		foreach ( $modules as $module ) {
			$component = $this->get_component_from_fm_data( $module );
			if ( ! empty( $component ) ) {
				$components[] = $component;
			}
		}

		return $components;
	}

	/**
	 * Get a component using saved FM data.
	 *
	 * @param  array $module Module stored as FM data.
	 * @return \WP_Irving\Component\Component|null Component.
	 */
	public function get_component_from_fm_data( array $module ) {
		// Get and validate type.
		$type = $module['module_type'] ?? '';
		if ( empty( $type ) ) {
			return;
		}

		// Find the component.
		foreach ( $this->components as $obj ) {
			if ( $type === $obj->name ) {
				// Parse the saved data.
				$component = ( new $obj() )->parse_from_fm_data( $module[ $type ] ?? [] );
				break;
			}
		}

		return $component ?? null;
	}
}
