<?php 

/**
 * admin class
 */
class Admin extends Controller
{
	
	public function index()
	{

		if(!Auth::logged_in())
		{
			message('please login to view the admin section');
			redirect('login');
		}

		$data['title'] = "Dashboard";

		$this->view('admin/dashboard',$data);
	}

	public function courses($action = null, $id = null)
	{

		if(!Auth::logged_in())
		{
			message('please login to view the admin section');
			redirect('login');
		}

		$user_id = Auth::getId();
		$course = new Course_model();
		$category = new Category_model();
		$language = new Language_model();
		$level = new Level_model();
		$price = new Price_model();
		$currency = new Currency_model();

		$data = [];
		$data['action'] = $action;
		$data['id'] = $id;

		if($action == 'add')
		{
			
			$data['categories'] = $category->findAll('asc');

			if($_SERVER['REQUEST_METHOD'] == "POST")
			{
				if($course->validate($_POST))
				{
					
					$_POST['date'] = date("Y-m-d H:i:s");
					$_POST['user_id'] = $user_id;
					$_POST['price_id'] = 1;

					$course->insert($_POST);
				
					$row = $course->first(['user_id'=>$user_id,'published'=>0]);
					message("Your Course was successfuly created");

					if($row){
						redirect('admin/courses/edit/'.$row->id);
					}else{
						redirect('admin/courses');
					}
				}

				$data['errors'] = $course->errors;
			}

		}
		elseif($action == 'edit')
		{
			$categories = $category->findAll('asc');
			$languages = $language->findAll('asc');
			$levels = $level->findAll('asc');
			$prices = $price->findAll('asc');
			$currencies = $currency->findAll('asc');

			//get course information
			$data['row'] = $row = $course->first(['user_id'=>$user_id,'id'=>$id]);
			
			if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
			{
				if(!empty($_POST['data_type']) && $_POST['data_type'] == "read")
				{
					if($_POST['tab_name'] == "course-landing-page")
					{

						include views_path('course-edit-tabs/course-landing-page');

					}	

				}else
				if(!empty($_POST['data_type']) && $_POST['data_type'] == "save")
				{
					if($_POST['tab_name'] == "course-landing-page")
					{
						
						$info['data'] = "";
						$info['data_type'] = "save";

						echo json_encode($info);
					}	

				}

				die;
			}

		}else
		{

			//courses view
			$data['rows'] = $course->where(['user_id'=>$user_id]);

		}

		$this->view('admin/courses',$data);
	}

	public function profile($id = null)
	{

		if(!Auth::logged_in())
		{
			message('please login to view the admin section');
			redirect('login');
		}

		$id = $id ?? Auth::getId();

		$user = new User();
		$data['row'] = $row = $user->first(['id'=>$id]);

		if($_SERVER['REQUEST_METHOD'] == "POST" && $row)
		{
		
			$folder = "uploads/images/";
			if(!file_exists($folder))
			{
				mkdir($folder,0777,true);
				file_put_contents($folder."index.php", "<?php //silence");
				file_put_contents("uploads/index.php", "<?php //silence");
			}
 
 			if($user->edit_validate($_POST,$id))
 			{

				$allowed = ['image/jpeg','image/png'];

				if(!empty($_FILES['image']['name'])){

					if($_FILES['image']['error'] == 0){

						if(in_array($_FILES['image']['type'], $allowed))
						{
							//everything good
							$destination = $folder.time().$_FILES['image']['name'];
							move_uploaded_file($_FILES['image']['tmp_name'], $destination);

							resize_image($destination);
							$_POST['image'] = $destination;
							if(file_exists($row->image))
							{
								unlink($row->image);
							}

						}else{
							$user->errors['image'] = "This file type is not allowed";
						}
					}else{
						$user->errors['image'] = "Could not upload image";
					}
				}

				$user->update($id,$_POST);

				//message("Profile saved successfully");
				//redirect('admin/profile/'.$id);
 			}

			if(empty($user->errors)){
				$arr['message'] = "Profile saved successfully";
			}else{
				$arr['message'] = "Please correct these errors";
				$arr['errors'] = $user->errors;
			}

			echo json_encode($arr);

 			die;
		}

		$data['title'] = "Profile";
		$data['errors'] = $user->errors;

		$this->view('admin/profile',$data);
	}

}