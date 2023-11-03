<?php
// FROM HASH: 172a7d332fb3e3356911fbc5ee81a07a
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	if ($__vars['xf']['options']['googleAnalyticsWebPropertyId']) {
		$__finalCompiled .= '
	<link rel="dns-prefetch" href="https://www.google-analytics.com">
	<script async src="https://www.googletagmanager.com/gtag/js?id=' . $__templater->escape($__vars['xf']['options']['googleAnalyticsWebPropertyId']) . '"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag(\'js\', new Date());
		gtag(\'config\', \'' . $__templater->filter($__vars['xf']['options']['googleAnalyticsWebPropertyId'], array(array('escape', array('js', )),), true) . '\', {
			// ' . '
			';
		if ($__vars['xf']['cookie']['domain']) {
			$__finalCompiled .= '
				\'cookie_domain\': \'' . $__templater->escape($__vars['xf']['cookie']['domain']) . '\',
			';
		}
		$__finalCompiled .= '
			';
		if ($__vars['xf']['options']['googleAnalyticsAnonymize']) {
			$__finalCompiled .= '
				\'anonymize_ip\': true,
			';
		}
		$__finalCompiled .= '
		});
	</script>
';
	}
	return $__finalCompiled;
}
);