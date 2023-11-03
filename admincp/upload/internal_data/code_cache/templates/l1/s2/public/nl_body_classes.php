<?php
// FROM HASH: 98516e468f018d31fb7a0112efc511ad
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__templater->func('property', array('nlThemeClass', ), false) != null) {
		$__finalCompiled .= ' ' . $__templater->func('property', array('nlThemeClass', ), true);
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('nlEnableFullWidth', ), false)) {
		$__finalCompiled .= ' fullWidth';
	} else {
		$__finalCompiled .= ' fixedWidth';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('nlUseContentBoxShadows', ), false)) {
		$__finalCompiled .= ' contentShadows';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('nlUseHoverTransitions', ), false)) {
		$__finalCompiled .= ' hoverTransitions';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('nlPNavPadded', ), false)) {
		$__finalCompiled .= ' has-paddedNav';
	}
	$__finalCompiled .= '
';
	if ($__vars['page']['advanced_mode']) {
		$__finalCompiled .= ' pageAdvanced';
	}
	$__finalCompiled .= '
 blockStyle--' . $__templater->func('property', array('nlBlockPaddingStyle', ), true) . '
';
	if ($__templater->func('property', array('nlDataListUseAlternatingRows', ), false)) {
		$__finalCompiled .= ' dataListAltRows';
	}
	$__finalCompiled .= '
 tab-markers-' . $__templater->func('property', array('nlTabMarkerStyle', ), true) . '
';
	if ($__templater->func('property', array('nlContentLayout', ), false) == 'floating') {
		$__finalCompiled .= ' 
 floatingContent
	';
		if ($__templater->func('property', array('nlHeaderLayout', ), false)) {
			$__finalCompiled .= ' headerStretch';
		} else {
			$__finalCompiled .= ' headerFixed';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('nlStretchHeaderInnerContents', ), false)) {
			$__finalCompiled .= ' headerStretchInner';
		} else {
			$__finalCompiled .= ' headerFixedInner';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('nlStretchNavigation', ), false)) {
			$__finalCompiled .= ' stretchNavigation';
		} else {
			$__finalCompiled .= ' fixedNavigation';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('nlFooterLayout', ), false) == 'stretch') {
			$__finalCompiled .= ' footerStretch';
		} else if ($__templater->func('property', array('nlFooterLayout', ), false) == 'fixed') {
			$__finalCompiled .= ' footerFixed';
		}
		$__finalCompiled .= '
';
	} else if ($__templater->func('property', array('nlContentLayout', ), false) == 'boxed') {
		$__finalCompiled .= '
 boxedContent
	';
		if ($__templater->func('property', array('nlHeaderLayout', ), false) AND (!$__templater->func('property', array('nlForceHeaderFooterBoxedWidth', ), false))) {
			$__finalCompiled .= ' headerStretch';
		} else {
			$__finalCompiled .= ' headerFixed';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('nlStretchHeaderInnerContents', ), false) AND (!$__templater->func('property', array('nlForceHeaderFooterBoxedWidth', ), false))) {
			$__finalCompiled .= ' headerStretchInner';
		} else {
			$__finalCompiled .= ' headerFixedInner';
		}
		$__finalCompiled .= '
	';
		if ($__templater->func('property', array('nlStretchNavigation', ), false) AND (!$__templater->func('property', array('nlForceHeaderFooterBoxedWidth', ), false))) {
			$__finalCompiled .= ' stretchNavigation';
		}
		$__finalCompiled .= '
	';
		if (($__templater->func('property', array('nlFooterLayout', ), false) == 'stretch') AND (!$__templater->func('property', array('nlForceHeaderFooterBoxedWidth', ), false))) {
			$__finalCompiled .= ' footerStretch';
		} else if (($__templater->func('property', array('nlFooterLayout', ), false) == 'fixed') OR $__templater->func('property', array('nlForceHeaderFooterBoxedWidth', ), false)) {
			$__finalCompiled .= ' footerFixed';
		}
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

' . $__templater->callMacro('bodytag_macros', 'page_class_output', array(
		'pageMode' => $__vars['pageMode'],
		'showTitle' => $__vars['showTitle'],
		'showBreadcrumb' => $__vars['showBreadcrumb'],
		'showSidebar' => $__vars['showSidebar'],
		'showSidenav' => $__vars['showSidenav'],
		'showShare' => $__vars['showShare'],
		'pagePadding' => $__vars['pagePadding'],
	), $__vars);
	return $__finalCompiled;
}
);