<div class="wrap">
<h2>Add Web Crawler</h2>
<form method="POST" name="swc_add_new_crawler" >
  <?php wp_nonce_field('add', 'nonce');?>


			
				
				<input type="text" maxlength="30" name="swc_name"
					value="" placeholder="Name"/>
					<p><label class="swc-info-label">Enter a name that you would like to use to identify this bot</label></p>
					<br/>
	
				
				<input type="text" maxlength="255" name="swc_url"
					value="" placeholder="Url" />
					<p><label class="swc-info-label">Enter the domain name or IP address to block .i.e. semalt.com</label></p>
					<br/>
			
	<?php submit_button(); ?>
	</form>
	
	  <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>
</div>

