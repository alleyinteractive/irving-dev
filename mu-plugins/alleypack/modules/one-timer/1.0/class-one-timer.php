<?php
/**
 * Class file for One_Timer.
 *
 * @package Alleypack
 */

namespace Alleypack;

/**
 * One Timer.
 *
 * Class for ensuring something happens once and only once.
 */
class One_Timer {

	/**
	 * Name.
	 *
	 * @var array
	 */
	private $name = '';

	/**
	 * Value.
	 *
	 * @var mixed
	 */
	private $value = '';

	/**
	 * Constructor.
	 *
	 * @param string $name  Name.
	 * @param mixed  $value Value.
	 */
	public function __construct( string $name, $value ) {
		$this->name  = $name;
		$this->value = $value;
	}

	/**
	 * Check if the value is unchanged.
	 *
	 * @return bool True if the value being checked is unchanged.
	 */
	public function is_unchanged() {
		$this->hash   = $this->get_array_hash( $this->value );
		$current_hash = get_transient( $this->name );

		if (
			false !== $current_hash
			&& $current_hash === $this->hash
		) {
			return true;
		}

		return false;
	}


	/**
	 * Update the transient.
	 */
	public function save_change() {
		// Store the hash to the transient.
		set_transient( $this->name, $this->hash );
	}

	/**
	 * Get a hash from data.
	 *
	 * @param  mixed $data Data to hash.
	 * @return string
	 */
	private function get_array_hash( $data ) {
		return md5( wp_json_encode( $data ) );
	}
}
