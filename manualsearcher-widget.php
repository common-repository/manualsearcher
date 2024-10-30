<?php
/*
Plugin Name: ManualSearcher
Plugin URI: http://manuall.co.uk/plugin
Description: Adds the ManualSearcher widget. Allows users to search user manuals from our database in a language of your choice.
Version: 1.4
Author: HandleidingKwijt
Author URI: https://handleidingkwijt.com
*/
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    include( plugin_dir_path( __FILE__ ) . 'functions.php');
    
    //add css
    function hkms_my_scripts() {
        wp_register_style( 'stylesheet', plugins_url('/css/styles.min.css', __FILE__) );
        wp_enqueue_style( 'stylesheet' );
    }
    add_action('wp_enqueue_scripts','hkms_my_scripts');
    
    //create widget
    class hkms_ManualSearcher extends WP_Widget {
        
        //constructor
        function hkms_ManualSearcher() {
            $widget_ops = array('classname' => 'ManualSearcher', 'description' => 'Adds the ManualSearcher widget',);
            parent::__construct('manualsearcher', 'ManualSearcher', $widget_ops);
        } //end of function hkms_ManualSearcher()
        
        //widget()
        function widget($args, $instance){
            //instance
            $widget_width = $instance['widget_width'];
            $radius = $instance ['radius'];
            $placeholder = $instance['placeholder'];
            $border_style = $instance ['border_style'];
            $url = $instance ['url'];
            $description = $instance['description'];
            $background_color = $instance['background_color'];
            $link = $instance['link'];
            $logo = $instance['logo'];
            $title = $instance['title'];
            
            //args
            $before_widget = $args['before_widget'];
            $after_widget = $args['after_widget'];
            
            //insert manuall or handleidingkwijt logo
            $logo_check = strpos($url, 'manuall');
            if ($logo_check === false){
                $img_url = plugins_url('/images/logo_handleidingkwijt.png', __FILE__);
            }
            else {
                $img_url = plugins_url('/images/logo_manuall.png', __FILE__);;
            }
            
            //add link to logo or not
            if ($link==1){
                $image = '<a href="' .$url .'" target="_blank"><img class="hkms_widget_image" src="' .$img_url .'"></img></a>';
            }
            else {
                $image = '<img class="hkms_widget_image" src="' .$img_url .'">';
            }
            
            //replace logo with title
            if ($logo==0 AND $link==1){
                $image = '<h3 class="hkms_widget_title"><a class="hkms_widget_title" href="' .$url .'" target="_blank">' .$title .'</a></h3>';
            }
            elseif ($logo==0 AND $link==0){
                $image = '<h3 class="hkms_widget_title">' .$title .'</h3>';
            }
            $domain = $_SERVER['HTTP_HOST'];
            
            //check for mobile devices
            $target = '_blank';
            if( strstr($_SERVER['HTTP_USER_AGENT'],'Android') ||
               strstr($_SERVER['HTTP_USER_AGENT'],'webOS') ||
               strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') ||
               strstr($_SERVER['HTTP_USER_AGENT'],'iPod') ||
               strstr($_SERVER['HTTP_USER_AGENT'],'iPad')
               ){
                $target = '';
            }
            
            //create widget
            $widget = $before_widget .'<div class="hkms_top_widget" style="width:' .$widget_width .';"><div class="hkms_inner_widget" style="border:' .$border_style .'; border-radius:' .$radius .'; background:' .$background_color .';">' .$image .'<p class="hkms_widget_description">' .$description .'</p><form method="post" action="" target="' .$target .'"><input class="hkms_widget_search_box" placeholder="' .$placeholder .'" type="text" name="search"/><input type="hidden" name="url" value="' .$url .'"/><input type="hidden" name="domain" value="' .$domain .'"/></form></div></div>' .$after_widget;
            if($_SERVER['REQUEST_METHOD']=='POST')
            {
                hkms_search();
            }
            echo $widget;
        } //end of function widget()
        
        //form()
        function form($instance){
            //add initial values
            $default = array(
                'widget_width' => '100%',
                'radius' => '10px',
                'placeholder' => 'Enter search term',
                'border_style' => '1px solid #a8a8a8',
                'url' => 'http://manuall.co.uk/',
                'description' => '',
                'background_color' => '',
                'title' => '',
                'link' => '0',
                'logo' => '1',
                );
            
            $instance = wp_parse_args( (array) $instance , $default);
            
            $widget_width = $instance['widget_width'];
            $radius = $instance['radius'];
            $placeholder = $instance['placeholder'];
            $border_style = $instance['border_style'];
            $url = $instance['url'];
            $description = $instance['description'];
            $background_color = $instance['background_color'];
            $link = $instance['link'];
            $logo = $instance['logo'];
            $title = $instance['title'];
            
            //php closing tag
            ?>

            <p>
            <label for="<?php echo $this->get_field_id('url'); ?>">Source</label>
            <select id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" style="width:100%">
            <option <?php selected( $instance['url'], 'http://shuomingshu88.com/'); ?> value="http://shuomingshu88.com/">CN - Shuomingshu88</option>
            <option <?php selected( $instance['url'], 'http://manuall.cz/'); ?> value="http://manuall.cz/">CZ - Manuall Czech Republic</option>
            <option <?php selected( $instance['url'], 'http://manuall.dk/'); ?> value="http://manuall.dk/">DK - Manuall Danmark</option>
            <option <?php selected( $instance['url'], 'http://manuall.de/'); ?> value="http://manuall.de/">DE - Manuall Deutschland</option>
            <option <?php selected( $instance['url'], 'http://manuall.es/'); ?> value="http://manuall.es/">ES - Manuall Espana</option>
            <option <?php selected( $instance['url'], 'http://manuall.fi/'); ?> value="http://manuall.fi/">FI - Manuall Finland</option>
            <option <?php selected( $instance['url'], 'http://manuall.fr/'); ?> value="http://manuall.fr/">FR - Manuall France</option>
            <option <?php selected( $instance['url'], 'http://manuall.hu/'); ?> value="http://manuall.hu/">HU - Manuall Hungary</option>
            <option <?php selected( $instance['url'], 'http://manuall.jp/'); ?> value="http://manuall.jp/">JP - Manuall Japan</option>
            <option <?php selected( $instance['url'], 'http://manuall.it/'); ?> value="http://manuall.it/">IT - Manuall Italia</option>
            <option <?php selected( $instance['url'], 'http://manuall.kr/'); ?> value="http://manuall.kr/">KR - Manuall Korea</option>
            <option <?php selected( $instance['url'], 'http://handleidingkwijt.com/'); ?> value="http://handleidingkwijt.com/">NL - HandleidingKwijt</option>
            <option <?php selected( $instance['url'], 'http://manuall.no/'); ?> value="http://manuall.no/">NO - Manuall Norge</option>
            <option <?php selected( $instance['url'], 'http://manuall.pl/'); ?> value="http://manuall.pl/">PL - Manuall Polska</option>
            <option <?php selected( $instance['url'], 'http://manuall.pt/'); ?> value="http://manuall.pt/">PT - Manuall Portugal</option>
            <option <?php selected( $instance['url'], 'http://manuall.ro/'); ?> value="http://manuall.ro/">RO - Manuall Romania</option>
            <option <?php selected( $instance['url'], 'http://manuall.ru.com/'); ?> value="http://manuall.ru.com/">RU - Manuall Russia</option>
            <option <?php selected( $instance['url'], 'http://manuall.se/'); ?> value="http://manuall.se/">SE - Manuall Sverige</option>
            <option <?php selected( $instance['url'], 'http://manuall.sk/'); ?> value="http://manuall.sk/">SK - Manuall Slovakia</option>
            <option <?php selected( $instance['url'], 'http://manuall.info.tr/'); ?> value="http://manuall.info.tr/">TR - Manuall Türkiye</option>
            <option <?php selected( $instance['url'], 'http://manuall.co.uk/'); ?> value="http://manuall.co.uk/">UK - Manuall United Kingdom</option>
            </select>
            </p>

            <p>
            <input class="widefat" id="<?php echo $this->get_field_id('logo'); ?>" name="<?php echo $this->get_field_name('logo'); ?>" type="checkbox" value="1" <?php if($logo==1) {echo 'checked';} ?> />
            display logo
            </p>

            <p>
            <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="checkbox" value="1" <?php if($link==1) {echo 'checked';} ?> />
            add link to website
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Logo replacement</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" placeholder="<?php echo $default['title']; ?>" value="<?php echo $title; ?>" />
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('widget_width'); ?>">Width</label>
            <input class="widefat" id="<?php echo $this->get_field_id('widget_width'); ?>" name="<?php echo $this->get_field_name('widget_width'); ?>" type="text" placeholder="<?php echo $default['widget_width']; ?>" value="<?php echo $widget_width; ?>" />
            </p>


            <p>
            <label for="<?php echo $this->get_field_id('radius'); ?>">Radius</label>
            <input class="widefat" id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" type="text" placeholder="<?php echo $default['radius']; ?>" value="<?php echo $radius; ?>" />
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('placeholder'); ?>">Placeholder</label>
            <input class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" placeholder="<?php echo $default['placeholder']; ?>" value="<?php echo $placeholder; ?>" />
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('border_style'); ?>">Border style</label>
            <input class="widefat" id="<?php echo $this->get_field_id('border_style'); ?>" name="<?php echo $this->get_field_name('border_style'); ?>" type="text" placeholder="<?php echo $default['border_style']; ?>" value="<?php echo $border_style; ?>" />
            </p>

            <p>
            <label for="<?php echo $this->get_field_id('background_color'); ?>">Background color</label>
            <input class=" widefat" id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" placeholder="#ffffff" value="<?php echo $background_color; ?>" />

            <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">Description</label>
            <textarea class="widefat" rows="5" id="<?php echo $this->get_field_id('description'); ?>" placeholder="Find your manual. Example &quot;Siemens washing machine&quot;." name="<?php echo $this->get_field_name('description'); ?>"><?php echo $description; ?></textarea>
            </p>

            <?php //php opening tag
        } //end of function form()
        
        //update()
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            
            //check link checkbox
            $safe_link = intval($new_instance['link']);
            if(!$safe_link){
                $safe_link = 0;
            }
            if($safe_link!=1){
                $safe_link = 0;
            }
            
            //check logo checkbox
            $safe_logo = intval($new_instance['logo']);
            if(!$safe_logo){
                $safe_logo = 0;
            }
            if($safe_logo!=1){
                $safe_logo = 0;
            }
            
            $instance['widget_width'] = sanitize_text_field( $new_instance['widget_width'] );
            $instance['radius'] = sanitize_text_field( $new_instance['radius'] );
            $instance['placeholder'] = sanitize_text_field( $new_instance['placeholder'] );
            $instance['border_style'] = sanitize_text_field( $new_instance['border_style'] );
            $instance['url'] = esc_url_raw( $new_instance['url'] );
            $instance['description'] = sanitize_text_field( $new_instance['description'] );
            $instance['background_color'] = sanitize_text_field( $new_instance['background_color'] );
            $instance['link'] = sanitize_text_field( $safe_link );
            $instance['logo'] = sanitize_text_field( $safe_logo );
            $instance['title'] = sanitize_text_field( $new_instance['title'] );
            return $instance;
        } //end of function update()
    } //end of class ManualSearcher
    
    //register widget
    add_action( 'widgets_init', function(){
               register_widget( 'hkms_ManualSearcher' );
               });
?>
