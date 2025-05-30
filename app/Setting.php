<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	//For 
	protected $table = 'tbl_settings';

	protected $fillable = ['address', 'system_name', 'starting_year', 'phone_number', 'email', 'city_id', 'state_id', 'country_id', 'logo_image', 'cover_image', 'paypal_id', 'date_format', 'currancy','terms_and_condition'];
}
