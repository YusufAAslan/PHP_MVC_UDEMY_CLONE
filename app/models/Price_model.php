<?php 

/**
 * prices model
 */
class Price_model extends Model
{
	
	public $errors = [];
	protected $table = "prices";

	protected $allowedColumns = [

		'name',
		'price', 	
		'disabled',
		 
	];

	public function validate($data)
	{
		$this->errors = [];

		if(empty($data['price']))
		{
			$this->errors['price'] = "A price is required";
		}

		if(empty($data['name']))
		{
			$this->errors['name'] = "A price name is required";
		}
 
		
		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}


}