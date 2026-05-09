<?php
/**
 * @uses $vars['data-parsley-emailaccount'] Validate email availability
 */
if (empty($vars['data-parsley-emailaccount'])) {
	return;
}
?>
<?php elgg_import_esm('forms/validation/email'); ?>