<?php 

/**
 * levels model
 */
class Level_model extends Model
{
	
	public $errors = [];
	protected $table = "course_levels";

	protected $allowedColumns = [

		'level',
		'disabled',
		 
	];

	public function validate($data)
	{
		$this->errors = [];

		if(empty($data['level']))
		{
			$this->errors['level'] = "A level is required";
		}
 
		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}


}