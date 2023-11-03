<?php
// FROM HASH: 2c42ea5eca4a645a75c8aa0a92766b48
return array(
'macros' => array('category_list' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '0',
		'pathToSelected' => array(),
		'children' => '!',
		'extras' => '!',
		'isActive' => false,
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	<ol class="categoryList toggleTarget' . ($__vars['isActive'] ? ' is-active' : '') . '">
		';
	if ($__templater->isTraversable($__vars['children'])) {
		foreach ($__vars['children'] AS $__vars['id'] => $__vars['child']) {
			$__finalCompiled .= '
			' . $__templater->callMacro(null, 'category_list_item', array(
				'selected' => $__vars['selected'],
				'pathToSelected' => $__vars['pathToSelected'],
				'category' => $__vars['child']['record'],
				'extras' => $__vars['extras'][$__vars['id']],
				'children' => $__vars['child'],
				'childExtras' => $__vars['extras'],
			), $__vars) . '
		';
		}
	}
	$__finalCompiled .= '
	</ol>
';
	return $__finalCompiled;
}
),
'category_list_item' => array(
'arguments' => function($__templater, array $__vars) { return array(
		'selected' => '!',
		'pathToSelected' => array(),
		'category' => '!',
		'extras' => '!',
		'children' => '!',
		'childExtras' => '!',
	); },
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

	';
	$__vars['isSelected'] = ($__vars['category']['category_id'] == $__vars['selected']);
	$__finalCompiled .= '
	';
	$__vars['hasPathToSelected'] = $__vars['pathToSelected'][$__vars['category']['category_id']];
	$__finalCompiled .= '
	';
	$__vars['isActive'] = ($__vars['isSelected'] OR ($__vars['hasPathToSelected'] AND !$__templater->test($__vars['children'], 'empty', array())));
	$__finalCompiled .= '

	<li class="categoryList-item">
		<div class="categoryList-itemRow' . ($__vars['isSelected'] ? ' is-selected' : '') . '">
			';
	if (!$__templater->test($__vars['children'], 'empty', array())) {
		$__finalCompiled .= '
				<a class="categoryList-toggler' . ($__vars['isActive'] ? ' is-active' : '') . '"
					data-xf-click="toggle" data-target="< :up :next"
					role="button" tabindex="0" aria-label="' . 'Toggle expanded' . '"></a>
			';
	} else {
		$__finalCompiled .= '
				<span class="categoryList-togglerSpacer"></span>
			';
	}
	$__finalCompiled .= '
			<a href="' . $__templater->func('link', array('products/categories', $__vars['category'], ), true) . '" class="categoryList-link' . ($__vars['isSelected'] ? ' is-selected' : '') . '">
				' . $__templater->escape($__vars['category']['title']) . '
			</a>
			<span class="categoryList-label">
				<span class="label label--subtle label--smallest">' . $__templater->filter($__vars['extras']['product_count'], array(array('number_short', array()),), true) . '</span>
			</span>
		</div>
		';
	if (!$__templater->test($__vars['children'], 'empty', array())) {
		$__finalCompiled .= '
			' . $__templater->callMacro(null, 'category_list', array(
			'selected' => $__vars['selected'],
			'pathToSelected' => $__vars['pathToSelected'],
			'children' => $__vars['children'],
			'extras' => $__vars['childExtras'],
			'isActive' => $__vars['isActive'],
		), $__vars) . '
		';
	}
	$__finalCompiled .= '
	</li>
';
	return $__finalCompiled;
}
)),
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '

';
	return $__finalCompiled;
}
);