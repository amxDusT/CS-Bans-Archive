<?php
// FROM HASH: fd486a77d075f6f4db9097487af3cdf4
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// This should be used for additional LESS setup code (that does not output anything).
// setup.less customizations should be avoided when possible.

.m-dotControls()
{
	.xf-nlDotControl();
}
.m-dotControlsHover()
{
	.xf-nlDotControlHover();
}
.m-stripElement()
{
	background: transparent none !important;
	padding: 0;
	border: none !important;
	box-shadow: none !important;
}
.m-buttonReset()
{
    .m-stripElement();
    color: inherit;
    text-transform: capitalize;
    font-weight: normal;
    padding: 0;
    display: inline;
}
.m-lineHeight(@lineHeight: 1.4)
{
	line-height: @lineHeight;
}
.m-lineHeightNormal()
{
	line-height: normal;
}
.m-lineHeightDefault()
{
	line-height: 1.4;
}
.m-flexAlignCenter()
{
	display: flex;
	align-items: center;
}

.m-fullTriangleLeft(@color; @size; @offset: 50%) {
	position: relative;
	
	&:before {
		content: "";
		position: absolute;
		left: -@size;
		top: @offset;
		transform: translateY(-50%);
	}
	&:before {
		.m-triangleLeft(@color, @size)
	}
}
.m-fullTriangleUp(@color; @size; @offset: 50%) {
	position: relative;
	
	&:before {
		content: "";
		position: absolute;
		top: -@size;
		margin: 0 auto;
		left: @offset;
		transform: translateX(-50%);
	}
	&:before {
		.m-triangleUp(@color, @size)
	}
}
.m-fullTriangleRight(@color; @size; @offset: 50%) {
	position: relative;
	
	&:after {
		content: "";
		position: absolute;
		right: -@size;
		top: @offset;
		transform: translateY(-50%);
	}
	&:after {
		.m-triangleRight(@color, @size)
	}
}
.m-fullTriangleDown(@color; @size; @offset: 50%) {
	position: relative;
	
	&:before {
		content: "";
		position: absolute;
		bottom: -@size;
		margin: 0 auto;
		left: @offset;
		transform: translateX(-50%);
	}
	&:before {
		.m-triangleDown(@color, @size)
	}
}

.m-dropShadow(@x: @xf-nlBoxShadowX; @y: @xf-nlBoxShadowY; @blur: @xf-nlBoxShadowBlur; @spread: @xf-nlBoxShadowSpread; @alpha: @xf-nlBoxShadowAlpha)
{
	box-shadow: @x @y @blur @spread fade(@xf-nlBoxShadowColor, (@alpha * 100));
}

.m-primaryGradient(@direction: to bottom; @startColor: @xf-gradientTop; @stopColor: @xf-gradientBottom;) {
	background: linear-gradient(@direction, @startColor, @stopColor);
}
.m-secondaryGradient(@direction: to bottom; @startColor: @xf-secondaryGradientTop; @stopColor: @xf-secondaryGradientBottom;) {
	background: linear-gradient(@direction, @startColor, @stopColor);
}
.m-lightGradient(@direction: to bottom; @startColor: @xf-lightGradientTop; @stopColor: @xf-lightGradientBottom;) {
	background: linear-gradient(@direction, @startColor, @stopColor);
}
.m-darkGradient(@direction: to bottom; @startColor: @xf-darkGradientTop; @stopColor: @xf-darkGradientBottom;) {
	background: linear-gradient(@direction, @startColor, @stopColor);
}';
	return $__finalCompiled;
}
);