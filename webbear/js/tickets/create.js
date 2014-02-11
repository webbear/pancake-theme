/**
 * Pancake
 *
 * A simple, fast, self-hosted invoicing application
 *
 * @package		Pancake
 * @author		Pancake Dev Team
 * @copyright	Copyright (c) 2010, Pancake Payments
 * @license		http://pancakeapp.com/license
 * @link		http://pancakeapp.com
 * @since		Version 4.25
 */
(function($){
	var fn = {element:false,options:false};
	
	fn.init=function(){
		$('textarea').redactor();
		this.get_client_rates(this.options.client_id);
		this.init_events();
	}
	
	fn.init_events=function(){
		this.init_button_events();
		this.init_ticket_priority_event();
	}

	fn.init_button_events=function(){

		$('.btn-new-ticket,.cancel').click(function(){
			$('select').val('0');
			$('input[type="text"],textarea').val('');
			$('.new-ticket-from').slideToggle();
		})
	}
	fn.init_ticket_priority_event=function(){
		var self = this;
		this.element.find('select.sel_priority').bind('change',function(){
			var el = $(this);

			var amt = el.find('option:selected').attr('rate');
			if(amt){
				if(parseFloat(amt)>0){
					self.element.find('input.ticket_amt').val(amt);
					self.element.find('input.ticket_is_billable').val('1');
				}else{
					self.element.find('input.ticket_amt').val('0');
					self.element.find('input.ticket_is_billable').val('0');
				}
			}else{
				self.element.find('input.ticket_amt').val('0');
				self.element.find('input.ticket_is_billable').val('0');
			}
		});
	}

	fn._get_data=function(uri,data,success,error){
		var url = this.options.base_url+'clients/'+uri;

		$.ajax({
			url: url,
			type:'POST',
			dataType:'json',
			data: data || {},
			success:success,
			error:error
		});
	}

	fn.get_client_rates=function(id){
		var self = this;
		this._get_data('get_client_support_matrix_json',{client_id:id},
		function(data){
			
			self.rebuild_priority_select_ui(data);
			
		},function(a,b,c){

		});
	}

	fn.rebuild_priority_select_ui=function(data){
		var sel = this.element.find('select.sel_priority');
		sel.find('option:not([value=0])').remove();
		
		$.each(data.ticket_priorities,function(i,priority){
			var o = $('<option/>');
			o.val(priority.id);
			var title = priority.title;

			if(parseFloat(priority.default_rate)>0){
				title += ' - [ ' + priority.default_rate + ' ]';
				o.attr('rate',priority.default_rate);
			}
			
			o.html(title);

			sel.append(o);
		});

	}
	

	fn.destroy=function(){

	}

	$.fn.ticket_create=function(data){
		
		fn.element = $(this);
		fn.options = data;
		fn.init();
	}

})(jQuery,document,window);