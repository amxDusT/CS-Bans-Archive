<?php
// FROM HASH: 32f7ed69ff7a02c7dd0184838a3bdb97
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if (!$__vars['options']['advanced_mode']) {
		$__finalCompiled .= '
	<div class="block">
		<div class="block-container">
			';
		if ($__vars['title']) {
			$__finalCompiled .= '
				<h3 class="block-minorHeader">' . $__templater->escape($__vars['title']) . '</h3>
			';
		}
		$__finalCompiled .= '
			<div class="block-body block-row">
				' . $__templater->filter($__vars['template'], array(array('raw', array()),), true) . '
			</div>
		</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="block">
	' . $__templater->filter($__vars['template'], array(array('raw', array()),), true) . '
	</div>
';
	}
	return $__finalCompiled;
}
);