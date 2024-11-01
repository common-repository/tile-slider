<?php
/*
  Plugin Name: Tile Slider for WordPress
  Plugin URI: http://kumarz.in/tile-wp/
  Description: Wordpress implementation of Tile Slider(http://kumarz.in/tile/).
  Version: 1.0
  Author: Rajesh Kumar Sharma
  Author URI: http://kumarz.in/
  License:@TODO
 * 
 */

global $wpdb, $tile_slide_tbl, $tile_slider_tbl, $tile_slider_rel_tbl;
/*
 * Database table structure of tile
 * table : tile_sliders
 * fields --
 * id: Slider Id
 * description: Slider Description
 * name: Slider Name
 * tile_duration:tile animation duration
 * animation_speed: Slide Progressing Speed
 * shrink_n_fade:if to shrink or not
 * animation_direction: Direction of animation
 * show_titles: if to show tiles
 * tile_height: Height of the lile
 * tile_width: Width of the tile
 * 
 */
$tile_slider_tbl = "CREATE TABLE  `" . $wpdb->prefix . "tile_slider` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 400 ) NOT NULL ,
`tile_duration` INT NULL DEFAULT  '150',
`animation_speed` INT NULL DEFAULT  '600',
`animation` VARCHAR(100) NOT NULL DEFAULT 'random' ,
`animation_dir` INT NULL DEFAULT  '0',
`interval` INT NULL DEFAULT  '0',
`tile_height` INT NULL DEFAULT  '40',
`thumb_height` INT NULL DEFAULT  '1',
`thumb_width` INT NULL DEFAULT  '40',
`tile_width` INT NOT NULL DEFAULT  '40',
`slider_height` INT NULL DEFAULT  '600',
`slider_width` INT NOT NULL DEFAULT  '400');\n";
/*
 * table : tile_slides
 * fields --
 * id: id of the slide
 * name: name of the slide
 * type: type of the slide post/image slide
 * img_url : image of the slide - for image slide types
 * desc : Desc of the slide - for image slide types
 */
$tile_slide_tbl = "CREATE TABLE  `" . $wpdb->prefix . "tile_slide` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 400 ) NULL DEFAULT NULL ,
`type` VARCHAR( 10 ) NOT NULL,
`img_url` VARCHAR( 600 ) NULL DEFAULT  NULL,
`desc` VARCHAR( 600 ) NULL DEFAULT  NULL,
`link` VARCHAR( 600 ) NULL DEFAULT  NULL
);\n";
/* table: tile_slider_rel
 * fields --
 * slide_id: id of the slide
 * slider_id: id of the slider
 * 
 */
$tile_slider_rel_tbl = "CREATE TABLE  `" . $wpdb->prefix . "tile_slider_rel` (
`slide_id` INT NOT NULL DEFAULT 1,
`slider_id` INT NOT NULL DEFAULT 1,
`slide_type` varchar(10) NOT NULL DEFAULT 'tslide',
UNIQUE KEY `rel` (`slide_id`,`slider_id`,`slider_type`) );\n";
/*
 * Set Installation Hooks
 * Create Tables at Database and 1 default slide with 5 slider from images directory
 */
register_activation_hook(__FILE__, 'tile_install');

function tile_install() {
    global $wpdb, $tile_slide_tbl, $tile_slider_tbl, $tile_slider_rel_tbl;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($tile_slider_tbl);
    dbDelta($tile_slide_tbl);
    dbDelta($tile_slider_rel_tbl);
    //$wpdb->query($tile_slider_tbl.$tile_slide_tbl.$tile_slider_rel_tbl);
}

//defines items to be displayed per page
global $items;
$items = 2;

/*
 * Template function to trigger js on load of contents
 * tile_admin_js_trigger
 */

function tile_admin_js_trigger() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function(){jQuery(document).trigger("tile_interface_init")});
    </script>  
    <?php
}

/*
 * Add Admin Init option and Add pages to admin Menu
 * 
 */
add_action("admin_menu", "tile_admin_menu");

function tile_admin_menu() {
    add_menu_page("Tile Slider", "Tile Slider", "manage_options", "tile-slider", "tile_admin_page", plugin_dir_url(__FILE__) . "images/tile-slider-menu.gif");
}

/*
 * add css & javascript to admin panel
 */
add_action("admin_enqueue_scripts", "tile_admin_res");

function tile_admin_res() {
    wp_enqueue_style("tile-slider-css", plugin_dir_url(__FILE__) . "css/tile-slider-admin.css");
    wp_enqueue_style("tipTip-css", plugin_dir_url(__FILE__) . "css/tipTip.css");
    wp_enqueue_style("apprise-css", plugin_dir_url(__FILE__) . "css/apprise.min.css");
    wp_enqueue_script(array('jquery', 'media-upload', 'thickbox', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'farbtastic'));
    wp_enqueue_script("tipTip.min", plugin_dir_url(__FILE__) . "js/jquery.tipTip.minified.js");
    wp_enqueue_script("apprise.min", plugin_dir_url(__FILE__) . "js/apprise-1.5.min.js");
    //wp_enqueue_scripts(array("jQuery","jquery-ui-core","jquery-ui-draggable"));
    wp_enqueue_script("tile-admin", plugin_dir_url(__FILE__) . "js/admin-interface.js", array());
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

add_action("wp_head", 'tile_site_head');

function tile_site_head() {
    wp_enqueue_script('jquery');
    wp_enqueue_script("tile-easing", plugins_url("js/jquery.easing.1.3.js", __FILE__),array('jquery'));
    wp_enqueue_script("tile-slider", plugins_url("js/jquery.tile.slider.1.0.js", __FILE__),array('jquery'));
    wp_enqueue_script("tile-loader", plugins_url("js/tile-loader.js", __FILE__),array('jquery'));
    wp_enqueue_style("tile-slider-css", plugins_url("css/tile-slider.css", __FILE__));
}

/*
 * Admin Page
 */

function tile_admin_page() {
    global $wpdb, $tile_slides, $tile_pages, $tile_curr_page, $items;
    $tile_slides = get_tile_slides();
    $tile_sliders = get_tile_sliders();
    $tile_curr_page = isset($_REQUEST['tpage']) ? $_REQUEST['tpage'] : 1;
    $tile_pages = ceil(count($tile_slides) / $items);
    $tile_slides = array_slice($tile_slides, ($tile_curr_page - 1) * $items, $items);
    include_once dirname(__FILE__) . '/admin-page.php';
    tile_admin_js_trigger();
}

/*
 * AJAX function to delete an item
 * item-type
 * item-id
 */
add_action("wp_ajax_tile_delete_item", "tile_delete_item");

function tile_delete_item() {
    global $wpdb;
    $wpdb->show_errors();
    $sql = "DELETE FROM " . $wpdb->prefix . "tile_" . $_REQUEST['item-type'] . " WHERE id='" . $_REQUEST['item-id'] . "'";
    if ($wpdb->query($sql)) {
        echo "success";
    } else {
        echo "problem";
    }
    die();
}

/*
 * AjAX Function to add slide slider relation
 */
add_action("wp_ajax_add_tile_rel", "add_tile_rel");

function add_tile_rel() {
    global $wpdb, $tile_sliders;
    $i_data['slide_id'] = $_POST['slide'];
    $i_data['slider_id'] = $_POST['slider'];
    $i_data['slide_type'] = $_POST['slide_type'];
    if ($wpdb->insert($wpdb->prefix . "tile_slider_rel", $i_data)) {
        echo '<input type="hidden" class="ajax-stat" value="added"/>';
    } else {
        echo '<input type="hidden" class="ajax-stat" value="problem"/>';
    }
    $tile_sliders = get_tile_sliders();

    include_once dirname(__FILE__) . '/views/admin-slider-page.php';
    tile_admin_js_trigger();

    die();
}

/*
 * AjAX Function to remove slide slider relation
 */
add_action("wp_ajax_remove_tile_rel", "remove_tile_rel");

function remove_tile_rel() {
    global $wpdb;
    $rel = $_POST['rel'];
    $rel = explode(",", $rel);
    if ($wpdb->query("DELETE FROM " . $wpdb->prefix . "tile_slider_rel WHERE slide_id='" . $rel[0] . "' AND slider_id='" . $rel[1] . "'")) {
        echo "removed";
    } else {
        echo "problem";
    }
    die();
}

/*
 * AJAX functions to load pages
 * HTML
 * <a href="#add-slide" class="tile-admin-ajax"></a>
 * <input type="hidden" class="tile-admin-action" value="tile_slide_form" />
 */
add_action("wp_ajax_tile_slide_form", "tile_slide_form");

function tile_slide_form() {
    global $tile_slide;
    if (isset($_REQUEST['slide_id'])) {
        $tile_slide = tile_slide_by_id($_REQUEST['slide_id']);
    } else {
        
    }
    include_once dirname(__FILE__) . '/views/admin-slide-form.php';
    tile_admin_js_trigger();
    die();
}

add_action("wp_ajax_tile_slide_page", "tile_slide_page");

function tile_slide_page() {
    global $wpdb, $tile_slides, $tile_pages, $tile_curr_page, $items;
    $tile_slides = get_tile_slides();
    $tile_curr_page = isset($_REQUEST['tpage']) ? $_REQUEST['tpage'] : 1;
    $tile_pages = ceil(count($tile_slides) / $items);
    $tile_slides = array_slice($tile_slides, ($tile_curr_page - 1) * $items, $items);
    include_once dirname(__FILE__) . '/views/admin-slide-page.php';
    tile_admin_js_trigger();
    die();
}

add_action("wp_ajax_tile_slider_form", "tile_slider_form");

function tile_slider_form() {
    global $tile_slider;
    if (isset($_REQUEST['slider_id'])) {
        $tile_slider = tile_slider_by_id($_REQUEST['slider_id']);
    } else {
        /*
         * @TODO
         * declare default
         */
        //$tile_slider=$default;
    }
    include_once dirname(__FILE__) . '/views/admin-slider-form.php';
    tile_admin_js_trigger();
    die();
}

/*
 * Function to load a specific tile slider record by id
 */

function tile_slider_by_id($id) {
    global $wpdb;
    $sql = "SELECT * FROM " . $wpdb->prefix . "tile_slider WHERE id='" . $id . "'";
    $slider = $wpdb->get_row($sql, ARRAY_A);
    return $slider;
}

/*
 * Function to load a specific tile slide record by id
 */

function tile_slide_by_id($id) {
    global $wpdb;
    $sql = "SELECT * FROM " . $wpdb->prefix . "tile_slide WHERE id='" . $id . "'";
    $slide = $wpdb->get_row($sql, ARRAY_A);
    return $slide;
}

add_action("wp_ajax_tile_slider_page", "tile_slider_page");

function tile_slider_page() {
    global $wpdb, $tile_sliders;
    $tile_sliders = get_tile_sliders();
    if (isset($_REQUEST['slide_id'])) {
        echo "Edit Form";
    } else {
        include_once dirname(__FILE__) . '/views/admin-slider-page.php';
        tile_admin_js_trigger();
    }
    die();
}

add_action("wp_ajax_tile_slide_posts", "tile_slide_posts");

function tile_slide_posts() {
    global $wpdb, $tile_pages, $tile_curr_page, $tile_posts, $items;
    $tile_curr_page = (isset($_REQUEST['tpage'])) ? $_REQUEST['tpage'] : 1; // pagination
    $args = array(
        'posts_per_page' => $items, // optional to overwrite the dashboard setting
        'paged' => $tile_curr_page,
        'meta_key' => '_thumbnail_id'
    );
    $tile_posts = new WP_Query($args);
    $tile_pages = $tile_posts->max_num_pages;
    include_once dirname(__FILE__) . '/views/admin-slide-posts.php';
    tile_admin_js_trigger();

    die();
}

/*
 * Add a slide through ajax
 */
add_action("wp_ajax_add_tile_slide", "add_tile_slide");

function add_tile_slide() {
    global $wpdb;
    $wpdb->show_errors();
    if (isset($_REQUEST['slide_id'])) {
        $i_data['name'] = $_POST['slide_name'];
        $i_data['type'] = "tslide";
        $i_data['img_url'] = $_POST['slide_image'];
        if (!empty($_POST['slide_link'])) {
            $i_data['link'] = $_POST['slide_link'];
        }
        $i_data['desc'] = esc_attr($_POST['slide_desc']);
        $wpdb->update($wpdb->prefix . "tile_slide", $i_data, array("id" => $_REQUEST['slide_id']));
        $resp['success'] = "success";
        echo json_encode($resp);
    } else {
        $resp['message'] = array();
        $resp['success'] = "failure";
        if (!strlen($_POST['slide_name'])) {
            $resp['message'][] = "Slide Name Can't Be Empty";
        }
        if (!strlen($_POST['slide_image'])) {
            $resp['message'][] = "Please Select a Picture";
        }
        if (!count($resp['message'])) {
            $i_data['name'] = $_POST['slide_name'];
            $i_data['type'] = "tslide";
            $i_data['img_url'] = $_POST['slide_image'];
            $i_data['desc'] = esc_attr($_POST['slide_desc']);
            if (!empty($_POST['slide_link'])) {
                $i_data['link'] = $_POST['slide_link'];
            }
            $wpdb->insert($wpdb->prefix . "tile_slide", $i_data);
            $resp['success'] = "success";
        }
        echo json_encode($resp);
        //include_once dirname(__FILE__) . '/views/admin-slider-page.php';
    }
    die();
}

/*
 * Add a slider through ajax
 */
add_action("wp_ajax_add_tile_slider", "add_tile_slider");

function add_tile_slider() {
    global $wpdb;
    $wpdb->show_errors();
    if (isset($_REQUEST['slider_id'])) {
        $i_data['name'] = $_POST['slider_title'];
        $i_data['tile_duration'] = (is_numeric($_POST['tile_duration']) ? $_POST['tile_duration'] : 600);
        $i_data['animation_speed'] = (is_numeric($_POST['animation_speed']) ? $_POST['animation_speed'] : 2000);
        $i_data['animation_dir'] = (is_numeric($_POST['animation_dir']) ? $_POST['animation_dir'] : 0);
        $i_data['animation'] = $_POST['animation'];
        $i_data['tile_height'] = (is_numeric($_POST['tile_height']) ? $_POST['tile_height'] : 0);
        $i_data['tile_width'] = (is_numeric($_POST['tile_width']) ? $_POST['tile_width'] : 0);
        $i_data['thumb_height'] = (is_numeric($_POST['thumb_height']) ? $_POST['thumb_height'] : 0);
        $i_data['thumb_width'] = (is_numeric($_POST['thumb_width']) ? $_POST['thumb_width'] : 0);
        $i_data['slider_height'] = (is_numeric($_POST['slider_height']) ? $_POST['slider_height'] : 0);
        $i_data['slider_width'] = (is_numeric($_POST['slider_width']) ? $_POST['slider_width'] : 0);
        $i_data['slider_width'] = (is_numeric($_POST['slider_width']) ? $_POST['slider_width'] : 0);
        $i_data['interval'] = (is_numeric($_POST['interval']) ? $_POST['interval'] : 0);
        $wpdb->update($wpdb->prefix . "tile_slider", $i_data, array('id' => $_REQUEST['slider_id']));
        $resp['success'] = "success";
        echo json_encode($resp);
    } else {
        $resp['message'] = array();
        $resp['success'] = "failure";
        if (!strlen($_POST['slider_title'])) {
            $resp['message'][] = "Slider Name Can't Be Empty";
        }
        if (!count($resp['message'])) {
            /*
             * @todo
             * add default values from default variable
             */
            $i_data['name'] = $_POST['slider_title'];
            $i_data['tile_duration'] = (is_numeric($_POST['tile_duration']) ? $_POST['tile_duration'] : 600);
            $i_data['animation_speed'] = (is_numeric($_POST['animation_speed']) ? $_POST['animation_speed'] : 2000);
            $i_data['animation_dir'] = (is_numeric($_POST['animation_dir']) ? $_POST['animation_dir'] : 0);
            $i_data['animation'] = $_POST['animation'];
            $i_data['tile_height'] = (is_numeric($_POST['tile_height']) ? $_POST['tile_height'] : 0);
            $i_data['tile_width'] = (is_numeric($_POST['tile_width']) ? $_POST['tile_width'] : 0);
            $i_data['thumb_height'] = (is_numeric($_POST['thumb_height']) ? $_POST['thumb_height'] : 0);
            $i_data['thumb_width'] = (is_numeric($_POST['thumb_width']) ? $_POST['thumb_width'] : 0);
            $i_data['slider_height'] = (is_numeric($_POST['slider_height']) ? $_POST['slider_height'] : 0);
            $i_data['slider_width'] = (is_numeric($_POST['slider_width']) ? $_POST['slider_width'] : 0);
            $i_data['slider_width'] = (is_numeric($_POST['slider_width']) ? $_POST['slider_width'] : 0);
            $i_data['interval'] = (is_numeric($_POST['interval']) ? $_POST['interval'] : 0);
            $wpdb->insert($wpdb->prefix . "tile_slider", $i_data);
            $resp['success'] = "success";
        }
        echo json_encode($resp);
        //include_once dirname(__FILE__) . '/views/admin-slider-page.php';
    }
    die();
}

/*
 * Function to fetch tile slides available in system
 */

function get_tile_slides() {
    global $wpdb;
    $offset = 0;
    if (isset($_REQUEST['page'])) {
        $offset = $_REQUEST['page'];
    }
    $sql = " SELECT * FROM " . $wpdb->prefix . "tile_slide WHERE type='tslide' ";
    $slides = $wpdb->get_results($sql, ARRAY_A);
    return $slides;
}

/*
 * Function to fetch tile sliders in system
 */

function get_tile_sliders() {
    global $wpdb;
    $offset = 0;
    if (isset($_REQUEST['page'])) {
        $offset = $_REQUEST['page'];
    }
    $sql = " SELECT * FROM " . $wpdb->prefix . "tile_slider";
    $sliders = $wpdb->get_results($sql, ARRAY_A);
    foreach ($sliders as $key => $slider) {
        $sliders[$key]['slides'] = get_tile_slides_by_id($slider['id']);
    }
    return $sliders;
}

/*
 * Function to featch slides by id of a slider
 */

function get_tile_slides_by_id($slider_id) {
    global $wpdb;
    $wpdb->show_errors();
    $sql = "SELECT * FROM " . $wpdb->prefix . "tile_slider_rel JOIN " . $wpdb->prefix . "tile_slide ON " . $wpdb->prefix . "tile_slider_rel.slide_id=" . $wpdb->prefix . "tile_slide.id WHERE " . $wpdb->prefix . "tile_slider_rel.slider_id='" . $slider_id . "'  AND " . $wpdb->prefix . "tile_slider_rel.slide_type='tslide'";
    $slides = $wpdb->get_results($sql, ARRAY_A);
    $sql = "SELECT * FROM " . $wpdb->prefix . "tile_slider_rel WHERE slider_id='" . $slider_id . "' AND slide_type='tpost'";
    $posts = $wpdb->get_results($sql, ARRAY_A);
    return array_merge($slides, $posts);
}

/*
 * Template Function
 * get_tile_slide_nav()
 * Admin nav for slide screen
 */

function get_tile_slide_nav($page = 'slides') {
    ?>
    <div class="tile-nav-item right-side">
        <span>
            <a href="#add-slide" class="add-new-h2 tile-admin-ajax <?php echo ($page == 'addnew') ? 'tile-active' : ''; ?>" id="tile-add-slide">+ Add New</a>
            <input type="hidden" class="tile-admin-action" value="tile_slide_form" />
        </span>
        <span>
            <a href="#" class="add-new-h2 tile-admin-ajax <?php echo ($page == 'slides') ? 'tile-active' : ''; ?>" id="tile-slides">Slides</a>
            <input type="hidden" class="tile-admin-action" value="tile_slide_page" />
        </span>
        <span>
            <a href="#" class="add-new-h2 tile-admin-ajax <?php echo ($page == 'posts') ? 'tile-active' : ''; ?>">Posts</a>
            <input type="hidden" class="tile-admin-action" value="tile_slide_posts" />
        </span>
    </div>
    <?php
}

/*
 * Template Function
 * get_tile_slider_nav()
 * Admin nav for slider screen
 */

function get_tile_slider_nav() {
    ?>
    <span>
        <a href="#add-slider" id="tile-add-slider" class="add-new-h2 tile-admin-ajax">+ Add New</a>
        <input type="hidden" class="tile-admin-action" value="tile_slider_form" />
    </span>
    <span>
        <a href="#" class="add-new-h2 tile-admin-ajax" id="tile-sliders">Sliders</a>
        <input type="hidden" class="tile-admin-action" value="tile_slider_page" />
    </span>
    <?php
}

function tile_slider($atts = array()) {
    extract(shortcode_atts(array(
                'id' => ''
                    ), $atts));
    $slider=  tile_slider_by_id($id);
    $slides = get_tile_slides_by_id($id);
    if (!count($slides)) {
        //empty slide
        return "all empty";
        return false;
    } else {
        $id = uniqid('tile_slider_');
        $str = '<div class="tile-container">
            <div class="tile-slideshow" id="' . $id . '" style="height:'.$slider['slider_height'].'px;">';
        foreach ($slides as $slide) {
            if ($slide['type'] == 'tslide') {
                if (!empty($slide['desc'])) {
                    $desc_id = uniqid('tile-caption-');
                    $str.='<div id="' . $desc_id . '">
                            <h4>' . $slide['name'] . '</h4>
                        <p>' . tileCaptionTrim($slide['desc'], 15) . '</p>
                        </div>';
                    $desc_id = '#' . $desc_id;
                } else {
                    $desc_id = $slide['name'];
                }
                $str.=(!empty($slide['link'])) ? '<a href="' . $slide['link'] . '">' : '';
                $str.='<img src="' . wp_get_attachment_url($slide['img_url']) . '" title="' . $desc_id . '"/>';
                $str.=(!empty($slide['link'])) ? '</a>' : '';
            } else {
                $post = get_post($slide['slide_id']);
                $desc_id = uniqid('tile-caption-');
                $str.='<div id="' . $desc_id . '">
                            <h4>' . $post->post_title . '</h4>
                        <p>' . tileCaptionTrim($post->post_content, 15) . '</p>
                        </div>';
                $desc_id = '#' . $desc_id;
                $str.='<a href="' . get_permalink($post->ID) . '"><img src="' . wp_get_attachment_url(get_post_thumbnail_id($post->ID)) . '" title="' . $desc_id . '"/></a>';
            }
        }
        $str.='</div></div>';
        $obj['tileWidth']=$slider['tile_width'];
        $obj['tileHeight']=$slider['tile_height'];
        $obj['thumbWidth']=$slider['thumb_width'];
        $obj['thumbHeight']=$slider['thumb_height'];
        $obj['tansitionSpeed']=$slider['animation_speed'];
        $obj['tileDuration']=$slider['tile_duration'];
        switch($slider['animation_dir']){
            case 1:
                $obj['direction']='top';
                break;
            case 2:
                $obj['direction']='left';
                break;
            case 3:
                $obj['direction']='right';
                break;
            case 4:
                $obj['direction']='bottom';
                break;
            case 5:
                $obj['direction']='topLeft';
                break;
            case 6:
                $obj['direction']='bottomLeft';
                break;
            case 7:
                $obj['direction']='topRight';
                break;
            case 8:
                $obj['direction']='bottomRight';
                break;
            case 9:
                $obj['direction']='random';
                break;
            default:
                $obj['direction']='random';
                break;
        }
        if(!$slider['interval']){
            $obj['autoplay']='false';
            $obj['interval']=0;
        }else{
            $obj['autoplay']='true';
            $obj['interval']=$slider['interval'];
        }
        $obj['animation']=$slider['animation'];
        $str.='<script type="text/javascript">window.'.$id.'='.json_encode($obj).';</script>';
        return $str;
    }
}

add_shortcode('tile-slider', 'tile_slider');

add_action('wp_head','tile_loader_js');

function tile_loader_js(){
    global $tile_js;
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            
        })
    </script>
        <?php
}

function tileCaptionTrim($string, $words = 1) {
    $string = explode(' ', $string);

    if (count($string) > $words) {
        return implode(' ', array_slice($string, 0, $words)) . '...';
    }

    return implode(' ', $string);
}

/*
 * script
 * title cutting script
 */
?>