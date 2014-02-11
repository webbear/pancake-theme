<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<title><?php echo __($invoice['type'] == 'ESTIMATE' ? 'estimates:estimatenumber' : 'invoices:invoicenumber', array($invoice['invoice_number']));?> | <?php echo Settings::get('site_name'); ?></title>

<!--favicon-->
<link rel="shortcut icon" href="" />

<!--metatags-->
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />

<script>
    estimateStatusUrl = '<?php echo site_url('ajax/set_estimate/' . $invoice['unique_id']); ?>/';
</script>

<!-- Grab Google CDN's jQuery and jQuery UI, with a protocol relative URL; fall back to local if necessary -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo asset::get_src('jquery-1.6.4.min.js', 'js');?>">\x3C/script>')</script>

<!-- CSS -->
<?php echo asset::css('invoice_style.css', array('media' => 'all'), NULL, $pdf_mode); ?>

<link href='//fonts.googleapis.com/css?family=Cabin:400,700&subset=latin&v2' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Copse&subset=latin&v2' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>

<?php if (Settings::get('use_utf8_font')): ?>
    <style>.pdf, .pdf * {font-family: "Open Sans" !important;}</style>
<?php endif;?>    
    
<?php if (Settings::get('frontend_css')): ?>
	<style type="text/css"><?php echo Settings::get('frontend_css'); ?></style>
<?php endif; ?>

</head>

<body class="<?php echo $invoice['type'] == 'ESTIMATE' ? 'estimate' : 'invoice'; ?> <?php echo logged_in() ? 'admin' : 'not-admin';?> <?php if ($pdf_mode): ?>pdf_mode pdf<?php else: ?>not-pdf<?php endif;?>">
<?php if( ! $pdf_mode): ?>
	<div id="buttonBar" data-status="<?php echo $invoice['status'];?>">

		<div id="buttonHolders">
		    
		<?php if (logged_in()): ?>
			<?php echo anchor('admin/invoices/'.(($is_estimate) ? 'estimates' : 'all'), __('global:admin').' &rarr;', 'class="button"'); ?>
		<?php endif; ?>
		    <?php echo anchor(Settings::get('kitchen_route').'/'.$client_unique_id, __('global:client_area').' &rarr;', 'class="button"'); ?>
		
                <?php if ($sendable): ?>
                        <?php echo anchor('admin/'.($invoice['type'] == "ESTIMATE" ? 'estimates' : 'invoices').'/created/'.$invoice['unique_id'], __('global:send_to_client'), 'class="button"'); ?>
                    <?php endif;?>
                    
                    <?php if ($editable): ?>
                            <?php echo anchor('admin/'.($invoice['type'] == "ESTIMATE" ? 'estimates' : 'invoices').'/edit/'.($invoice['unique_id']), __($invoice['type'] == "ESTIMATE" ? 'estimates:edit' : 'invoices:edit'), 'class="button"'); ?>
                    
                    <?php endif;?>
                
                   <div id="pdf">
			<a href="<?php echo site_url('pdf/'.$invoice['unique_id']); ?>" target="_blank" title="<?php echo __('global:downloadpdf'); ?>" id="download_pdf" class="button"><?php echo __('global:downloadpdf'); ?></a>
		</div><!-- /pdf --> 
                    <?php $admin_login = logged_in() ? 'admin' : '#'; ?>
                    <?php if ($is_estimate): ?>
                        <?php if ($editable): ?>
                            <?php echo anchor($admin_login, __('global:mark_as_unanswered'), 'class="unanswer button"'); ?>
                            <?php echo anchor($admin_login, __('global:mark_as_accepted'), 'class="admin accept button"'); ?>
                            <?php echo anchor($admin_login, __('global:mark_as_rejected'), 'class="admin reject button"'); ?>
                        <?php else: ?>
                            <?php echo anchor($admin_login, __('global:reject_estimate'), 'class="client reject button"'); ?>
                            <?php echo anchor($admin_login, __('global:accept_estimate'), 'class="client accept button"'); ?>
                        <?php endif; ?>
                        <?php echo anchor($admin_login, __('global:estimate_rejected'), 'class="rejected button"'); ?>
                        <?php echo anchor($admin_login, __('global:estimate_accepted'), 'class="accepted button"'); ?>
                    <?php endif; ?>
		<?php if( ! $is_paid and $invoice['type'] != 'ESTIMATE' and (count(Gateway::get_frontend_gateways($invoice['real_invoice_id'])) > 0)){ ?>
		<div id="paypal">
        	<a href="<?php echo $invoice['partial_payments'][$invoice['next_part_to_pay']]['payment_url']; ?>" class="button">
                    <?php if (count($invoice['partial_payments']) > 1) : ?>
                        <?php echo __('partial:pay_part_x_now', array($invoice['next_part_to_pay'])); ?>
                    <?php else: ?>
                        <?php echo __('partial:proceedtopayment')?>
                    <?php endif;?>
                </a>
		</div><!-- /paypal -->
		<?php }?>
                <?php if (!$editable): ?>
		<?php if ($is_paid) :?>
		    <span class="paidon"><?php echo __('invoices:thisinvoicewaspaidon', array(format_date($invoice['payment_date'])));?></span>
		<?php elseif (!($is_estimate)) :?>
		    <span class="paidon"><?php echo __('invoices:thisinvoiceisunpaid');?></span>
		<?php endif;?>
                    <?php endif;?>
		</div><!-- /buttonHolders -->

	</div><!-- /buttonBar -->
        <script>
            $('#buttonBar').each(function() {
                var status = $(this).data('status');

                $('.estimate.admin .client.accept, .estimate.admin .client.reject, .estimate.not-admin .admin.accept, .estimate.not-admin .admin.reject, .estimate.not-admin .unanswer.button').hide();

                if (status == 'ACCEPTED') {
                    $('.accept, .reject, .rejected').hide();
                } else {
                    if (status == 'REJECTED') {
                        $('.accept, .reject, .accepted').hide();
                    } else {
                        $('.unanswer.button, .rejected, .accepted').hide();
                    }
                }
            });
        </script>
<?php endif; ?>
	<div id="wrapper">
		<div id="header">
			<div id="envelope" <?php if (!$pdf_mode):?> style="padding:60px 0 0 0" <?php endif; ?>>
				<table cellspacing="0" cellpadding="0" style="padding: 0 0px;">
					<tr>
						<td style="text-align:left;vertical-align:<?php echo (logo(true, false) != '') ?  "top" :  "bottom"; ?>;" id="company-info-holder">
							<?php echo logo(false, false, 2);?>
							<p><?php echo escape(nl2br(Settings::get('mailing_address'))); ?></p>	
              			</td>

 						<td style="text-align:right; vertical-align:top;" class="tight" id="invoice-details-holder">
							<div id="details-wrap">
								<p><?php echo __($invoice['type'] == 'ESTIMATE' ? 'estimates:estimatenumber' : 'invoices:invoicenumber', array($invoice['invoice_number']));?>
								<h2><?php echo $invoice['type'] == 'ESTIMATE' ? __('global:estimate') : Settings::get('default_invoice_title'); ?></h2>
                                                                <p><?php echo __($invoice['type'] == 'ESTIMATE' ? 'estimates:estimatedate' : 'invoices:invoicedate'); ?>: <?php echo $invoice['date_entered'] ? format_date($invoice['date_entered']) : '<em>n/a</em>';?></p>
								<p><?php echo __('invoices:due'); ?>: <?php echo $invoice['due_date'] ? format_date($invoice['due_date']) : '<em>n/a</em>';?></p>
			                    <?php if($invoice['is_paid'] == 1): ?>
			                    <span class="paidon"><?php echo __('invoices:paidon', array(format_date($invoice['payment_date'])));?></span>
			                      <?php endif; ?>
				                  </p>
							</div><!-- /details-wrap -->	
						</td>
					</tr>
				</table>
			</div><!-- /envelope -->


			<div id="clientInfo">
            <div id="billing-info">
              <table cellspacing="0" cellpadding="0" id="billing-table">
                <tr>
                  <td width="240px" style="vertical-align:top;">
					<h2><?php echo $invoice['company'];?></h2>
                    <p><?php echo escape($invoice['first_name'].' '.$invoice['last_name']);?><br />
                  <?php echo escape(nl2br($invoice['address']));?></p></td>
                  <td width="<?php echo (!$pdf_mode)? "560px" : "300px" ?>"  style="vertical-align:top;">
						<?php if ( ! empty($invoice['description'])): ?>
						<h3><?php echo __('global:description');?>:</h3>
						<?php echo escape(auto_typography($invoice['description']));?>
						<?php endif; ?>
					</td>
                </tr>
              </table>
              <br /> <br />
            </div>
		  </div><!-- /clientInfo -->



		</div><!-- /header -->
<?php echo $template['body']; ?>
		<div id="footer">

		</div><!-- /footer -->
</div><!-- /wrapper -->
<?php if ($invoice['type'] != 'ESTIMATE'): ?>

<?php

// ====================
// = Remittence slips =
// ====================

/*
	If you wish to remove this option delete everyting between
	
	=== PAYMENT SLIP ====
	
	=== END PAYMENT SLIP ===
	
*/


?>



<?php // 	=== PAYMENT SLIP ====	 ?>

<?php if($pdf_mode and Settings::get('include_remittance_slip')): ?>
<div style="page-break-before: always;"></div>
<div id="wrapper">
 <div id="header">
  <div id="envelope" class="remittance_slip">
   <table border="0" cellspacing="5" cellpadding="5">
    <tr>
     <td width="400px">
      <h2>How to Pay</h2>
      <p>View invoice online at <?php echo anchor($invoice['unique_id']); ?></p>
      <p>You may pay in person, online, or by mail using this payment voucher. Please provide your payment information below.<br/>
<br/><br/>
Enclosed Amount: __________________________________
      </p>
     </td>
     <td width="200px" style="text-align:right">
      <p>
      <strong>Invoice #:</strong> <?php echo $invoice['invoice_number'];?><br/>
      <strong>Total:</strong> <?php echo Currency::format($invoice['total'], $invoice['currency_code']); ?><br/>
      <strong>Due:</strong> <?php echo $invoice['due_date'] ? format_date($invoice['due_date']) : '<em>n/a</em>';?>
      </p>
      
      <h3>Mail To:</h3>      
      <p><span class='site_name'><?php echo Settings::get('site_name'); ?><br /></span><span class="mailing-address"><?php echo nl2br(Settings::get('mailing_address')); ?></span></p>
     </td>
     
    </tr>
   </table>
  </div>
 </div>
</div>
<?php endif; ?>
<?php // === END PAYMENT SLIP === ?>
<?php endif; ?>

<script src="<?php echo asset::get_src('invoices.js', 'js');?>"></script>
</body>
</html>