<?php
/**
 * php file to define custom functions related to color for the mason custom widgets plugin
 */

/**
 * Reference:
 * 
 * Primary Colors
 * 005239 George Mason Green
 * ffc733 George Mason Gold
 *
 * Accent Colors
 * cc4824 Red
 * 008285 Teal
 * 727579 Gray
 * 004f71 Navy
 * 
 */

/**
 * Function to return an array of Mason brand colors
 */
function gmuj_brand_colors() {

	// Declare array of primary palette - signature colors
	$colors_primary[0] = "005239";
	$colors_primary[1] = "ffc733";

	// Declare array of secondary palette - supporting colors
	$colors_secondary_supporting[0] = "cc4824";
	$colors_secondary_supporting[1] = "008285";
	$colors_secondary_supporting[2] = "727579";
	$colors_secondary_supporting[3] = "004f71";

	// Merge arrays
	$brand_colors = array_merge($colors_primary,$colors_secondary_supporting);

	// Return value
	return $brand_colors;
}


/**
 * Function to return a random hex color code from the brand colors
 */
function gmuj_random_brand_color() {

	// Get brand color array
	$colors=gmuj_brand_colors();

	// Get random color value
	$random_item = array_rand($colors, 1);
	$random_color = $colors[$random_item];

	// Return value
	return $random_color;

}