# Woo Shortcode Generator WordPress Plugin

## Description
A WordPress plugin that generates a shortcode to display selected WooCommerce products in a customizable grid or list layout. Includes a custom post type for product widgets, taxonomy filters, and a widget for easy integration.

## Features
- Shortcode `[woo_product_layout id="X"]` to display products
- Custom post type `product_widget` for managing product displays
- Grid or list layout options
- Filter products by taxonomy, featured status, or specific selection
- Customizable thumbnail display, sorting, and column settings
- Responsive design with CSS styling
- Widget support for displaying product lists

## Usage
1. Navigate to **Product Widget** in the WordPress admin to create a new widget.
2. Configure settings (layout, product type, taxonomy, etc.) in the meta box.
3. Copy the generated shortcode (e.g., `[woo_product_layout id="X" title="Y"]`) and add it to a page or post.
4. Alternatively, use the "GM Woo Product List Widget" in a widget area to display products.

## File Structure
- `woo-product-shortcode.php`: Main plugin file
- `includes/woo_Admin.php`: Admin functionality and meta box
- `includes/woo_Comman.php`: Common functions for product retrieval
- `includes/woo_Frontend.php`: Shortcode and frontend logic
- `includes/woo_Widget.php`: Widget for product display
- `css/style.css`: Frontend styles
- `css/admin-style.css`: Admin styles
- `js/admin-script.js`: Admin JavaScript for dynamic taxonomy updates

## Customization
- Modify `css/style.css` to adjust frontend styles.
- Update `woo_Admin.php` to add new settings or modify meta box fields.
- Extend `woo_Frontend.php` for custom shortcode output.
- Adjust `woo_Widget.php` for widget customization.

## License
GPL-2.0+ (See [LICENSE](LICENSE) file)
