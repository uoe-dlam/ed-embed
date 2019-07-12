<?php

/**
 * Handles Mediahopper Embed Cache tables in WordPress
 *
 * @author DLAM Applications Development Team
 */
class Ed_Embed_Init_Cache {

	private $wpdb;

	public function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	/**
	 * Create table to store media hopper iframe code
	 *
	 * @return void
	 */
	public function initialise_mediahopper_table() {
		$this->wpdb->mediahoppertable = $this->wpdb->base_prefix . 'mediahopper';

		if ( is_user_logged_in() && is_super_admin() ) {
            // phpcs:disable
            $mediahopper_tables = $this->wpdb->get_var( "SHOW TABLES LIKE '{$this->wpdb->mediahoppertable}'" );
            // phpcs:enable

			if ( $mediahopper_tables !== $this->wpdb->mediahoppertable ) {
                // phpcs:disable
                $this->wpdb->query(
                    "CREATE TABLE IF NOT EXISTS `{$this->wpdb->mediahoppertable}` (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      url varchar(2048) NOT NULL,
                      iframe varchar(2048) NOT NULL,
                      PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
                );

            }
        }
    }
}
