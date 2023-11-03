<?php
// FROM HASH: 118726c6bca7756222fd99dae9664e5b
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->pageParams['pageTitle'] = $__templater->preEscaped($__templater->escape($__vars['category']['title']));
	$__finalCompiled .= '
';
	$__templater->pageParams['pageDescription'] = $__templater->preEscaped($__templater->filter($__vars['category']['description'], array(array('raw', array()),), true));
	$__templater->pageParams['pageDescriptionMeta'] = true;
	$__finalCompiled .= '

';
	if (!$__templater->method($__vars['category'], 'isSearchEngineIndexable', array())) {
		$__finalCompiled .= '
	';
		$__templater->setPageParam('head.' . 'metaNoindex', $__templater->preEscaped('<meta name="robots" content="noindex" />'));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('metadata_macros', 'canonical_url', array(
		'canonicalUrl' => $__templater->func('link', array('canonical:categories', $__vars['category'], ), false),
	), $__vars) . '

' . $__templater->callMacro('category_macros', 'category_page_options', array(
		'category' => $__vars['category'],
	), $__vars) . '
';
	$__templater->breadcrumbs($__templater->method($__vars['category'], 'getBreadcrumbs', array(false, )));
	$__finalCompiled .= '

';
	if ($__vars['nodeTree']) {
		$__finalCompiled .= '
	<div id="subForumsList" class="nodeList subForumsList">
	<div class="block block--nodes">
		<div class="block-outer">';
		$__compilerTemp1 = '';
		$__compilerTemp2 = '';
		$__compilerTemp2 .= '
						';
		if ($__vars['xf']['visitor']['user_id'] AND $__vars['hasForumDescendents']) {
			$__compilerTemp2 .= '
							' . $__templater->button('
								' . 'Mark read' . '
							', array(
				'href' => $__templater->func('link', array('categories/mark-read', $__vars['category'], array('date' => $__vars['xf']['time'], ), ), false),
				'class' => 'button--link',
				'overlay' => 'true',
			), '', array(
			)) . '
						';
		}
		$__compilerTemp2 .= '
					';
		if (strlen(trim($__compilerTemp2)) > 0) {
			$__compilerTemp1 .= '
				<div class="block-outer-opposite">
					<div class="buttonGroup">
					' . $__compilerTemp2 . '
					</div>
				</div>
			';
		}
		$__finalCompiled .= trim('
			' . $__compilerTemp1 . '
		') . '</div>
		<div class="block-container block-container--subforums">
			<div class="block-body">
				' . $__templater->callMacro('forum_list', 'node_list', array(
			'children' => $__vars['nodeTree'],
			'extras' => $__vars['nodeExtras'],
			'depth' => '2',
		), $__vars) . '
			</div>
		</div>
	</div>
	</div>
';
	} else {
		$__finalCompiled .= '
	<div class="blockMessage">' . 'There is nothing to display.' . '</div>
';
	}
	$__finalCompiled .= '

';
	$__templater->modifySidebarHtml('_xfWidgetPositionSidebarf53c56bedf5c2ab6b795108a08cd0061', $__templater->widgetPosition('category_view_sidebar', array(
		'category' => $__vars['category'],
	)), 'replace');
	return $__finalCompiled;
}
);