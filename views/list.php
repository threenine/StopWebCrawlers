<?php
/**
 * WP List Table admin page view
 * Gary Woodfine
 * @license   GPL-2.0+
 */

?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form id="swc-filter" method="get">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />


        <?php $crawler_list_table->search_box('Search Nickname', 'search-table'); ?>

		<?php $crawler_list_table->display() ?>
	</form>
</div>

