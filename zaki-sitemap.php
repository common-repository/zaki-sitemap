<?php
/**
 * Plugin Name: Zaki Sitemap
 * Plugin URI:  http://www.zaki.it
 * Description: Plugin that show a sitemap of page/post. You can choose to show/hide categories and custom post-types.
 * Version:     1.2
 * Author:      Zaki Design
 * Author URI:  http://www.zaki.it
 */
 
define('ZAKI_SITEMAP_FILE',__FILE__);

// Plugin Classes
require_once plugin_dir_path(ZAKI_SITEMAP_FILE).'classes/class-zaki-plugins.php';
require_once plugin_dir_path(ZAKI_SITEMAP_FILE).'classes/class-zaki-sitemap.php';

add_action('init','ZakiSitemap_init');
function ZakiSitemap_init() {
    global $zakiSitemap;
    $zakiSitemap = new ZakiSitemap();
}

// Hooks & Init
add_action('admin_init', 'ZakiSitemap_SettingsInit');
add_action('admin_menu', 'ZakiSitemap_AddMenuPages');
register_activation_hook(ZAKI_SITEMAP_FILE, 'ZakiSitemap_Activation');
register_deactivation_hook( ZAKI_SITEMAP_FILE, 'ZakiSitemap_Deactivation');

// Attivazione e disattivazione plugin
function ZakiSitemap_Activation() {
    $settings = array(
        "excl_pages" => array(),
        "label_pages" => __('Pages','zaki'),
        "excl_categories" => array(),
        "label_categories" => __('Archives','zaki'),
        "excl_posttype" => array(),
        "label_posttype" => __('Post Type','zaki'),
        "excl_posttype_posts" => array(),
    );
    update_option('zaki_sitemap_options', $settings);
}

function ZakiSitemap_Deactivation() {
    unregister_setting('zaki_sitemap_options','zaki_sitemap_options');
}

// Definizione variabile settaggi con relative callback
function ZakiSitemap_SettingsInit() {

    register_setting(
        'zaki_sitemap_options',
        'zaki_sitemap_options'
    );
    
    add_settings_section(
        'zaki_sitemap_options_section_main',
        __('General Settings','zaki'),
        'ZakiSitemap_PageSetting_Section_Main_Callback',
        'zaki-sitemap'
    );
        
        add_settings_field(
            'zaki_sitemap_op_label_pages',
            __('Label of pages','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_LabelPages_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
        add_settings_field(
            'zaki_sitemap_op_excl_pages',
            __('Pages to exclude','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_ExclPages_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
        add_settings_field(
            'zaki_sitemap_op_label_categories',
            __('Label of categories','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_LabelCategories_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
        add_settings_field(
            'zaki_sitemap_op_excl_categories',
            __('Categories to exclude','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_ExclCategories_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
        add_settings_field(
            'zaki_sitemap_op_label_posttype',
            __('Label of post type','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_LabelPosttype_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
        add_settings_field(
            'zaki_sitemap_op_excl_posttype',
            __('Post-type to exclude','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_ExclPosttype_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
        add_settings_field(
            'zaki_sitemap_op_excl_posttype_posts',
            __('Exclude posts from','zaki'),
            'ZakiSitemap_PageSetting_Section_Main_ExclPosttypePosts_Callback',
            'zaki-sitemap',
            'zaki_sitemap_options_section_main'
        );
        
}

// Sezione generale
function ZakiSitemap_PageSetting_Section_Main_Callback() {
    echo __('In this page you can set all the sitemap options. ;-)','zaki');
}
           
     // Settaggio label pagine
    function ZakiSitemap_PageSetting_Section_Main_LabelPages_Callback() {
        $settings = get_option('zaki_sitemap_options');
        ?>
        <input name="zaki_sitemap_options[label_pages]" type="text" value="<?=$settings['label_pages']?>" />
        <?php
    }
    
    // Pagine da escludere
    function ZakiSitemap_PageSetting_Section_Main_ExclPages_Callback() {
        $settings = get_option('zaki_sitemap_options');
        $page_to_exclude = (array) $settings['excl_pages'];
        $pages = get_pages(array(
            'hierarchical' => 1,
            'child_of' => 0,
            'parent' => -1,
            'post_type' => 'page',
            'post_status' => 'publish'
        ));
        ?>
        <div class="exclbox">
            <?php
            if($pages) : foreach($pages as $p) :
                $checked = (in_array($p->ID,$page_to_exclude)) ? ' checked="checked"' : '';
                $sepcount = count(get_ancestors($p->ID,'page'));
                for($i=0;$i<$sepcount;$i++) { echo '&nbsp;&ndash;&nbsp;'; }
                ?>
                <input name="zaki_sitemap_options[excl_pages][]" type="checkbox" value="<?=$p->ID?>" class="code" <?=$checked?> />&nbsp;<?=$p->post_title?>
                <br />
                <?php
            endforeach; endif;
            ?>
        </div>
        <?php
    }
    
    // Settaggio label categories
    function ZakiSitemap_PageSetting_Section_Main_LabelCategories_Callback() {
        $settings = get_option('zaki_sitemap_options');
        ?>
        <input name="zaki_sitemap_options[label_categories]" type="text" value="<?=$settings['label_categories']?>" />
        <?php
    }
    
    // Categorie da escludere
    function ZakiSitemap_PageSetting_Section_Main_ExclCategories_PrintHTML($cat,$sepcount,$cat_to_exclude) {
        $categories = get_categories(array(
			'hide_empty' => false,
			'parent' => $cat
		));
        if($categories) : foreach($categories as $c) :
            $checked = (in_array($c->term_id,$cat_to_exclude)) ? ' checked="checked"' : '';
            for($i=0;$i<$sepcount;$i++) { echo '&nbsp;&ndash;&nbsp;'; }
            ?>
            <input name="zaki_sitemap_options[excl_categories][]" type="checkbox" value="<?=$c->term_id?>" class="code" <?=$checked?> />&nbsp;<?=$c->name?>
            <br />
            <?php
            $childcount = count(get_categories(array('hide_empty' => false,'parent' => $c->term_id)));
            if($childcount > 0) {
                $newsepcount = $sepcount + 1;
                ZakiSitemap_PageSetting_Section_Main_ExclCategories_PrintHTML($c->term_id,$newsepcount,$cat_to_exclude);
            }
        endforeach; endif;
    }
    
    function ZakiSitemap_PageSetting_Section_Main_ExclCategories_Callback() {
        $settings = get_option('zaki_sitemap_options');
        $cat_to_exclude = (array) $settings['excl_categories'];
        ?>
        <div class="exclbox">
            <?php ZakiSitemap_PageSetting_Section_Main_ExclCategories_PrintHTML(0,0,$cat_to_exclude); ?>
        </div>
        <?php
    }
    
    // Settaggio label posttype
    function ZakiSitemap_PageSetting_Section_Main_LabelPosttype_Callback() {
        $settings = get_option('zaki_sitemap_options');
        ?>
        <input name="zaki_sitemap_options[label_posttype]" type="text" value="<?=$settings['label_posttype']?>" />
        <?php
    }
    
    // Post type da escludere
    function ZakiSitemap_PageSetting_Section_Main_ExclPosttype_Callback() {
        $settings = get_option('zaki_sitemap_options');
        $pt_to_exclude = (array) $settings['excl_posttype'];
        $posttype = get_post_types(array(
            'public'   => true,
            '_builtin' => false
        ),'objects');
        
        if($posttype) : 
            foreach($posttype as $name => $pt) :
                $checked = (in_array($name,$pt_to_exclude)) ? ' checked="checked"' : '';
                ?>
                <input name="zaki_sitemap_options[excl_posttype][]" type="checkbox" value="<?=$name?>" class="code" <?=$checked?> />&nbsp;<?=$pt->labels->name?>
                <br />
                <?php
            endforeach; 
        else:
            ?>
            <p class="description"><?=__('No custom post-type defined','zaki');?></p>
            <?php
        endif;  
    }
    
    // Post type posts da escludere
    function ZakiSitemap_PageSetting_Section_Main_ExclPosttypePosts_Callback() {
        $settings = get_option('zaki_sitemap_options');
        $pt_to_exclude = (array) $settings['excl_posttype_posts'];
        $posttype = get_post_types(array(
            'public'   => true,
            '_builtin' => false
        ),'objects');
        
        if($posttype) : 
            foreach($posttype as $name => $pt) :
                $checked = (in_array($name,$pt_to_exclude)) ? ' checked="checked"' : '';
                ?>
                <input name="zaki_sitemap_options[excl_posttype_posts][]" type="checkbox" value="<?=$name?>" class="code" <?=$checked?> />&nbsp;<?=$pt->labels->name?>
                <br />
                <?php
            endforeach; 
            ?>
            <p class="description"><?=__('This setting affects only active post-type','zaki');?></p>
            <?php
        else:
            ?>
            <p class="description"><?=__('No custom post-type defined','zaki');?></p>
            <?php
        endif;  
    }

// Inizializzazione pagine menu
function ZakiSitemap_AddMenuPages() {

    //Controllo ed eventualmente includo il menu principale
    ZakiPlugins::checkMainMenu();
            
    // Pagine del plugins
    add_submenu_page(
        'zaki',
        __('Sitemap','zaki'),
        __('Sitemap','zaki'),
        'manage_options',
        'zaki-sitemap',
        'ZakiSitemap_PageSettingHtml'
    );
    
}

// HTML Pagina principale di settaggio (main)
function ZakiSitemap_PageSettingHtml() {
    $wpml_active = ZakiSitemap::checkWPML();
    $settings = get_option('zaki_sitemap_options');
    ?>  
    <div class="wrap zaki_sitemap_page zaki_sitemap_page_main">
        <?php screen_icon('options-general'); ?><h2><?=__('Zaki Sitemap','zaki')?></h2>
        
        <?php if($wpml_active) : ?>
            <!-- WPML Alert -->
            <div id="message" class="updated"><?=__('You have WPML Plugin activated! Sitemap list will be translated in frontend automatically.','zaki')?></div>
        <?php endif; ?>        
        
        <form method="post" action="options.php">
            <?php settings_fields('zaki_sitemap_options'); ?>
            <?php do_settings_sections('zaki-sitemap'); ?>
            <p class="submit">
               <input name="submit" type="submit" id="submit" class="button-primary" value="<?=__('Save','zaki')?>" />
            </p>
        </form>
    </div>
    <?php
}

