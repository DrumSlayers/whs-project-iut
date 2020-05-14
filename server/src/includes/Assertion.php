<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
*	The object for the data access layer for assertions
*/
class Assertion extends \Illuminate\Database\Eloquent\Model {

	protected $table = 'assertion';
	protected $primaryKey = 'idassertion';
	public $timestamps = false;

}
