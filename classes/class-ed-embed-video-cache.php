<?php
/**
 * Handles passing mediahopper embed html to and from db
 *
 * @author DLAM Applications Development Team
 */
class Ed_Embed_Video_Cache {

	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * If URL exists in mediahopper table, get iframe HTML. Else, return empty string.
	 *
	 * @param $url string
	 * @return string
	 */
	public function get( $url ) {

		$row = $this->wpdb->get_row(
			$this->wpdb->prepare(
				'SELECT iframe FROM ' . $this->wpdb->base_prefix . 'mediahopper WHERE url = %s',
				$url
			)
		);

		if ( $row ) {
			return $row->iframe;
		}

		return '';
	}

	/**
	 * Save iframe HTML for mediahopper URL
	 *
	 * @param $url string
	 * @param $iframe_html string
	 * @return string|false
	 */
	public function save( $url, $iframe_html ) {
		$this->wpdb->insert(
			$this->wpdb->base_prefix . 'mediahopper', array(
				'url'    => $url,
				'iframe' => $iframe_html,
			)
		);
	}
}
