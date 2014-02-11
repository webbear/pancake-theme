<?php if (isset($invoice['id'])): ?>
<div id="content">
	<table style="width: 100%;" id="<?php echo ($is_estimate) ? 'estimate_table' : ''; ?>" cellspacing="0" cellpadding="0">
		<thead>
		<tr>
			<th class="column_1" style="text-align:left"><?php echo __('global:description'); ?> </th>
			<th class="column_2"><?php echo __('invoices:timequantity');?></th>
			<th class="column_3"><?php echo __('invoices:ratewithcurrency', array($invoice['currency_code'] ? $invoice['currency_code'] : Currency::code()));?></th>
			<th class="column_4"><?php echo __('invoices:taxable');?></th>
			<th class="column_5"><?php echo __('items:type');?></th>
			<th class="column_6"><?php echo __('invoices:total');?></th>
		</tr>
		</thead>

		<tbody>
			<?php
            if ( ! empty($invoice['items'])):
			$class = '';
			foreach( $invoice['items'] as $item ):
			?>
				<tr class="border-top">
					<td colspan="6">
						<div id="border-holder-top">
						</div><!-- /border-holder-top -->
					</td>
				</tr><!-- /top-border -->
				<tr class="<?php echo $class; ?> invoice-desc-row">
					<td class="column_1"><img src="<?php echo asset::get_src('bg-invoice-arrow.gif', 'img'); ?>" />  <?php echo escape($item['name']); ?></td>
					<td class="column_2"><?php echo $item['qty']; ?></td>
					<td class="column_3"><?php echo Currency::format($item['rate'], $invoice['currency_code']); ?></td>
					<td class="column_4"><?php echo $item['tax_id'] ? __('global:Y') : __('global:N'); ?></td>
                                        <td class="column_5"><?php echo invoice_item_type($item); ?></td>
					<td class="column_6 total-values"><?php echo Currency::format($item['total'], $invoice['currency_code']); ?></td>
				</tr>
				<tr class="border-bottom">
					<td colspan="6">
						<div id="border-holder-bottom">
						</div><!-- /border-holder-bottom -->
					</td>
				</tr>
				<?php if ($item['description']): ?>
				<tr class="invoice-item-notes">	
					<td colspan="5"><?php echo nl2br(escape($item['description'])); ?></td>
				</tr>
				<?php endif; ?>
			<?php
			$class = ($class == '' ? 'alt' : '');
			endforeach;
                        endif;
			?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="3" rowspan="8" class="invoice-notes" style=" vertical-align:top;">
                                    
					<?php if ( ! empty($invoice['notes'])): ?>
                                            <h3><?php echo __('global:notes');?>:</h3>
                                            <?php echo auto_typography(escape($invoice['notes']));?>
					<?php endif; ?>



	<?php // Taxes Section  ?>
				<?php if ($invoice['has_tax_reg']): ?>
				<h3><?php echo __('settings:taxes') ?></h3>
				<ul id="taxes">
					<?php foreach ($invoice['taxes'] as $id => $total ):
						$tax = Settings::tax($id);
						if (empty($tax['reg'])) continue;
					?>
						<li class="<?php echo underscore($tax['name']) ?>">
							<span class="name"><?php echo $tax['name'] ?>:</span>
							<span class="reg"><?php echo $tax['reg'] ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
	<?php // END Taxes section ?>
                                
                                <div class='invoice-payments'>
                                    <?php if (!$is_estimate) : ?>
						<?php $has_gateway = (count(Gateway::get_frontend_gateways($invoice['real_invoice_id'])) > 0); ?>
				        <?php if (count($invoice['partial_payments']) > 1) : ?>
				        <h2 class="main-header-style orange" id="payment-plan-header"><?php echo __('partial:partialpayments');?></h2>
				        <div class="payment-plan">
				                    <ol>
				                        <?php foreach ($invoice['partial_payments'] as $part) : ?>
				                            <li>
				                                    <span class="amount"><?php echo Currency::format($part['billableAmount'], $invoice['currency_code']); ?></span> <?php if ($part['due_date'] != 0) : ?><?php echo __('partial:dueondate', array('<span class="dueon">'.format_date($part['due_date']).'</span>'));?><?php endif; ?> <?php echo (empty($part['notes'])) ? '' : '- '.$part['notes']; ?>
				                                    <?php if (!$part['is_paid']) : ?>
				                                    <?php if ($pdf_mode) : ?>
                                                                        <?php if ($has_gateway): ?>
                                                                            <?php echo " &raquo; ".__('partial:topaynowgoto', array('<a href="'.$part['payment_url'].'">'.$part['payment_url'].'</a>'));?>
                                                                        <?php endif ?>
				                                    <?php else: ?>
				                                    	<?php if ($has_gateway): ?>
				                                        <?php echo " &raquo; ".anchor($part['payment_url'], __('partial:proceedtopayment'), 'class="simple-button"'); ?>
				                                    	<?php endif ?>
				                                    <?php endif; ?>
				                                    <?php else: ?>
				                                        <?php echo " &raquo; ".__('partial:partpaidthanks');?>
				                                    <?php endif; ?>
				                            </li>
				                        <?php endforeach; ?>
				                    </ol>
				            </div>
				        <?php endif;?>
					<?php endif ; ?>
                                </div>

	<?php //Files for download section ?>

	<?php if (!($is_estimate)) : ?>
		<?php if ($files): ?>
		<div id="files" class="main-body-style">
			<h2 class="main-header-style"><?php echo __('invoices:filestodownload'); ?></h2>

				<div class="files-holder">
					<?php if ( ! $is_paid): ?>
						<p><?php echo __('invoices:fileswillbeavailableafterpay');?></p>
					<?php endif; ?>

					<ul id="list-of-files">
					<?php foreach ($files as $file): ?>
				            <?php $ext = explode('.', $file['orig_filename']); end($ext); $ext = current($ext); ?>
				            <?php $bg = $pdf_mode ? '' : asset::get_src($ext.'.png', 'img'); ?>
				            <?php $style = empty($bg) ? '' : 'style="background: url('.$bg.') 1px 0px no-repeat;"'; ?>
					<?php if ($is_paid): ?>
						<li><a class="file-to-download" <?php echo $style;?> href="<?php echo site_url('files/download/'.$invoice['unique_id'].'/'.$file['id']);?>"><?php echo $file['orig_filename'];?></a></li>
					<?php else: ?>
				         <li class="file-to-download" <?php echo $style;?> ><?php echo $file['orig_filename']; ?></li>
					<?php endif; ?>
					<?php endforeach; ?>
					</ul><!-- /list-of-files -->

				</div><!-- /files-holder -->
		    </div><!-- /files -->
		<?php endif; ?>
	<?php endif; ?>

	<?php // End Files section ?>

				</td>
				<td class="total-heading"><?php echo __('invoices:subtotal');?>:</td>
				<td colspan="2" class="total-values"><?php echo Currency::format($invoice['sub_total'], $invoice['currency_code']); ?></td>
			</tr>
		<?php foreach( $invoice['taxes'] as $id => $total ):
			$tax = Settings::tax($id);
		?>
			<tr dontbreak="true">
				<td class="total-heading"><?php echo $tax['name'].' ('.$tax['value'].'%):'; ?></td>
				<td colspan="2" class="total-values"><?php echo Currency::format($total, $invoice['currency_code']); ?></td>
			</tr>
		<?php endforeach; ?>
			<tr dontbreak="true">
				<td class="total-heading"><?php echo __('invoices:totaltax');?>:</td>
				<td colspan="2" class="total-values"><?php echo Currency::format($invoice['tax_total'], $invoice['currency_code']); ?></td>
			</tr>

			<tr dontbreak="true" <?php echo ($invoice['paid_amount'])? '' : 'class="invoice-total"' ?> >
				<td class="total-heading" style=" vertical-align:top;"><?php echo __('invoices:total');?>:</td>
				<td colspan="2" class="total-values" style=" vertical-align:top;"><?php echo Currency::format($invoice['total'], $invoice['currency_code']); ?></td>
			</tr>


			<?php if($invoice['paid_amount']): ?>
				
			<tr dontbreak="true">
				<td class="total-heading"><?php echo __('invoice:paid_amount');?>:</td>
				<td colspan="2" class="total-values"><?php echo Currency::format($invoice['paid_amount'], $invoice['currency_code']); ?></td>
			</tr>
				
			<tr dontbreak="true" class="invoice-total">
				<td class="total-heading" style=" vertical-align:top;"><?php echo __('Due');?>:</td>
				<td colspan="2" class="total-values" style=" vertical-align:top;"><?php echo Currency::format($invoice['unpaid_amount'], $invoice['currency_code']); ?></td>
			</tr>
			
			<?php endif ?>
			<tr dontbreak="true">
				<td colspan="3"  class="invoice-due-on"><?php echo __('invoices:due'); ?>: <?php echo $invoice['due_date'] ? format_date($invoice['due_date']) : '<em>n/a</em>';?></td>
			</tr>

		</tfoot>
	</table>





</div><!-- /content -->
<?php endif;?>