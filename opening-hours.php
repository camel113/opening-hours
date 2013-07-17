<?php
/* Plugin Name: Opening Hour
*/
add_action('wp_enqueue_scripts', 'abg_add_styles');
function abg_add_styles() {
  wp_register_style('opening-hours_css', plugins_url('css/style.css', __FILE__));
  wp_enqueue_style('opening-hours_css');
}

add_shortcode("schedule", "my_shortcode_function");

function my_shortcode_function($atts,$content) {

    extract(shortcode_atts(array(

               'id' => ''), $atts));

    $opening_hours = get_post_meta($id, "_abg_opening_hours", true);
    $opening_hours = ($opening_hours != '') ? json_decode($opening_hours) : array();

    return "<table>
              <tr>
                <th>Monday</th>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <th>Monday</th>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <th>Monday</th>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <th>Monday</th>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <th>Monday</th>
                <td></td>
                <td></td>
              </tr>
              </table>";


}
add_action('init', 'abg_register_opening_hours');

function abg_register_opening_hours() {

    $labels = array(

       'menu_name' => 'Opening Hours',
       'add_new_item' => 'Add new schedule',
       'singular_name' => 'Schedule',
       'name' => 'Schedules'

    );

    $args = array(

       'labels' => $labels,

       'hierarchical' => true,

       'description' => 'Slideshows',

       'supports' => 'title',

       'public' => true,

       'show_ui' => true,

       'show_in_menu' => true,

       'show_in_nav_menus' => true,

       'publicly_queryable' => true,

       'exclude_from_search' => false,

       'has_archive' => true,

       'query_var' => true,

       'can_export' => true,

       'rewrite' => true,

       'capability_type' => 'post'

    );

    register_post_type('opening_hours', $args);

}

add_action('add_meta_boxes', 'abg_plugin_meta_box');

add_action('admin_init','my_meta_init');
function my_meta_init()
{
    wp_register_style('opening-hours_css', plugins_url('css/admin.css', __FILE__));
    wp_enqueue_style('opening-hours_css');
  }

function abg_plugin_meta_box() {

    add_meta_box("abg-opening-hours-metabox", "Opening Hours", 'abg_view_metabox', "opening_hours", "normal");

}

function abg_view_metabox() {
    global $post;

    $opening_times = get_post_meta($post->ID, "_abg_opening_hours", true);
    // print_r($gallery_images);exit;
    $opening_times = ($opening_times != '') ? json_decode($opening_times) : array();

    // Use nonce for verification
    $html =  '<input type="hidden" name="abg_box_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';

    $html .= ''; 
    $html .= '
    <div class="abg-from">
      <label for="Upload Schedule"><span class="abg-day">Monday</span> from</label>
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[0].'" />
    </div>
    <div class="abg-to">  
      <label for="Upload Images">To</label>
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[1].'" />
    </div>  
    <div class="abg-from">
      <label for="Upload Schedule"><span class="abg-day">Tuesday</span> from</label>
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[2].'" />
    </div>
    <div class="abg-to">  
      <label for="Upload Images">To</label>  
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[3].'" />
    </div>  
    <div class="abg-from">
      <label for="Upload Schedule"><span class="abg-day">Wednesday</span> from</label>
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[4].'" />
    </div>
    <div class="abg-to">
      <label for="Upload Images">To</label>  
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[5].'" />
    </div>  
    <div class="abg-from">
      <label for="Upload Schedule"><span class="abg-day">Thursday</span> from</label>
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[6].'" />
    </div>  
    <div class="abg-to">
      <label for="Upload Images">To</label>  
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[7].'" />
    </div>  
    <div class="abg-from">
      <label for="Upload Schedule"><span class="abg-day">Friday</span> from</label>
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[8].'" />
    </div>
    <div class="abg-to">
      <label for="Upload Images">To</label>    
      <input id="fwds_slider_upload" type="text" name="opening_times[]" value="'.$opening_times[9].'" />
    </div>  
      ';
    echo $html;
}
add_action('save_post', 'abg_save_opening_hours');

function abg_save_opening_hours($post_id) {

    // verify nonce

    if (!wp_verify_nonce($_POST['abg_box_nonce'], basename(__FILE__))) {

       return $post_id;

    }

    // check autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {

       return $post_id;

    }

    // check permissions

    if ('opening_hours' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {

       /* Save Slider Images */


       //print_r($_POST['gallery_img']);exit;

       $opening_times= (isset($_POST['opening_times']) ? $_POST['opening_times'] : '');

       $opening_times = strip_tags(json_encode($opening_times));

       //print_r($opening_times);exit;

       update_post_meta($post_id, "_abg_opening_hours", $opening_times);

       return $post_id;

    } else {

       return $post_id;

    }
}
/* Define shortcode column in Rhino Slider List View */
add_filter('manage_edit-opening_hours_columns', 'abg_set_custom_edit_opening_hours_columns');
add_action('manage_opening_hours_posts_custom_column', 'abg_custom_opening_hours_column', 10, 2);

function abg_set_custom_edit_opening_hours_columns($columns) {
    return $columns
            + array('schedule_shortcode' => __('Shortcode'));
}

function abg_custom_opening_hours_column($column, $post_id) {

    $schedule_meta = get_post_meta($post_id, "_fwds_plugin_meta", true);
    $schedule_meta = ($schedule_meta != '') ? json_decode($schedule_meta) : array();

    switch ($column) {
        case 'schedule_shortcode':
            echo "[schedule id='$post_id' /]";
            break;

    }
}