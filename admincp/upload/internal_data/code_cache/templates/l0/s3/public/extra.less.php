<?php
// FROM HASH: 82830a294d4e0504a4716e7ac9f6d8c9
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '//////////////////////////////////////////////////////////////////////////////
/////////////////////// Shades of Grey - CSS additions ///////////////////////
//////////////////////////////////////////////////////////////////////////////

/* scrollbar colors */
/* Works on Firefox-only */
* {
  scrollbar-width: 12px;
  scrollbar-color: @xf-paletteAccent1 @xf-paletteColor5 !important;
}
/* Works on Chrome/Edge/Safari */
*::-webkit-scrollbar {
  width: 12px;
  overflow: scroll;
}
*::-webkit-scrollbar-track {
  background-color: @xf-paletteColor5 !important;
}
*::-webkit-scrollbar-thumb {
  background-color: @xf-paletteAccent1 !important;
  border-radius: 20px;
}

/* enlarge Unfurl */
.bbCodeBlock--unfurl .contentRow-figure.contentRow-figure--fixedSmall
{
    width: 150px;
    img { max-height: 100px; }
	img { min-width: 100px; }
}
@media (max-width: 650px){
.bbCodeBlock--unfurl .contentRow-figure.contentRow-figure--fixedSmall
{
    width: 60px;
    img { max-height:60px; }
	img { min-width: 60px; }
}
}

/* media category selected override */
.categoryList-link.is-selected {
  color: white;
}

/* media number text override */
.embedTabs .embedTabs-tab.has-selected .badge.badge--highlighted {
    color: @xf-paletteColor2 !important;
}

/* overlay footer override */
.overlay {
	background-color: @xf-paletteNeutral2;
}

/* button link background overide */
.button.button--link, a.button.button--link {
	color: @xf-paletteAccent3;
	background: @xf-paletteNeutral1;
	border-color: @xf-paletteNeutral1;
}
.button.button--link:hover, a.button.button--link:hover, .button.button--link:active, a.button.button--link:active, .button.button--link:focus, a.button.button--link:focus {
    text-decoration: none;
    background: @xf-paletteColor3;
}

/* userBanner.userBanner--staff override */
.userBanner.userBanner--staff, .userBanner.userBanner--primary {
    color: @xf-paletteAccent3;
    background: mix(@xf-paletteAccent1, @xf-paletteNeutral2);
    border-color: @xf-paletteNeutral1;
}

/* Tag input field text fix */
.select2 .select2-selection ul .select2-search .select2-search__field {
    color: @xf-paletteColor2;
}
.select2-results__options li {
    background-color: @xf-paletteColor3;
}

/** center logo */
.p-header-logo{
	margin-left: auto;
}
/* end center logo*/';
	return $__finalCompiled;
}
);