<div id="content">
	<h1><?php echo $client->first_name;?> <?php echo $client->last_name;?><br>Support Tickets</h1>
	<?php if(!empty($client->support_user_id)): ?>
		<button class="btn-new-ticket" style="margin-bottom:5px;">Submit New Ticket</button>
	<?php endif; ?>

	<noscript>
		<style type="text/css">
			.btn-new-ticket{display:none;}
		</style>
	</noscript>
	<div id="ticket-body" class="eight columns new-ticket-from" style="padding:5px;background-color:#eee;border-radius:5px;display:none;"> 
		<?php /* CREATE NEW TICKET */ ?>
		<div id="ticket-create" >

			<div class="help">
				<h3>Submit a Ticket</h3>
			</div> <br />

			<?php echo form_open(Settings::get('kitchen_route').'/'.$client->unique_id.'/new_ticket/', array('class' => 'form-holder row')) ?>
				<div class="twelve columns">
					<label for="subject" style="width:20%;"><?php echo __('tickets:ticket_subject'); ?></label>
					<input type="text" id="subject" name="subject" style="padding:6px;border-radius:5px;border:1px solid #ccc;width:53%;margin-left:30px;margin-bottom:10px;">
				</div>

				<div class="twelve columns" style="margin-bottom:10px;">
					<label for="message" style="display:inline-block;vertical-align:top;"><?php echo __('tickets:ticket_message'); ?></label>
					<div class="textarea ticket" style="display:inline-block;width:54%;height:100px;margin-left:23px;">
						<?php
							echo form_textarea(array(
								'name' => 'message',
								'id' => 'message',
								'style' => 'display:inline-block;width:100%;',
								'value' => '',
								'rows' => 10,
								'cols' => 55
							));
						?>
					</div>
				</div>

				<div class="six columns" style="margin-bottom:10px;">
				    <label for="priority" style="width:20%;"><?php echo __('tickets:ticket_priority'); ?></label>
				    <span class="sel-item">
						<?php echo form_dropdown('priority_id', $priorities, 0,'style="width:54%;margin-left:33px;" class="sel_priority"'); ?>
				    </span>
				</div>

				<div class="six columns end" style="margin-bottom:10px;">
				    <label for="status" style="width:20%;"><?php echo __('tickets:ticket_status'); ?></label>
				    <span class="sel-item">
						<?php echo form_dropdown('status_id', $statuses, 0,'style="width:54%;margin-left:38px;"'); ?>
				    </span>
				</div>
				<input type="hidden" name="is_billable" class="ticket_is_billable" value="0" />
				<input type="hidden" name="ticket_amount" class="ticket_amt" value="0" />
				<div class="twelve columns">
					<input type="submit" id="submit" class="blue-btn" value="Add Ticket" style="width:15%;">
					<input type="button" class="cancel" value="Cancel" style="width:15%;">
				</div>
			</form>
		</div>
	</div> 
	<h2>Active Tickets</h2>
	<table id="kitchen-invoices"  class="kitchen-table" cellpadding="0" cellspacing="0">
		<thead>
			<?php if(count($tickets) > 0) : ?>
		    	<tr>
			    	<th style="width:8%"><?php echo __('Status') ?></th>
		            <th style="width:8%"><?php echo __('Priority') ?></th>	
		            <th style="width:36%"><?php echo __('Subject') ?></th>	
		 			<th style="width:12%"><?php echo __('Created') ?></th>
		 			<th style="width:12%"><?php echo __('Updated') ?></th>
		            <th style="width:11%"><?php echo __('Responses') ?></th>
		            <th style="width:11%"></th>
	        	</tr>
        	<?php endif; ?>
        </thead>
 
        <tbody>
        	<?php if(count($tickets) > 0) : ?>
	        	<?php foreach ($tickets as $ticket): ?>
	        	<tr class="ticket medium">
	          		<td style="width:6%"><span class="open" style="background-color: <?php echo $ticket->status_background_color; ?>"></span><?php echo $ticket->status_title; ?></td>
	          		<td style="width:6%"><?php echo $ticket->priority_title; ?></td>
	           		<td style="width:36%"><?php echo $ticket->subject; ?></td>
	        		<td style="width:12%"><?php echo days_ago($ticket->created).' ago'; ?></td>
	        		<td style="width:12%"><?php echo days_ago(empty($ticket->latest_history) ? $ticket->created : $ticket->latest_history->created).' ago'; ?></td>
	        		<td style="width:11%"><?php echo $ticket->response_count ?> Responses</td>
	        		<td style="width:11%"><?php echo anchor(Settings::get('kitchen_route').'/'.$client->unique_id.'/tickets/'.$ticket->id, 'View Conversation'); ?></td>
	      		</tr>
	        	<?php endforeach ?>
	        <?php else : ?>
	        	<tr class="ticket medium">
	        		<td colspan="7">
	        			No Tickets Created.
	        		</td>
	        	</tr>
	    	<?php endif; ?>
        </tbody>
	</table>
	<?php if ($current_ticket): ?>
		<h2><?php echo $current_ticket->subject ?></h2>
	<?php 
	$prev_date = '';
	foreach ($current_ticket->activity as $ts => $activity): ?>

	<?php 
	$date = date('jS M Y', $ts);
	if ($prev_date != $date): 
		$prev_date = $date;
	?>
	<?php endif ?>

	<?php if (isset($activity['post']) && $activity['post']): 
		$is_staff = $activity['post']->user_id != null;
	?>
	<div class="ticketconvo <?php echo $is_staff ? 'left' : 'right' ?>">
		<div class="image">
			<img src="<?php echo get_gravatar($is_staff ? $activity['post']->user->email : $current_ticket->client_email, 60); ?>" />
		</div>
		<div class="text">
			<h4><?php echo $activity['post']->user_name ?></h4>
			<p><?php echo nl2br($activity['post']->message) ?></p>
		</div>
	</div>
	<br class="clear" />
	<?php if(!empty($activity['post']->orig_filename)): ?>
		<div class="files">
			<p><?php echo __('tickets:attachment') ?>:</p>
			<?php $ext = explode('.', $activity['post']->orig_filename); end($ext); $ext = current($ext); ?>	
			<?php if($ext == 'png' OR $ext == 'jpg' OR $ext == 'gif'): ?>
				<div class="image-preview">
					<p><img src="<?php echo site_url('admin/tickets/file/'.$activity['post']->real_filename); ?>" style="max-width:50%" /></p>
				</div>						
			<?php endif; ?>
			<?php $bg = asset::get_src($ext.'.png', 'img'); ?>
            <?php $style = empty($bg) ? '' : 'style="background: url('.$bg.') 1px 0px no-repeat;"'; ?>
			<a class="file-to-download" <?php echo $style;?> href="<?php echo site_url('admin/tickets/file/'.$activity['post']->real_filename); ?>"><?php echo $activity['post']->orig_filename;?></a>
		</div>
	<?php endif; ?>
	<br />
	<?php endif ?>

	<?php if (isset($activity['history']) && $activity['history']): ?>
	<div class="notice" style="border-bottom: 1px solid <?php echo $activity['history']->status->background_color ?>;">
		<div><span style="background: <?php echo $activity['history']->status->background_color ?>; color: <?php echo $activity['history']->status->font_color ?>;"><?php echo $activity['history']->user_name ?> updated the ticket status to <strong><?php echo $activity['history']->status->title ?></strong> on <?php echo date('m/d/Y \a\t g:ia', $ts) ?></span></div>
	</div>
	<?php endif ?>
	<?php endforeach ?>

	<h3>Leave a Response</h3><br/>
	<div class="ticketconvo">
		<?php echo form_open(Settings::get('kitchen_route').'/'.$client->unique_id.'/tickets/'.$current_ticket->id); ?>
		<textarea name="message" style="width: 100%; height: 130px; margin-bottom: 10px;"></textarea> <br />
		<div class="six columns end" style="margin-bottom:10px;">
		    <label for="status" style="width:20%;">Ticket Status</label>
		    <span class="sel-item">
				<?php echo form_dropdown('status_id', $statuses, $current_ticket->status_id,'style="width:54%;margin-left:38px;"'); ?>
		    </span>
		</div>

		<input type="submit" class="submit" value="Update Ticket">
		<?php echo form_close(); ?>
	</div>
	<?php endif ?>
</div>
<script type="text/javascript">
	
		$('.new-ticket-from').ticket_create({
			base_url: '<?php echo site_url() ?>',
			client_id: '<?php echo $client->id ?>'
		});
	
</script>