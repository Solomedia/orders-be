<?php
/*
* Plugin Name: Orders BE
* Plugin URI: http://www.llt-group.com
* Description: Orders BE
* Version: 0.1
* Author: Marcos Robles, Moises Gonzalez
* Author URI: http://www.llt-group.com
* Text Domain: _llt_mgrg
*/
if(!defined('ABSPATH')) exit;
if(is_admin()) {
add_action('current_screen', 'llt_check_screen_041194');
function llt_check_screen_041194() {
$dpvarcurrent_screen = get_current_screen();
$page_init_admin = 'toplevel_page_cancelled-order-dt';
if($dpvarcurrent_screen->id == $page_init_admin) {
add_action( 'admin_enqueue_scripts', '_llt_queue_scripts_orderBE' );
function _llt_queue_scripts_orderBE() {
wp_enqueue_script('datables-js',esc_url(plugins_url('assets/js/datatables.min.js', __FILE__)), array('jquery'));
wp_enqueue_script('orderBE-js',esc_url(plugins_url('assets/js/script.js', __FILE__)), array('jquery'));
wp_register_style('datatables-css',esc_url(plugins_url('assets/css/datatables.min.css', __FILE__)));
wp_register_style('llt_BEORmain-css',esc_url(plugins_url('assets/css/main.css', __FILE__)));
wp_enqueue_style('datatables-css');
wp_enqueue_style('llt_BEORmain-css');
}
}
}
}

add_action( 'admin_menu', '_llt_admin_mn_pg_cl' );
function _llt_admin_mn_pg_cl() {
	add_menu_page(
		'Cancelled orders', 
		'Cancelled orders', 
		'manage_options', 
		'cancelled-order-dt',
		'_llt_ord_cancl',
		'',
		6
	);

	function _llt_ord_cancl() {
		if(isset($_GET['status']) && !isset($_GET['from']) && !isset($_GET['to'])) {
			$status = $_GET['status'];
			$query = new WC_Order_Query(array(
				'limit' 	=> 100,
				'orderby' 	=> 'date',
				'order' 	=> 'DESC',
				'status' 	=> $status
			));
		} else if(isset($_GET['from']) && isset($_GET['to']) && !isset($_GET['status'])) {

			$from = $_GET['from'];
			$to = $_GET['to'];
			$query = new WC_Order_Query(array(
				'limit' 	=> 20,
				'orderby' 	=> 'date',
				'order' 	=> 'DESC',
				'date_created' 	=> $from.'...'.$to
			));
		} else if(isset($_GET['from']) && isset($_GET['to']) && isset($_GET['status'])) {
			$from = $_GET['from'];
			$to = $_GET['to'];
			$status = $_GET['status'];
			$query = new WC_Order_Query(array(
				'limit' 	=> 20,
				'orderby' 	=> 'date',
				'order' 	=> 'DESC',
				'status'	=> $status,
				'date_created' 	=> $from.'...'.$to
			));
		} else {
			$query = new WC_Order_Query(array(
				'limit' 	=> 9999,
				'orderby' 	=> 'date',
				'order' 	=> 'DESC'
			));			
		}
		
		$orders = $query->get_orders();
		?>
		<style>
			.loadmt {
				pointer-events: none !important;
    			opacity: 0.3;
			}
		</style>
		<form class="form-cancel" action="<?php echo esc_url(plugins_url('includes/cancel.php', __FILE__)); ?>" m_action="<?php echo esc_url(plugins_url('includes/mcancel.php', __FILE__)); ?>">
			<table id="orders_table" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>UPC#</th>
						<th>Status</th>
						<th>Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $orders as $order ): ?>
					<tr order-id="<?= $order->ID; ?>">
						<td>
							<?php echo $order->ID; ?>
						</td>
						<td>
							<?php
								echo get_field('item_upc', $order->ID);
							?>
						</td>
						<td>
							<?php echo $order->status; ?>
						</td>
						<td>
							<?php echo $order->date_created->date_i18n(); ?>
						</td>
						<td>
							<?php 
							if($order->status != 'cancelled'):
							?>
								<a  class="btn btn-danger" data-toggle="modal" data-target="#exampleModa<?php echo $order->ID; ?>" style="color:#fff;">
								Cancel order
								</a>
							<?php
							endif;
							?>
						</td>
					</tr>
<div class="modal fade" id="exampleModa<?php echo $order->ID; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CANCEL ORDER UPC#  <?php echo get_field('item_upc', $order->ID); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	      <div class="table">
	      		<?php 
	      		$_order = wc_get_order( $order->ID );
				$_items = $_order->get_items();
				foreach($_items as $_item) {
					$product_id = get_field('item_id', $order->ID);
					$_product = wc_get_product( $product_id );
					
					echo '<div class="td"><b>Product Name:</b>';
					echo $_item->get_name();
					echo '<p><small>product ID:'.$product_id.'</small></p>';
					echo '</div>';

					echo '<div class="td"><b>Start date:</b>';
					echo get_field('item_start_date', $order->ID);
					echo '<b>End date:</b>';
					echo get_field('item_end_date', $order->ID);
					echo '</div>';
				
					echo '<div class="td"><b>Order status:</b>';
					echo $order->status;
					echo '</div>';

					echo '<div class="td"><b>Customer email:</b>';
					$__order = new WC_Order($order->ID);
					$customer_email = $__order->get_billing_email();
					echo $customer_email;
					echo '</div>';
				}
	      		?>
	      </div>
      </div>
      <div class="modal-footer" style="justify-content:center!important;">
		<input type="hidden" name="order-id" value="<?php echo $order->ID; ?>">
		<button class="btn btn-danger cnl" btn-id="<?php echo $order->ID; ?>" type="submit">
		Cancel this order
		</button>
      </div>
    </div>
  </div>
</div>
					<?php endforeach; ?>
				</tbody>
			</table>
		</form>
		<button id="button" class="btn btn-primary">CANCEL MULTIPLE ORDERS</button>
		<div id="validationModal" aria-labelledby="validationModal" class="modal" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title">Warning</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<p>Are you sure to cancel the following orders?:</p>
				<p id="validationModalValue"></p>
			  </div>
			  <div class="modal-footer">
				<button type="button" id="validationModalButton" class="btn btn-primary">Yes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
			  </div>
			</div>
		  </div>
		</div>
		<?php
		//include_once('includes/cancel.php');
	}
}
?>