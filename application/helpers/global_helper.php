<?php

defined('BASEPATH') OR exit('No direct script access allowed');


function get_children_status($children_status)
{
	switch ($children_status) {
		case CHILDREN_NOT_APPLICAPLE:
			return "Not Applicable";
			break;
		case CHILDREN_YES:
			return "Yes";
			break;
		case CHILDREN_NO:
			return "No";
			break;
		default:
			return "";
			break;
	}
}

function get_beard_type($beard)
{
	switch ($beard) {
		case "yes_sunnah":
			return "Yes Sunnah Beard";
			break;
		case "no_sunnah":
			return "Yes but not as per Sunnah";
			break;
		case "no_beard":
			return "No Beard";
			break;
		default:
			return "-";
			break;
	}
}

function get_namaz_type($namaz)
{
	switch ($namaz) {
		case "regular":
			return "Regular 5 times a day";
			break;
		case "sometime":
			return "Sometimes but not all 5 times";
			break;
		case "friday":
			return "Only Jumuah (Friday prayer)";
			break;
		default:
			return "-";
			break;
	}
}

function get_fasting_type($fasting)
{
	switch ($fasting) {
		case "ramzan":
			return "Only in Ramzan";
			break;
		case "ramzan_sunnah":
			return "Ramzan and Sunnah";
			break;
		case "ramzan_sunnah_nawafil":
			return "Ramzan some Sunnah and some Nawafil fasts";
			break;
		default:
			return "-";
			break;
	}
}

function pr($data)
{
	echo '<pre>'; print_r($data);exit;
}
?>