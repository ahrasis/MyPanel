<?php

/*
	Form Definition

	Tabledefinition

	Datatypes:
	- INTEGER (Forces the input to Int)
	- DOUBLE
	- CURRENCY (Formats the values to currency notation)
	- VARCHAR (no format check, maxlength: 255)
	- TEXT (no format check)
	- DATE (Dateformat, automatic conversion to timestamps)

	Formtype:
	- TEXT (Textfield)
	- TEXTAREA (Textarea)
	- PASSWORD (Password textfield, input is not shown when edited)
	- SELECT (Select option field)
	- RADIO
	- CHECKBOX
	- CHECKBOXARRAY
	- FILE

	VALUE:
	- Wert oder Array

	Hint:
	The ID field of the database table is not part of the datafield definition.
	The ID field must be always auto incement (int or bigint).


*/
global $app;

$form["title"]    = "DNS TXT Record";
$form["description"]  = "";
$form["name"]    = "dns_txt";
$form["action"]   = "dns_txt_edit.php";
$form["db_table"]  = "dns_rr";
$form["db_table_idx"] = "id";
$form["db_history"]  = "yes";
$form["tab_default"] = "dns";
$form["list_default"] = "dns_a_list.php";
$form["auth"]   = 'yes'; // yes / no

$form["auth_preset"]["userid"]  = 0; // 0 = id of the user, > 0 id must match with id of current user
$form["auth_preset"]["groupid"] = 0; // 0 = default groupid of the user, > 0 id must match with groupid of current user
$form["auth_preset"]["perm_user"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_group"] = 'riud'; //r = read, i = insert, u = update, d = delete
$form["auth_preset"]["perm_other"] = ''; //r = read, i = insert, u = update, d = delete

$form["tabs"]['dns'] = array (
	'title'  => "DNS TXT",
	'width'  => 100,
	'template'  => "templates/dns_txt_edit.htm",
	'fields'  => array (
		//#################################
		// Begin Datatable fields
		//#################################
		'server_id' => array (
			'datatype' => 'INTEGER',
			'formtype' => 'SELECT',
			'default' => '',
			'value'  => '',
			'width'  => '30',
			'maxlength' => '255'
		),
		'zone' => array (
			'datatype' => 'INTEGER',
			'formtype' => 'TEXT',
			'default' => @$app->functions->intval($_REQUEST["zone"]),
			'value'  => '',
			'width'  => '30',
			'maxlength' => '255'
		),
		'name' => array (
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'filters'   => array( 0 => array( 'event' => 'SAVE',
					'type' => 'IDNTOASCII'),
				1 => array( 'event' => 'SHOW',
					'type' => 'IDNTOUTF8'),
				2 => array( 'event' => 'SAVE',
					'type' => 'TOLOWER')
			),
			'validators' => array (  0 => array ( 'type' => 'REGEX',
					'regex' => '/^(\*\.|[a-zA-Z0-9\.\-\_]){0,255}$/',
					'errmsg'=> 'name_error_regex'),
			),
			'default' => '',
			'value'  => '',
			'width'  => '30',
			'maxlength' => '255'
		),
		'type' => array (
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'default' => 'TXT',
			'value'  => '',
			'width'  => '5',
			'maxlength' => '5'
		),
		'data' => array (
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'validators' => array (  
				0 => array ( 
					'type' => 'NOTEMPTY', 
					'errmsg'=> 'data_error_empty'
				),
				1 => array ( 
					'type' => 'REGEX', 
					'regex' => "/^((?!v=DKIM).)*$/s", 
					'errmsg'=> 'invalid_type_dkim'
				),
				2 => array ( 
					'type' => 'REGEX', 
					'regex' => "/^((?!v=DMARC1; ).)*$/s", 
					'errmsg'=> 'invalid_type_dmarc'),
				3 => array ( 
					'type' => 'REGEX', 
					'regex' => "/^((?!v=spf).)*$/s", 
					'errmsg'=> 'invalid_type_spf'
				),
			),
			'default' => '',
			'value'  => '',
			'width'  => '30',
			'maxlength' => '255'
		),
		'ttl' => array (
			'datatype' => 'INTEGER',
			'formtype' => 'TEXT',
			'validators' => array (  0 => array ( 'type' => 'RANGE',
					'range' => '60:',
					'errmsg'=> 'ttl_range_error'),
			),
			'default' => '3600',
			'value'  => '',
			'width'  => '10',
			'maxlength' => '10'
		),
		'active' => array (
			'datatype' => 'VARCHAR',
			'formtype' => 'CHECKBOX',
			'default' => 'Y',
			'value'  => array(0 => 'N', 1 => 'Y')
		),
		'stamp' => array (
			'datatype' => 'VARCHAR',
			'formtype' => 'TEXT',
			'default' => '',
			'value'  => '',
			'width'  => '30',
			'maxlength' => '255'
		),
		'serial' => array (
			'datatype' => 'INTEGER',
			'formtype' => 'TEXT',
			'default' => '',
			'value'  => '',
			'width'  => '10',
			'maxlength' => '10'
		),
		//#################################
		// END Datatable fields
		//#################################
	)
);

if($app->auth->is_admin()) {
	unset($form["tabs"]['dns']['fields']['data']['validators']);
	$form["tabs"]['dns']['fields']['data']['validators'][0]['type'] = 'NOTEMPTY';
	$form["tabs"]['dns']['fields']['data']['validators'][0]['errmsg'] = 'data_error_empty';
	$form["tabs"]['dns']['fields']['data']['maxlength'] = 512;
}
?>
