<?php
if(isset($_POST)) {
$parse_uri = explode('wp-content',$_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] .'wp-load.php');
	$orders = $_POST['order'];

	foreach($orders as $order) {
		$_order = new WC_Order($order);
		$customer_email = $_order->get_billing_email();
		$_order->update_status('cancelled');
		$to = $customer_email;
		$subject = esc_html('The order #'.$_order->ID.' cancelled');
		$body = '<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%"><tbody><tr><td align="center" valign="top"><div id="m_-5514218658040503317template_header_image"></div><table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-5514218658040503317template_container" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px"><tbody><tr><td align="center" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-5514218658040503317template_header" style="background-color:#96588a;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0"><tbody><tr>
<td id="m_-5514218658040503317header_wrapper" style="padding:36px 48px;display:block"><h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left">Invoice for order #'.$_order->ID.'</h1></td></tr></tbody></table></td></tr>
<tr><td align="center" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="600" id="m_-5514218658040503317template_body"><tbody><tr><td valign="top" id="m_-5514218658040503317body_content" style="background-color:#ffffff"><table border="0" cellpadding="20" cellspacing="0" width="100%"><tbody><tr>
<td valign="top" style="padding:48px 48px 0"><div id="m_-5514218658040503317body_content_inner" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left"><p style="margin:0 0 16px">Hi '.$customer_email.',</p><p style="margin:0 0 16px">
	Here are the details of your order placed on '.$_order->date_created->date_i18n().': </p><h2 style="color:#96588a;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
	[Order #'.$_order->ID.'] ('.$_order->date_created->date_i18n().')</h2><div style="text-align:center;width:100%;"><h4>Your order: #'.$_order->ID.' has been cancelled</h4></div><p style="margin:0 0 16px">Thanks for reading.</p></div></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td align="center" valign="top"><table border="0" cellpadding="10" cellspacing="0" width="600" id="m_-5514218658040503317template_footer"><tbody><tr><td valign="top" style="padding:0"><table border="0" cellpadding="10" cellspacing="0" width="100%"><tbody><tr>
<td colspan="2" valign="middle" id="m_-5514218658040503317credit" style="padding:0 48px 48px 48px;border:0;color:#c09bb9;font-family:Arial;font-size:12px;line-height:125%;text-align:center"><p>Topcare – Powered by WooCommerce</p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table>';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		 
		wp_mail( $to, $subject, $body, $headers );
	}
}
?>