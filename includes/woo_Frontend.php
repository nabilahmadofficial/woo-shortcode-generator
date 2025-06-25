<?php

class woo_Frontend {
	
	protected static $instance = NULL;

	public function __construct () {
		add_shortcode( 'woo_product_layout', array( $this, 'woo_product_layout' ) );

	}

	public function woo_product_layout($atts) {
		
        $atts = shortcode_atts(array(
            'id' => '',
        ), $atts);

        if (!empty($atts['id'])) {
            $post_id = intval($atts['id']);

            // Now you can use $product_id in your layout logic
            // Example: Fetch and display product details based on $product_id
            
            ob_start();
            woo_returndata($post_id);
            $html_output = ob_get_clean();

            return $html_output;
        } else {
            // Handle the case where 'id' attribute is missing
            return "<p>Error: Missing 'id' attribute in the shortcode.</p>";
        }
    }

	

}

?>