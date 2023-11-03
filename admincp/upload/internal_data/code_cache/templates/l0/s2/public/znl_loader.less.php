<?php
// FROM HASH: 83b675833960b5c4b4364e4d5af02fda
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= $__templater->includeTemplate('nl_base.less', $__vars) . '
' . $__templater->includeTemplate('nl_mod.less', $__vars) . '
' . $__templater->includeTemplate('nl_style.less', $__vars);
	return $__finalCompiled;
}
);