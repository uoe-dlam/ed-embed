<?php 

class EmbedTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_initialise_mediahopper_table()
    {
        global $wpdb;

        //log in as admin
        self::login_as_admin();

        (new Ed_Embed_Init_Cache())->initialise_mediahopper_table();
        $table_name = $wpdb->base_prefix . 'mediahopper';
        $result = $wpdb->insert($table_name, array('url'=>'test', 'iframe'=>'test'));

        $this->assertEquals(1, $result);
    }

    private function initialize_media_table()
    {
        global $wpdb;

        $wpdb->mediahoppertable = $wpdb->base_prefix . 'mediahopper';
        $wpdb->query(
                    "CREATE TABLE IF NOT EXISTS `{$wpdb->mediahoppertable}` (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      url varchar(2048) NOT NULL,
                      iframe varchar(2048) NOT NULL,
                      PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"
                );
    }

    private function login_as_admin()
    {
        $user = get_user_by('login', 'admin' );
        if( $user ) {
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID );
            do_action( 'wp_login', $user->user_login );
        }
    }
}