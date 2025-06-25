<?php

/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class woo_Admin {
	
	protected static $instance = NULL;


	public function __construct () {
		add_action( 'init', array( $this, 'woo_init' ) );
		add_action( 'add_meta_boxes', array($this, 'woo_add_meta_box'));
		add_action('admin_enqueue_scripts', array($this, 'woo_scripts'));
		
		add_action( 'wp_ajax_gmwqp_change_tax', array( $this, 'gmwqp_change_tax' ));
		add_action( 'wp_ajax_nopriv_gmwqp_change_tax', array( $this, 'gmwqp_change_tax' ));

		add_action( 'edit_post', array($this, 'woo_meta_save'), 10, 2);
	}

	public function woo_init () {
		
		$args = array(
            'label'  => __( 'Product Widget', 'gmwrpm' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_admin_bar'  => true,
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'menu_position'      => null,
            'supports'  => array( 'title' ),
        );
        register_post_type( 'product_widget', $args );
    }

	public function woo_add_meta_box() {
            add_meta_box(
                'woo_metabox',
                __( 'Product Widget Settings', 'gmwrpm' ),
                array($this, 'woo_metabox_rule'),
                'product_widget',
                'normal'
            );
   }
   public function woo_meta_save($post_id, $post) {
    if ($post->post_type != 'product_widget') {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save selected productproducts when 'all' is chosen
    if (isset($_POST['woo_select_type']) && $_POST['woo_select_type'] === 'all') {
        $selected_productproducts = isset($_POST['woo_selected_productproducts']) ? array_map('intval', $_POST['woo_selected_productproducts']) : array();
        update_post_meta($post_id, 'woo_selected_productproducts', $selected_productproducts);
    } else {
        // Delete the saved selected productproducts meta when 'all' is not chosen
        delete_post_meta($post_id, 'woo_selected_productproducts');
    }

    update_post_meta($post_id, 'woo_select_type', sanitize_text_field($_POST['woo_select_type']));
    update_post_meta($post_id, 'woo_product_show', intval($_POST['woo_product_show']));
    update_post_meta($post_id, 'woo_show_per_column', intval($_POST['woo_show_per_column']));
    update_post_meta($post_id, 'woo_thum', sanitize_text_field($_POST['woo_thum']));
    update_post_meta($post_id, 'woo_order_by', sanitize_text_field($_POST['woo_order_by']));
    update_post_meta($post_id, 'woo_order', sanitize_text_field($_POST['woo_order']));
    update_post_meta($post_id, 'woo_select_tax_val', sanitize_text_field($_POST['woo_select_tax_val']));
    update_post_meta($post_id, 'woo_layout', sanitize_text_field($_POST['woo_layout']));
}
	public function woo_metabox_rule($post) {
    $woo_select_type = get_post_meta($post->ID, 'woo_select_type', true);
    $woo_product_show = get_post_meta($post->ID, 'woo_product_show', true);
    if (empty($woo_product_show)) {
        $woo_product_show = 3;
    }
    $woo_show_per_column = get_post_meta($post->ID, 'woo_show_per_column', true);
    if (empty($woo_show_per_column)) {
        $woo_show_per_column = 3;
    }
    $woo_thum = get_post_meta($post->ID, 'woo_thum', true);
    if (empty($woo_thum)) {
        $woo_thum = 'yes';
    }
    $woo_order_by = get_post_meta($post->ID, 'woo_order_by', true);
    $woo_order = get_post_meta($post->ID, 'woo_order', true);
    $woo_select_tax_val = get_post_meta($post->ID, 'woo_select_tax_val', true);
    $woo_layout = get_post_meta($post->ID, 'woo_layout', true);
    if (empty($woo_layout)) {
        $woo_layout = 'list';
    }
    $args = array(
        'post_type' => 'productproduct',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $productproducts = get_posts($args);
	$selected_productproducts = get_post_meta($post->ID, 'woo_selected_productproducts', true) ?: array();
	
   	?>
   	<div class="woo_settings">
   		<table>
   			<tr>
   				<td>Shortcode</td>
   				<td> 
   					<code>[woo_product_layout id="<?php echo $post->ID;?>" title= "<?php echo sanitize_title(get_the_title($post->ID)); ?>"]</code>
   				</td>
   			</tr>
   			<tr>
   				<td>Display Layout</td>
   				<td>
				   <input  name="woo_layout" type="radio" value="grid" <?php checked( $woo_layout, 'grid' ); ?> /> Grid
   					<input  name="woo_layout" type="radio" value="list" <?php checked( $woo_layout, 'list' ); ?> /> Single Product
   				</td>
   			</tr>
   			<tr>
   				<td>Select Type</td>
   				<td>
   					<?php
   					$taxonomies=get_object_taxonomies( 'product', 'objects' ); 
			
					$taxc = array();
					foreach ($taxonomies as $key => $value) {
						if($value->show_ui){
							$taxc[$key] = $value->label;
						}
					}
   					?>
   					<input  
   					name="woo_select_type" 
   					type="radio" 
   					value="all"  
   					iscal="no" 
   					<?php checked( $woo_select_type, 'all' ); ?>  
   					class="changecat"/> All<br/>
   					<input  
   					name="woo_select_type" 
   					type="radio" 
   					value="featured"  
   					iscal="no" 
   					<?php checked( $woo_select_type, 'featured' ); ?>  
   					class="changecat"/> Featured<br/>
   					<?php 
					// Remove the options for 'Product Brand', 'Product Color', and 'Product Size'
					$exclude_taxonomies = array('product_brand', 'product_color', 'product_size');

					foreach($taxc as $taxckey=>$taxcval){
					    if(!in_array($taxckey, $exclude_taxonomies)){
 					       $isselcted = (($woo_select_type ==$taxckey) ? 'checked' : '');
					        echo '<input  
 					           name="woo_select_type" 
 						           type="radio" 
  						          value="'.$taxckey.'" 
   							         iscal="yes"  
      							      '.$isselcted.' 
       							     class="changecat"/> '.$taxcval.'<br/>';
 									   }
}
					?>
   					
   				</td>
   			</tr>
   			<?php 
   			if (array_key_exists($woo_select_type, $taxc)) {
   				$istax=true;
   			}else{
   				$istax=false;
   			}
   			?>
   			<tr class="showc_taxonomy_val" style="<?php echo ($istax == true) ? 'display: table-row' : ''; ?>">
   				<td>Select Taxonomy Value</td>
   				<td>
				   <?php
                        if ($istax == true) {
                            if (class_exists('WooCommerce')) {
                                $terms = get_terms($woo_select_type, array(
                                    'hide_empty' => false,
                                ));
                                $taxc = array();
                                foreach ($terms as $key => $value) {
                                    $taxc[$value->term_id] = $value->name;
                                }
                            } else {
                                // Handle the case where WooCommerce is not active
                                // You might want to add a fallback behavior here
                            }
                        }
                        ?>
   					<select class='changetax_val widefat' name="woo_select_tax_val" >
   						<?php 
   						if($istax == true){
	   						foreach($taxc as $taxckey=>$taxcval){
	   							echo '<option value="'.$taxckey.'" '.(($woo_select_tax_val==$taxckey) ? 'selected' : '').'>'.$taxcval.'</option>';
	   						}
	   					}
   						?>
					</select>
   				</td>
   			</tr>
            <tr class="showc_products" style="<?php echo ($woo_select_type == 'all') ? 'display: table-row' : 'display: none'; ?>">
    <td>Select Products</td>
    <td>
        <?php
       $args = array(
		'post_type'      => 'productproduct', // Change to your custom post type slug
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	);
	$productproducts = get_posts($args);
		
        $selected_products = get_post_meta($post->ID, 'woo_selected_products', true) ?: array();
        ?>
        <select class="widefat" name="woo_selected_productproducts[]" multiple>
    <?php
    foreach ($productproducts as $productproduct) {
        $selected = in_array($productproduct->ID, $selected_productproducts);
        echo '<option value="' . $productproduct->ID . '" ' . ($selected ? 'selected' : '') . '>' . $productproduct->post_title . '</option>';
    }
    ?>
</select>
    </td>
</tr>
   			<tr>
   				<td>No of Products Per Column <i>(Just work for grid layout)</i></td>
   				<td>
   					<input type="number" name="woo_show_per_column"  class="widefat" value="<?php echo esc_attr($woo_show_per_column); ?>">
   				</td>
   			</tr>
   			<tr>
   				<td>No of Products </td>
   				<td>
   					<input type="number" name="woo_product_show"  class="widefat" value="<?php echo esc_attr($woo_product_show); ?>">
   				</td>
   			</tr>
   			<tr>
   				<td>Show product thumbnails?</td>
   				<td>
   					<input  name="woo_thum" type="radio" value="yes" <?php checked( $woo_thum, 'yes' ); ?> /> Yes
					<input  name="woo_thum" type="radio" value="no" <?php checked( $woo_thum, 'no' ); ?> /> No
   				</td>
   			</tr>
   			
   			<tr>
   				<td>Order By</td>
   				<td>
   					<select  name="woo_order_by"  class="widefat">
						<option value='post_title' <?php echo ($woo_order_by == 'post_title') ? 'selected' : ''; ?>>Product Name</option>
						<option value='id' <?php echo ($woo_order_by == 'id') ? 'selected' : ''; ?>>Product ID</option>
						<option value='date' <?php echo ($woo_order_by == 'date') ? 'selected' : ''; ?>>Date Published</option>
						<option value='modified' <?php echo ($woo_order_by == 'modified') ? 'selected' : ''; ?>>Last Modified</option>
						<option value='rand' <?php echo ($woo_order_by == 'rand') ? 'selected' : ''; ?>>Random</option>
						<option value='total_sales' <?php echo ($woo_order_by == 'total_sales') ? 'selected' : ''; ?>>Total Sales</option>
						<option value='none' <?php echo ($woo_order_by == 'none') ? 'selected' : ''; ?>>None</option>
					</select>
   				</td>
   			</tr>
   			<tr>
   				<td>Order By</td>
   				<td>
   					<select  name="woo_order" class="widefat">
						<option value='ASC' <?php echo ($woo_order == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
						<option value='DESC' <?php echo ($woo_order == 'DESC') ? 'selected' : ''; ?>>Descending</option>
					</select>
   				</td>
   			</tr>
   		</table>
   	</div>
   	<?php
   }

	public static function get_instance()
    {
        if ( NULL === self::$instance )
            self::$instance = new self;

        return self::$instance;
    }
	
	
	public function gmwqp_change_tax() {
        $htmlfinal = '';
        $terms = get_terms( sanitize_text_field($_REQUEST['option']), array(
                            'hide_empty' => false,
                        ) );
        foreach ($terms as $key => $value) {
            $htmlfinal .= '<option value="'.$value->term_id.'" >'.$value->name.'</option>';
        }
        echo html_entity_decode(esc_html($htmlfinal)) ;

        exit;
    }
	


	public function woo_scripts(){
		wp_enqueue_script('wooadmin-script', woo_PLUGIN_URL . '/js/admin-script.js', array(), '1.0.0', true );
		wp_localize_script( 'wooadmin-script', 'woo_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_style('woo_admin_css', woo_PLUGIN_URL.'css/admin-style.css');
	}

	

}

?>