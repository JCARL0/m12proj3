<?php

// DataTables PHP library
include("../lib/DataTables.php");

// Alias Editor classes so they are easy to use
use
DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Mjoin,
DataTables\Editor\Options,
DataTables\Editor\Upload,
DataTables\Editor\Validate,
DataTables\Editor\ValidateOptions;


/*
 * Example PHP implementation used for the join.html example
 */
Editor::inst($db, 'users')
	->field(
		Field::inst('users.first_name'),
		Field::inst('users.last_name'),
		Field::inst('users.phone'),
		Field::inst('users.site')
			->options(
				Options::inst()
					->table('sites')
					->value('id')
					->label('name')
			)
			->validator(Validate::required())
			->validator(Validate::dbValues()),
		Field::inst('sites.name')
	)
	->leftJoin('sites', 'sites.id', '=', 'users.site')
	->process($_POST)
	->json();
