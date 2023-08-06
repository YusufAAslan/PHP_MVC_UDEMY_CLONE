<?php 

/**
 * categories model
 */
class Category_model extends Model
{
	
	public $errors = [];
	protected $table = "categories";

	protected $allowedColumns = [

		'category',
		'disabled',
		 
	];

	public function validate($data)
	{
		$this->errors = [];

		if(empty($data['category']))
		{
			$this->errors['category'] = "A category is required";
		}else
		if(!preg_match("/^[a-zA-Z \&\']+$/", trim($data['category'])))
		{
			$this->errors['category'] = "category can only have letters and spaces or &";
		}
 
		
		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}


}