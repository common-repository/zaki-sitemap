<?php
final class ZakiSitemap {

    // Activation plugin
    public function __construct() {
        add_shortcode('zakisitemap',array($this,'createShortcode'));
        add_action('admin_init',array($this,'loadBackendCss'));
    }
    
    public function loadBackendCss() {
        wp_register_style('zaki_sitemap_backend_css',plugins_url('css/main.css', ZAKI_SITEMAP_FILE));
        wp_enqueue_style('zaki_sitemap_backend_css');
    }
        
    // Funzione che estrapola l'array dei post.type
    public static function getPostTypeArray() {	
    
        //Pagine da escludere
        $settings = get_option('zaki_sitemap_options');
        $posttype_to_exclude = (array) $settings['excl_posttype'];

        // Array pagine
        $posttype = get_post_types(array(
            'public'   => true,
            '_builtin' => false
        ),'objects');
        
        foreach($posttype as $key => $val) :
            if(in_array($key,$posttype_to_exclude)) unset($posttype[$key]);
        endforeach;

        if(!$posttype) return array();
        return $posttype;
    }
    
    // Funzione di check WPML
    public static function checkWPML() {
        if(is_plugin_active('sitepress-multilingual-cms/sitepress.php')) return true;
        return false;
    }
     
    // Funzione che crea lo shortcode
    public function createShortcode($atts) {

        $settings = get_option('zaki_sitemap_options');

        // Attributi shortcode
        extract(shortcode_atts(array(
        ),$atts));

        //Pagine        
        $page_to_exclude = (array) $settings['excl_pages'];
        $outputPages = wp_list_pages(array(
            'echo'         => false,
            'exclude'      => implode(',',$page_to_exclude),
            'title_li'     => '<h2>'.$settings['label_pages'].'</h2>'
        ));
            
        //Categorie
        $cat_to_exclude = (array) $settings['excl_categories'];
        $outputCategories = wp_list_categories(array(
            'orderby'            => 'name',
            'order'              => 'ASC',
            'style'              => 'list',
            'show_count'         => false,
            'hide_empty'         => false,
            'exclude'            => implode(',',$cat_to_exclude),
            'title_li'           => '<h2>'.$settings['label_categories'].'</h2>',
            'echo'               => false,
            'show_option_none'   => ''
        ));
                    
        //Post Type
        $arrayPostType = self::getPostTypeArray();
        $arrayPostTypePosts = (array) $settings['excl_posttype_posts'];
        $outputPostType = '';
        if($arrayPostType) : 
            $outputPostType = '<li>';
            if($settings['label_posttype']!='') $outputPostType .= '<h2>'.$settings['label_posttype'].'</h2>';
            $outputPostType .= '<ul>';
            foreach($arrayPostType as $name => $pt) :
                $outputPostType .= '<li><a href="'.get_post_type_archive_link($name).'">'.$pt->labels->name.'</a>';
                if(!in_array($name,$arrayPostTypePosts)) {
                    $cPTPosts = new WP_Query(array(
                        'post_type' => $name,
                        'nopaging' => true
                    ));
                    if($cPTPosts->posts) :
                        $outputPostType .= '<ul class="children">';
                        foreach($cPTPosts->posts as $cptp) :
                            $outputPostType .= '<li><a href="'.get_permalink($cptp->ID).'">'.$cptp->post_title.'</a>';
                        endforeach;
                        $outputPostType .= '</ul>';
                    endif;
                }
                $outputPostType .= '</li>';
            endforeach;
            $outputPostType .= '</ul></li>';
        endif;

        return '<ul class="zaki-sitemap-list">'.$outputPages.$outputCategories.$outputPostType.'</ul>';
    }
    
}