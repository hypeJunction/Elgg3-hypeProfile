<?php
/**
 * @uses $vars['data-parsley-emailaccount'] Validate email availability
 */
if (empty($vars['data-parsley-emailaccount'])) {
	return;
}
?>
<script>
	require(['forms/validation/email']);
</script>