

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
			<li rel="comment_ID ) ; ?>" class="">
				<div class="note_content">
					comment_content ) ) ); ?>
				</div>
				<p class="meta">
					comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?>
				</p>
			</li>
			<?php
		}
	} else {
		echo '<li>' . __( 'There are no notes for this order yet.', 'woocommerce' ) . '</li>';
	}

	echo '</ul>';
}