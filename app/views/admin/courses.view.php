<?php $this->view('admin/admin-header',$data) ?>

<style>
  
  .tabs-holder{
    display: flex;
    margin-top: 10px; 
    margin-bottom: 10px;
    justify-content: center;
    text-align: center;
    flex-wrap: wrap;
  }

  .my-tab{
    flex:1;
    border-bottom: solid 2px #ccc;
    padding-top: 10px;
    padding-bottom: 10px;
    cursor: pointer;
    user-select: none;
    min-width: 150px;

  }
  .my-tab:hover{
    color: #4154f1;
  }

  .active-tab{
    color: #4154f1;
    border-bottom: solid 2px #4154f1;
  }

  .hide{
    display: none;
  }

  .loader{
    position: relative;
    width:200px;
    height:200px;
    left: 50%;
    top: 50%;
    transform: translateX(-50%);
    opacity: 0.5;
  }

</style>
<?php if($action == 'add'):?>

  <div class="card col-md-5 mx-auto">
    <div class="card-body">
      <h5 class="card-title">New Course</h5>

      <!-- No Labels Form -->
      <form method="post" class="row g-3">
        
        <div class="col-md-12">
          <input value="<?=set_value('title')?>" name="title" type="text" class="form-control <?=!empty($errors['title']) ? 'border-danger':'';?>" placeholder="Course title">

          <?php if(!empty($errors['title'])):?>
            <small class="text-danger"><?=$errors['title']?></small>
          <?php endif;?>

        </div>

        <div class="col-md-12">
          <input value="<?=set_value('primary_subject')?>" name="primary_subject" type="text" class="form-control <?=!empty($errors['primary_subject']) ? 'border-danger':'';?>" placeholder="Primary subject e.g Photography or Vlogging">

          <?php if(!empty($errors['primary_subject'])):?>
            <small class="text-danger"><?=$errors['primary_subject']?></small>
          <?php endif;?>

        </div>
 
 
        <div class="col-md-12">
          <select name="category_id" id="inputState" class="form-select <?=!empty($errors['category_id']) ? 'border-danger':'';?>">
            
            <option value="" selected="">Course Category...</option>
            <?php if(!empty($categories)):?>  
              <?php foreach($categories as $cat):?>
                <option <?=set_select('category_id',$cat->id)?> value="<?=$cat->id?>"><?=esc($cat->category)?></option>
              <?php endforeach;?>
            <?php endif;?>

          </select>

          <?php if(!empty($errors['category_id'])):?>
            <small class="text-danger"><?=$errors['category_id']?></small>
          <?php endif;?>

        </div>
    
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Save</button>

          <a href="<?=ROOT?>/admin/courses">
            <button type="button" class="btn btn-secondary">Cancel</button>
          </a>
        </div>
      </form><!-- End No Labels Form -->

    </div>
  </div>

<?php elseif($action == 'edit'):?>

  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Edit Course</h5>

      <?php if(!empty($row)):?>

        <div class="float-end">
          <button onclick="save_content()" class="js-save-button btn btn-success disabled">Save</button>
          <a href="<?=ROOT?>/admin/courses">
            <button class="btn btn-primary">Back</button>
          </a>
        </div>
        
        <h5 class=""><?=esc($row->title)?></h5>

        <!--tabs-->
          <br>
          <div class="tabs-holder">
            <div onclick="set_tab(this.id,this)" id="intended-learners" class="my-tab active-tab">Intended Learners</div>
            <div onclick="set_tab(this.id,this)" id="curriculum" class="my-tab">Curriculum</div>
            <div onclick="set_tab(this.id,this)" id="course-landing-page" class="my-tab">Course landing page</div>
            <div onclick="set_tab(this.id,this)" id="promotions" class="my-tab">Promotions</div>
            <div onclick="set_tab(this.id,this)" id="course-messages" class="my-tab">Course messages</div>
          </div>
        <!--end tabs-->
        <!--div-tabs-->
        <div oninput="something_changed(event)" >
          <div id="tabs-content">
            
          </div>
        </div>
        <!--end div-tabs-->
 

      <?php else:?>
        <div>That course was not found!</div>
      <?php endif;?>

    </div>
  </div>

<?php else:?>

  <div class="card">

    <div class="card-body">
      <h5 class="card-title">
        My Courses 
        <a href="<?=ROOT?>/admin/courses/add">
          <button class="btn btn-primary float-end"><i class="bi bi-camera-video-fill"></i> New Course</button>
        </a>
      </h5>

      <!-- Table with stripped rows -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Instructor</th>
            <th scope="col">Category</th>
            <th scope="col">Price</th>
            <th scope="col">Primary Subject</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
          </tr>
        </thead>

        <?php if(!empty($rows)):?>
          <tbody>

            <?php foreach($rows as $row):?>
              <tr>
                <th scope="row"><?=$row->id?></th>
                <td><?=esc($row->title)?></td>
                <td><?=esc($row->user_row->name ?? 'Unknown')?></td>
                <td><?=esc($row->category_row->category ?? 'Unknown')?></td>
                <td><?=esc($row->price_row->name ?? 'Unknown')?></td>
                <td><?=esc($row->primary_subject)?></td>
                <td><?=get_date($row->date)?></td>
                <td>
                  <a href="<?=ROOT?>/admin/courses/edit/<?=$row->id?>">
                    <i class="bi bi-pencil-square"></i> 
                  </a>
                  <a href="<?=ROOT?>/admin/courses/delete/<?=$row->id?>">
                    <i class="bi bi-trash-fill text-danger"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach;?>

          </tbody>
        <?php else:?>
          <tr><td colspan="10">No records found!</td></tr>
        <?php endif;?>

      </table>
      <!-- End Table with stripped rows -->

    </div>
  </div>

<?php endif;?>

<script>
  
  var tab = sessionStorage.getItem("tab") ? sessionStorage.getItem("tab"): "intended-learners";
  var dirty = false;

  function show_tab(tab_name)
  {
 
    var contentDiv = document.querySelector("#tabs-content");
    show_loader(contentDiv);

    //change active tab
    var div = document.querySelector("#"+tab_name);
    var children = div.parentNode.children;
    for (var i = 0; i < children.length; i++) {
      children[i].classList.remove("active-tab");
    }

    div.classList.add("active-tab");


    send_data({
      tab_name:tab,
      data_type:"read",
    });

    disable_save_button(false);

  }

  function send_data(obj)
  {
 
    var myform = new FormData();
    for(key in obj){
      myform.append(key,obj[key]); 
    }

    var ajax = new XMLHttpRequest();

    ajax.addEventListener('readystatechange',function(){

      if(ajax.readyState == 4){

        if(ajax.status == 200){
          //everything went well
          //alert("upload complete");
          handle_result(ajax.responseText);
        }else{
          //error
          alert("an error occurred");
        }
      }
    });
 
    ajax.open('post','',true);
    ajax.send(myform);

  }


  function handle_result(result)
  {

    //console.log(result);
    if(result.substr(0,2) == '{"')
    {
      var obj = JSON.parse(result);
      if(typeof obj == 'object'){

        if(obj.data_type == "save"){

          alert("data saved");
          disable_save_button(false);
        }

      }

    }else{

      var contentDiv = document.querySelector("#tabs-content");
      contentDiv.innerHTML = result;
    }

  }

  function set_tab(tab_name)
  {

    if(dirty)
    {
      //ask user to save when switching tabs
      if(!confirm("Your changes were not saved. continue?!"))
      {
        return;
      }
    }

    tab = tab_name;
    sessionStorage.setItem("tab", tab_name);

    dirty = false;
    show_tab(tab_name);

  }

  function something_changed(e)
  {
    dirty = tab;
    disable_save_button(true);
  }

  function disable_save_button(status = false)
  {
    if(status){
      document.querySelector(".js-save-button").classList.remove("disabled");
    }else{
      document.querySelector(".js-save-button").classList.add("disabled");
    }
  }

  function show_loader(item)
  {
    item.innerHTML = '<img class="loader" src="<?=ROOT?>/assets/images/loader.gif">';
  }

  show_tab(tab);

  /*******************
  for saving content
  ********************/

  function save_content()
  {
    var content = document.querySelector("#tabs-content");
    var inputs = content.querySelectorAll("input,textarea,select");

    var obj = {};
    obj.data_type = "save";
    obj.tab_name = tab;

    for (var i = 0; i < inputs.length; i++) {
       
      var key = inputs[i].name;
      obj[key] = inputs[i].value;

    }

    send_data(obj);

  }

</script>
<?php $this->view('admin/admin-footer',$data) ?>