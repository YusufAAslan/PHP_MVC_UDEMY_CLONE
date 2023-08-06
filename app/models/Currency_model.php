<?php 

/**
 * currencys model
 */
class Currency_model extends Model
{
	
	public $errors = [];
	protected $table = "currencies";

	protected $allowedColumns = [

		'symbol',
		'currency', 	
		'disabled',
		 
	];

	public function validate($data)
	{
		$this->errors = [];

		if(empty($data['currency']))
		{
			$this->errors['currency'] = "A currency is required";
		}

		if(empty($data['symbol']))
		{
			$this->errors['symbol'] = "A currency symbol is required";
		}

 
		
		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}


}