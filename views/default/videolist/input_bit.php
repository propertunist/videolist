<?php
foreach ($vars['videolist_variables'] as $name => $type) {
?>
<div>
	<label><?php echo elgg_echo("videolist:$name") ?></label>
	<?php
		if ($type != 'longtext') {
			echo '<br />';
		}
	?>
	<?php echo elgg_view("input/$type", array(
			'name' => $name,
			'value' => $vars[$name],
		));
	?>
</div>
<?php
}
