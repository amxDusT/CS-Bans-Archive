<?php
// FROM HASH: 34a3da24aa525f04b1b83a4b1d476df4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '@_nav-elTransitionSpeed: @xf-animationSpeed;
@_navAccount-hPadding: @xf-paddingLarge;

.u-anchorTarget
{
	.m-stickyHeaderConfig(@xf-publicNavSticky);
	height: (@_stickyHeader-height + @_stickyHeader-offset);
	margin-top: -(@_stickyHeader-height + @_stickyHeader-offset);
}

@supports (scroll-padding-top: 10px)
{
	html:not(.has-browser-safari)
	{
		.m-stickyHeaderConfig(@xf-publicNavSticky);
		scroll-padding-top: (@_stickyHeader-height + @_stickyHeader-offset);
	}

	html:not(.has-browser-safari) .u-anchorTarget
	{
		height: 0;
		margin-top: 0;
	}
}

.m-footerWidth(@min-width: @xf-pageEdgeSpacer)
{
	max-width: @xf-nlFooterContentMax;
	width: 100%;
	padding: 0 @xf-pageEdgeSpacer;
	margin: 0 auto;
}

.p-pageWrapper
{
	position: relative;
	display: flex;
	flex-direction: column;
	min-height: 100vh;
	.xf-pageBackground();

	.is-modalOverlayOpen &
	{
		& when (unit(xf-default(@xf-overlayMaskBlur, 0)) > 0)
		{
			filter: blur(@xf-overlayMaskBlur);
		}
	}
}

// RESPONSIVE HEADER

.p-offCanvasAccountLink
{
	display: none;
}

@media (max-width: 359px)
{
	.p-offCanvasAccountLink, .p-offCanvasRegisterLink
	{
		display: block;
	}
}

' . $__templater->includeTemplate('app_staffbar.less', $__vars) . '
' . $__templater->includeTemplate('app_header.less', $__vars) . '
' . $__templater->includeTemplate('app_stickynav.less', $__vars) . '
' . $__templater->includeTemplate('app_nav.less', $__vars) . '
' . $__templater->includeTemplate('app_sectionlinks.less', $__vars) . '
' . $__templater->includeTemplate('app_body.less', $__vars) . '
' . $__templater->includeTemplate('app_breadcrumbs.less', $__vars) . '
' . $__templater->includeTemplate('app_title.less', $__vars) . '
' . $__templater->includeTemplate('app_footer.less', $__vars) . '
' . $__templater->includeTemplate('app_inlinemod.less', $__vars) . '
' . $__templater->includeTemplate('app_ignored.less', $__vars) . '
' . $__templater->includeTemplate('app_username_styles.less', $__vars) . '
' . $__templater->includeTemplate('app_user_banners.less', $__vars) . '
' . $__templater->includeTemplate('app_alerts.less', $__vars) . '
' . $__templater->includeTemplate('app_content_vote.less', $__vars) . '

' . $__templater->includeTemplate('nl_app.less', $__vars);
	return $__finalCompiled;
}
);