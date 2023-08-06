<?php 

/**
 * languages model
 */
class Language_model extends Model
{
	
	public $errors = [];
	protected $table = "languages";

	protected $allowedColumns = [

		'symbol',
		'language', 	
		'disabled',
		 
	];

	public function validate($data)
	{
		$this->errors = [];

		if(empty($data['language']))
		{
			$this->errors['language'] = "A language is required";
		}

		if(empty($data['symbol']))
		{
			$this->errors['symbol'] = "A language code is required";
		}

 
		
		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}


}