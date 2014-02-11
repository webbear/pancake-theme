<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>

<title><?php echo __('kitchen:kitchen_name') ?> | <?php echo Settings::get('site_name'); ?></title>

<!--favicon-->
<link rel="shortcut icon" href="" />

<!--metatags-->
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />

<!-- CSS -->
<?php echo asset::css('kitchen_style.css', array('media' => 'all')); ?>
<?php echo asset::css('redactor.css', array('media' => 'all')); ?>

<link href='//fonts.googleapis.com/css?family=Cabin:400,700&subset=latin&v2' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Copse&subset=latin&v2' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo asset::get_src('jquery-1.6.4.min.js', 'js');?>">\x3C/script>')</script>
<script>document.write('<script src="<?php echo asset::get_src('redactor/redactor.min.js', 'js');?>">\x3C/script>')</script>
<script>document.write('<script src="<?php echo asset::get_src('tickets/create.js', 'js');?>">\x3C/script>')</script>
<?php if (Settings::get('frontend_css')): ?>
	<style type="text/css"><?php echo Settings::get('frontend_css'); ?></style>
<?php endif; ?>

</head>


<body class="kitchen <?php echo is_admin() ? 'admin' : 'not-admin';?>">
	
	

	<div id="buttonBar">

		<div id="buttonHolders">
			

				
		<?php if (logged_in()): ?>
			<?php echo anchor('admin/', __('global:backtoadmin'), 'class="button"'); ?>
		<?php endif; ?>
                    <?php if ($this->session->userdata('client_passphrase') != '' or logged_in()): ?>
                        <?php echo !logged_in() ? anchor(Settings::get('kitchen_route').'/logout/'.$this->uri->segment(2), __('global:logout'), 'class="button"') : ''; ?>
                        <?php echo anchor(Settings::get('kitchen_route').'/'.$this->uri->segment(2).'/tickets', __('tickets:support_tickets'), 'class="button '.($this->uri->segment(3) == 'tickets' ? 'active' : '').'"'); ?>
			<?php echo anchor(Settings::get('kitchen_route').'/'.$this->uri->segment(2), 'Dashboard', 'class="button '.($this->uri->segment(3) == '' ? 'active' : '').'"'); ?>
		<?php endif; ?>
		
		<span class="button-bar-text "><?php echo Settings::get('site_name'); ?> - <?php echo __('kitchen:kitchen_name') ?></span>
		</div><!-- /buttonHolders -->
		
		

	</div><!-- /buttonBar -->
	
	<div id="wrapper">
		<?php if (!isset($hide_header)) :?>
		<div class="header-area">
			
		</div><!-- /header-area end -->
		<?php endif;?>


<?php echo $template['body']; ?>



		
        <div id="footer">

		</div><!-- /footer -->
	</div><!-- /wrapper -->
</body>
</html>