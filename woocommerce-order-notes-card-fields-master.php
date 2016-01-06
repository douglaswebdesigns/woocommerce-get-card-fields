<?php
/*
Plugin Name: WooCommerce Order Notes Card Fields
Plugin URI: http://www.ordernotescardfields.com/
Description: Powerful plugin designed to retrieve notes from the comment_content column of the WordPress Database to use in admin emails.
Version: 1.0.0
Author: Paul Douglas
Author URI: http://www.douglaswebdesigns.com/
Copyright: Douglas Web Designs
Text Domain: Order Notes Card Fields
*/
?>

<?php
// WooCommerce New Customer Admin Notification Email
add_action('woocommerce_created_customer', 'admin_email_on_registration');
function admin_email_on_registration() {
    $user_id = get_current_user_id();
    wp_new_user_notification( $user_id );
}


// WooCommerce Add Payment Method Definition
add_action( 'woocommerce_email_after_order_table', 'add_payment_method_to_admin_new_order', 15, 2 );
 
function add_payment_method_to_admin_new_order( $order, $is_admin_email ) {
  if ( $is_admin_email ) {
    echo '<p><strong>Payment Method:</strong> ' . $order->payment_method_title . '</p>';
  }
}

// WooCommerce Adds a link in the WooCmmerce > Settings > Email page to display email templates
$preview = get_stylesheet_directory() . '/woocommerce/emails/woo-preview-emails.php';

if(file_exists($preview)) {
    require $preview;
}

// WooCommerce Adds Order Notes to Admin Email
add_action( 'woocommerce_email_order_meta', 'woo_add_order_notes_to_email' );

function woo_add_order_notes_to_email() {

	global $woocommerce, $post;

	$args = array(
		'post_id' 	=> $post->ID,
		'approve' 	=> 'approve',
		'type' 		=> 'order_note'
	);

	$notes = get_comments( $args );
	
	echo '<h2>' . __( 'Order Notes', 'woocommerce' ) . '</h2>';

	echo '<ul class="order_notes">';

	if ( $notes ) {
		foreach( $notes as $note ) {
			$note_classes = get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ? array( 'customer-note', 'note' ) : array( 'note' );

			?>
			<li rel="<?php echo absint( $note->comment_ID ) ; ?>" class="<?php echo implode( ' ', $note_classes ); ?>">
				<div class="note_content">
					(<?php printf( __( 'added %s ago', 'woocommerce' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?>) <?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
				</div>
			</li>
			<?php
		}
	} else {
		echo '<li>' . __( 'There are no notes for this order yet.', 'woocommerce' ) . '</li>';
	}

	echo '</ul>';
}



?>