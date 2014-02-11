<div id="content">
    
        <div class="content-header">
            <?php if ($latest): ?>
            <a href="<?php echo site_url($latest->unique_id);?>" id="latest_invoice">
                <h4><?php echo __('kitchen:latest_invoice')?></h4>
                <h3><?php echo __('invoices:invoicenumber', array($latest->invoice_number)); ?></h3>
                <p>
                    <span class="paidon"><?php echo __('projects:due_date')?>: <?php echo $latest->due_date ? format_date($latest->due_date) : '<em>'.__('global:na').'</em>'; ?></span><br />
                    <span class="paidon"><?php echo __('invoices:amount')?>: <?php echo Currency::format($latest->billable_amount, $latest->currency_code); ?></span><br />
                    <?php if ($latest->is_paid) : ?>
                        <span class="paidon"><?php echo __('invoices:thisinvoicewaspaidon', array(format_date($latest->payment_date))); ?></span>
                    <?php else: ?>
                        <span class="paidon"><?php echo __('invoices:thisinvoiceisunpaid'); ?></span>
                    <?php endif; ?>
                </p>
            </a>
            <?php endif;?>

            <div class="side_border">
                <h1>
                    <?php echo $client->first_name; ?> <?php echo $client->last_name; ?><?php echo empty($client->company) ? '' : ' - ' . $client->company; ?>
                </h1>
                <h4 class="account_totals">
                    <?php echo __('kitchen:unpaid_balance', array(Currency::format($client->unpaid_total))) ?><br />
                    <?php echo __('kitchen:total_paid_to_date', array(Currency::format($client->paid_total)))?>
                </h4>
            </div>
            <div class="clear">&nbsp;</div>
        </div>
        

	<?php if (count($invoices)): ?>
	
		
	<h2 class="invoices_heading"><?php echo __('global:invoices')?></h2>
	
	<table id="kitchen-invoices"  class="kitchen-table" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class="column_1"><?php echo lang('invoices:number') ?></th>
			<th class="column_2"><?php echo lang('invoices:due') ?></th>
			<th class="column_3"><?php echo lang('invoices:amount') ?></th>
			<th class="column_4"><?php echo lang('invoices:is_paid') ?></th>
			<th class="column_4"><?php echo lang('invoices:view') ?></th>
			<th class="column_5"><?php echo lang('global:notes') ?></th>
		</tr>
		</thead>
		<?php foreach ($invoices as $invoice): ?>
			<tr class="border-top">
				<td colspan="6">
					<div id="border-holder-top">
					</div><!-- /border-holder-top -->
				</td>
			</tr><!-- /top-border -->
			<tr class="items-desc-row <?php echo ($invoice->paid ? 'paid' : 'unpaid'); ?>-invoice">
			<td><?php echo $invoice->invoice_number; ?></td>
			<td><?php echo $invoice->due_date ? format_date($invoice->due_date) : '<em>'.__('global:na').'</em>';?></td>
			<td><?php echo Currency::format($invoice->billable_amount, $invoice->currency_code); ?></td>
			<td class="status_<?php echo ($invoice->paid ? 'paid' : 'unpaid'); ?>"><?php echo __($invoice->paid ? 'global:paid' : 'global:unpaid'); ?></td>
			<td>
				<?php echo anchor($invoice->unique_id, lang('invoices:view')); ?>
			</td>
			<td>
				<?php echo anchor(Settings::get('kitchen_route').'/'.$client->unique_id.'/comments/invoice/'.$invoice->id, __('kitchen:comments_x', array($invoice->total_comments))); ?>
			</td>
		</tr>
		<tr class="border-bottom">
			<td colspan="6">
				<div id="border-holder-bottom">
				</div><!-- /border-holder-bottom -->
			</td>
		</tr>
		<?php endforeach ?>
	</table>

	
	<?php endif; //END INVOICE COUNT ?>


	<?php if (count($estimates)): ?>
	<h2><?php echo __('global:estimates'); ?></h2>

	<table id="kitchen-estimates"  class="kitchen-table" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class="column_1"><?php echo lang('invoices:amount') ?></th>
			<th class="column_2"><?php echo lang('estimates:view') ?></th>
			<th class="column_5"><?php echo lang('global:notes') ?></th>
		</tr>
		</thead>
		<?php foreach ($estimates as $estimate): ?>
			<tr class="border-top">
				<td colspan="3">
					<div id="border-holder-top">
					</div><!-- /border-holder-top -->
				</td>
			</tr><!-- /top-border -->
			<tr class="items-desc-row">
			<td><?php echo Currency::format($estimate->amount, $estimate->currency_code); ?></td>
			<td>
				<?php echo anchor($estimate->unique_id, lang('estimates:view')); ?>
			</td>
			<td>
				<?php echo anchor(Settings::get('kitchen_route').'/'.$client->unique_id.'/comments/invoice/'.$estimate->id, __('kitchen:comments_x', array($estimate->total_comments))); ?>
			</td>
		</tr>
		<tr class="border-bottom">
			<td colspan="3">
				<div id="border-holder-bottom">
				</div><!-- /border-holder-bottom -->
			</td>
		</tr>
		<?php endforeach ?>
	</table>
	<?php endif //END ESTIMATE COUNT ?>

	<?php if ($projects): ?>
	<h2><?php echo __('global:projects'); ?></h2>

	<?php $prev_milestone = null; ?>
	<?php foreach ($projects as $project): ?>
		
		<div id="project-<?php echo $project->id; ?>-holder">

		
		<h4><?php echo $project->name; ?></h4>
		<p style="font-size:12px; color:#ccc"><?php echo lang('projects:due_date') ?>: <?php echo format_date($project->due_date); ?><br>
		<?php echo lang('projects:is_completed') ?>: <?php echo ($project->completed ? __('global:yes') : __('global:no')); ?><br>
		<?php echo anchor(Settings::get('kitchen_route').'/'.$client->unique_id.'/comments/project/'.$project->id, __('kitchen:comments_x', array($project->total_comments))); ?></p>

		<div id="project-details-holder">

		
		<table id="kitchen-projects" class="kitchen-table" cellpadding="0" cellspacing="0">
			<?php foreach ($project->tasks as $task): ?>
				

				
				<?php if ($task['milestone_name'] !== $prev_milestone): ?>
					
					
					<tr class="milestone-title">
						<th>
							
							<?php if (!empty($task['milestone_name'])): ?>
								<div class="milestone-icon" style="background-color: <?php echo $task['milestone_color'] ?>"></div>
							<?php endif; ?>
							
							<?php echo (!empty($task['milestone_name']) ?  $task['milestone_name'] :  lang('tasks:no_milestones')); ?> </th>
						<th><?php echo lang('tasks:hours') ?></th>
						<th><?php echo lang('tasks:due_date') ?></th>
						<th><?php  echo __('global:status') ?></th>
						<th><?php  echo __('global:notes') ?></th>
					</tr>

					<?php $prev_milestone = $task['milestone_name']; ?>
				<?php endif ?>
				
				<tr class="border-top">
					<td colspan="5">
						<div id="border-holder-top">
						</div><!-- /border-holder-top -->
					</td>
				</tr><!-- /top-border -->
				<tr class="items-desc-row">
					<td>
						
						
						
						<?php if ($task['completed'] == '1'): ?>
						<img src="<?php echo asset::get_src('bg-invoice-arrow.gif', 'img'); ?>" />  <strike><?php echo $task['name']; ?></strike>
						<?php else: ?>
					
						
						<img src="<?php echo asset::get_src('bg-invoice-arrow.gif', 'img'); ?>" />  <?php echo $task['name']; ?>
						<?php endif ?>
						
						</td>
					<td><?php echo format_hours($task['tracked_hours']); ?></td>
					<td><?php echo $task['due_date'] ? format_date($task['due_date']) : __('global:na'); ?></td>
                                        <td>
                                            <?php if ($task['status_title']): ?>
                                                <span class="tag status-<?php echo $task['status_id'] ?>" style="color: <?php echo $task['font_color'] ?>; background: <?php echo $task['background_color'] ?>; text-shadow: 1px 1px <?php echo $task['text_shadow'] ?>; -webkit-box-shadow:0px 1px 1px 0px <?php echo $task['box_shadow'] ?>; -moz-box-shadow:0px 1px 1px 0px <?php echo $task['box_shadow'] ?>; box-shadow: 0px 1px 1px 0px <?php echo $task['box_shadow'] ?>" ><?php echo $task['status_title'] ?></span>
                                            <?php else: ?>
                                                <span><?php echo ($task['completed']) ? __('gateways:completed') : __('global:na'); ?></span>
                                            <?php endif ?>
                                        </td>
					<td>
						<?php echo anchor(Settings::get('kitchen_route').'/'.$client->unique_id.'/comments/task/'.$task['id'], __('kitchen:comments_x', array($task['total_comments']))); ?>
					</td>
				</tr>
				<tr class="border-bottom">
					<td colspan="5">
						<div id="border-holder-bottom">
						</div><!-- /border-holder-bottom -->
					</td>
				</tr>
				<tr class="item-notes">
				<td colspan="5"><?php echo auto_typography($task['notes']);?></td>
				</tr>
				
					
					
			<?php endforeach ?>
		</table><!-- /kitchen-table-->
		</div><!-- /project-details-holder -->
		
		</div><!-- /project-<?php echo $project->id; ?>-holder -->
	<?php endforeach ?>

	<?php endif ?>



	<?php if (count($proposals)): ?>
	<h2><?php echo __('proposals:proposal') ?></h2>

	<table id="kitchen-proposals" class="kitchen-table" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th><?php echo __('proposals:number') ?></th>
			<th><?php echo __('proposals:proposal_title') ?></th>
			<th><?php echo __('proposals:estimate') ?></th>
			<th><?php echo __('proposals:status') ?></th>
			<th><?php  echo __('global:notes') ?></th>
		</tr>
		</thead>
		<?php foreach ($proposals as $proposal): ?>
			<tr class="border-top">
				<td colspan="5">
					<div id="border-holder-top">
					</div><!-- /border-holder-top -->
				</td>
			</tr><!-- /top-border -->
			<tr class="items-desc-row">
			<td><?php echo $proposal->proposal_number; ?></td>
			<td><?php echo $proposal->title; ?></td>
			<td><?php echo ($proposal->amount > 0 ? Currency::format($proposal->amount) : __('global:na')); ?></td>
			<td><?php echo __('proposals:' . (!empty($proposal->status) ? strtolower($proposal->status) : 'noanswer'), array(format_date($proposal->last_status_change))); ?></td>
			<td>
				<?php echo anchor('proposal/'.$proposal->unique_id, lang('proposals:view')); ?>
				<?php echo anchor(Settings::get('kitchen_route').'/'.$client->unique_id.'/comments/proposal/'.$proposal->id, __('kitchen:comments_x', array($proposal->total_comments))); ?>
			</td>
		</tr>
		<tr class="border-bottom">
			<td colspan="5">
				<div id="border-holder-bottom">
				</div><!-- /border-holder-bottom -->
			</td>
		</tr>
		<?php endforeach ?>
	</table>

	<?php endif ?>


</div><!-- /projects -->