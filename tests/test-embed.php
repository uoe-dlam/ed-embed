<?php 

class EmbedTest extends WP_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();
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

    public function test_Ed_Embed_Video_Cache_get()
    {
        global $wpdb;

        self::initialize_media_table();

        $table_name = $wpdb->base_prefix . 'mediahopper';
        $url = 'test_url';
        $iframe = 'test_iframe';
        $wpdb->insert($table_name, array('url'=>$url, 'iframe'=>$iframe));
        $result = (new Ed_Embed_Video_Cache())->get($url);

        $this->assertEquals('test_iframe', $result);
    }

    public function test_Ed_Embed_Video_Cache_save()
    {
        global $wpdb;

        self::initialize_media_table();
        $url = 'test_url';
        $iframe = 'test_iframe';
        (new Ed_Embed_Video_Cache())->save( $url, $iframe );

        $result = $wpdb->get_row(
            $wpdb->prepare(
                'SELECT iframe FROM ' . $wpdb->base_prefix . 'mediahopper WHERE url = %d AND iframe= %s',
                $url,
                $iframe
            )
        );

        $this->assertEquals('test_iframe', $result->iframe);
    }

    public function test_custom_wp_trim_excerpt_has_filter()
    {
        $result = has_filter( 'get_the_excerpt', 'custom_wp_trim_excerpt' );
        $this->assertEquals(10, $result);
    }
    
    public function test_media_hopper_embed_handler_init()
    {
        $result = has_action( 'init', 'add_media_hopper_embed_handler', 'mediahopper' );
        $this->assertEquals(10, $result);
    }
}