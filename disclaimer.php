<?php
/**
 * Plugin Name:     Disclaimer Manager
 * Plugin URI:      http://gresak.net/wp
 * Description:     A simple plugin to add a text of your choice (i.e. a disclaimer) to all your posts. You can use it without tweeking a theme.
 * Author:          Gregor Grešak
 * Author URI:      http://gresak.net
 * Text Domain:     disclaimer
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Disclaimer
 */

GGDisclaimer::instance(__FILE__);

class GGDisclaimer {

	protected $path;

	protected function __construct($path) {
		$this->path = $path;
		add_action( 'customize_register',array($this,'customizer') );
		add_filter( 'the_content', array($this,'display_disclaimer'));
	}

	public function display_disclaimer($content) {
		if($this->check_post_type(array('post'))) {
			$disclaimer = get_theme_mod('ggdisclaimer_text');
			if(!empty($disclaimer)) {
				$content = $content . "<div class='ggdisclaimer'>".$disclaimer."</div>";
			}
		}	
		return $content;
	}

	public function check_post_type(array $types) {
		if(!is_single()) return false;
		if(in_array(get_post_type(), $types)) {
			return true;
		} else {
			return false;
		}
	}

	 public function customizer($wp_customize) {

        $wp_customize->add_section('ggdisclaimer', array(
		'title' => __('Disclaimer Manager','disclaimer'),
		'priority' => 30,
            ));

		$wp_customize->add_setting('ggdisclaimer_text', array( "default" => "" ));
		$wp_customize->add_control(
	            new WP_Customize_Control(
	                $wp_customize,
	                'ggdisclaimer_text',
	                array(
	                    'label' => __('Disclaimer text','disclaimer'),
	                    'section' => 'ggdisclaimer',
	                    'settings' => 'ggdisclaimer_text',
	                    'type' => 'textarea',
	                    )
	                )
	            );
    }



	/**
    * Static method returns instance of the class object ( see singleton pattern 
    * http://www.phptherightway.com/pages/Design-Patterns.html#singleton )
    * @param  string $path to the plugin
    * @return object       instance of the class object
    */
    public static function instance($path)
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new GGDisclaimer($path);
        }
        return $inst;
    }

}