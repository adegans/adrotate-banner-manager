<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2025 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

$banner = $wpdb->get_row("SELECT `title`, `tracker`, `type` FROM `{$wpdb->prefix}adrotate` WHERE `id` = '{$id}';");

$stats = adrotate_stats($id);
$stats_today = adrotate_stats($id, adrotate_date_start('day'));
$stats_last_month = adrotate_stats($id, gmmktime(0, 0, 0, gmdate("m")-1, 1, gmdate("Y")), gmmktime(0, 0, 0, gmdate("m"), 0, gmdate("Y")));
$stats_this_month = adrotate_stats($id, gmmktime(0, 0, 0, gmdate("m"), 1, gmdate("Y")), gmmktime(0, 0, 0, gmdate("m"), gmdate("t"), gmdate("Y")));
$stats_graph_month = adrotate_stats($id, $monthstart, $monthend);

// Prevent gaps in display
if(empty($stats['impressions'])) $stats['impressions'] = 0;
if(empty($stats['clicks']))	$stats['clicks'] = 0;
if(empty($stats_today['impressions'])) $stats_today['impressions'] = 0;
if(empty($stats_today['clicks'])) $stats_today['clicks'] = 0;
if(empty($stats_last_month['impressions'])) $stats_last_month['impressions'] = 0;
if(empty($stats_last_month['clicks'])) $stats_last_month['clicks'] = 0;
if(empty($stats_this_month['impressions'])) $stats_this_month['impressions'] = 0;
if(empty($stats_this_month['clicks'])) $stats_this_month['clicks'] = 0;
if(empty($stats_graph_month['impressions'])) $stats_graph_month['impressions'] = 0;
if(empty($stats_graph_month['clicks'])) $stats_graph_month['clicks'] = 0;

// Get Click Through Rate
$ctr = adrotate_ctr($stats['clicks'], $stats['impressions']);
$ctr_today = adrotate_ctr($stats_today['clicks'], $stats_today['impressions']);
$ctr_last_month = adrotate_ctr($stats_last_month['clicks'], $stats_last_month['impressions']);
$ctr_this_month = adrotate_ctr($stats_this_month['clicks'], $stats_this_month['impressions']);
$ctr_graph_month = adrotate_ctr($stats_graph_month['clicks'], $stats_graph_month['impressions']);
?>
<h2><?php _e("Statistics for advert", 'adrotate'); ?> '<?php echo stripslashes($banner->title); ?>'</h2>
<table class="widefat" style="margin-top: .5em">

	<thead>
  	<tr>
        <th colspan="3"><center><strong><?php _e("Today", 'adrotate'); ?></strong></center></th>
        <th>&nbsp;</th>
		<th colspan="3"><center><strong><?php _e("All time", 'adrotate'); ?></strong></center></th>
  	</tr>
	</thead>
	<tbody>
  	<tr>
        <td width="16%"><div class="stats_large"><?php _e("Impressions", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today['impressions']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("Clicks", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_today['clicks']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("CTR", 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr_today.' %'; ?></div></div></td>

         <td>&nbsp;</td>
 
		 <td><div class="stats_large"><?php _e("Impressions", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats['impressions']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("Clicks", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats['clicks']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("CTR", 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr.' %'; ?></div></div></td>
  	</tr>
	</tbody>
	<thead>
  	<tr>
        <th colspan="3"><center><strong><?php _e("Last month", 'adrotate'); ?></strong></center></th>
        <th>&nbsp;</th>
        <th colspan="3"><center><strong><?php _e("This month", 'adrotate'); ?></strong></center></th>
  	</tr>
	</thead>
	<tbody>
  	<tr>
        <td width="16%"><div class="stats_large"><?php _e("Impressions", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_last_month['impressions']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("Clicks", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_last_month['clicks']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("CTR", 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr_last_month.' %'; ?></div></div></td>

        <td>&nbsp;</td>
 
        <td width="16%"><div class="stats_large"><?php _e("Impressions", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_this_month['impressions']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("Clicks", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_this_month['clicks']; ?></div></div></td>
        <td width="16%"><div class="stats_large"><?php _e("CTR", 'adrotate'); ?><br /><div class="number_large"><?php echo $ctr_this_month.' %'; ?></div></div></td>
  	</tr>
	<tbody>

</table>

<h2><?php _e("Monthly overview of clicks and impressions", 'adrotate'); ?></h2>
<form method="post" action="admin.php?page=adrotate-statistics&view=advert&id=<?php echo $id; ?>">
<table class="widefat" style="margin-top: .5em">
	<tbody>
	<tr>
        <th colspan="3">
        	<div style="text-align:center;"><?php echo adrotate_stats_nav('ads', $id, $month, $year); ?></div>
        	<?php 
				echo adrotate_stats_graph('ads', $id, 1, $monthstart, $monthend); 
			?>
        </th>
	</tr>
	<tr>
        <td width="33%"><div class="stats_large"><?php _e("Impressions", 'adrotate'); ?><br /><div class="number_large"><?php echo $stats_graph_month['impressions']; ?></div></div></td>
        <td width="33%"><div class="stats_large"><?php _e("Clicks", 'adrotate'); ?><br /><div class="number_large"><?php if($banner->tracker == 'Y') { echo $stats_graph_month['clicks']; } else { echo '--'; } ?></div></div></td>
        <td width="34%"><div class="stats_large"><?php _e("CTR", 'adrotate'); ?><br /><div class="number_large"><?php if($banner->tracker == 'Y') { echo $ctr_graph_month.' %'; } else { echo '--'; } ?></div></div></td>
	</tr>
	</tbody>

</table>	
</form>

<p><center>
	<em><small><strong><?php _e("Note:", 'adrotate'); ?></strong> <?php _e("All statistics are indicative. They do not nessesarily reflect results counted by other parties.", 'adrotate'); ?></small></em>
</center></p>