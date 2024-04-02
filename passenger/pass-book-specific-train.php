<?php
    session_start();
    include('assets/inc/config.php');
    include('assets/inc/checklogin.php');
    check_login();
    $aid=$_SESSION['pass_id'];
  //   if (isset($_SESSION['Book_Train_clicked']) && $_SESSION['Book_Train_clicked'] === true) {
  //     // Check if the timestamp of the last click is more than 30 seconds ago
  //     if (isset($_SESSION['Book_Train_clicked_time']) && (time() - $_SESSION['Book_Train_clicked_time']) > 10) {
  //         // Reset the session variable
  //         $_SESSION['Book_Train_clicked'] = false;
  //         unset($_SESSION['Book_Train_clicked_time']); // unset the time variable
  //     } else {
  //         // Set an error message
  //         $err = "Train already Reserved! Please Proceed To Check Out";
  //         // You can redirect or display an error message as per your requirement
  //         $_SESSION['Book_Train_clicked'] = false;
  //         unset($_SESSION['Book_Train_clicked_time']); // unset the time variable
  //     }
  // }
    // if(isset($_POST['Book_Train']))
    // {
    //         $_SESSION['Book_Train_clicked'] = true;
    //         $_SESSION['Book_Train_clicked_time'] = time();
    if(isset($_POST['Book_Train'])){
      if (isset($_SESSION['Book_Train_clicked_time']) && (time() - $_SESSION['Book_Train_clicked_time']) <= 10) {
        $err = "Train already Reserved! Please Proceed To Check Out";
    } else {
        $_SESSION['Book_Train_clicked_time'] = time();
            $pass_train_number = $_POST['pass_train_number'];
            $pass_train_name = $_POST['pass_train_name'];
            $pass_train_route = $_POST['pass_train_route'];
            $pass_dep_date = $_POST['pass_dep_date'];
            $pass_dep_station_time = $_POST['pass_dep_station_time'];
            $pass_arr_station_time = $_POST['pass_arr_station_time'];
            $pass_train_fare = $_POST['pass_train_fare'];
            //sql file to update the table of passengers with the new captured information
            $query="update  orrs_passenger set pass_train_number = ?, pass_train_name = ?, pass_train_route = ?, pass_dep_date = ?, pass_dep_station_time = ?,  pass_arr_station_time = ?, pass_train_fare = ? where pass_id=?";
            $stmt = $mysqli->prepare($query); //prepare sql and bind it later
            $rc=$stmt->bind_param('sssssssi', $pass_train_number, $pass_train_name, $pass_train_route, $pass_dep_date, $pass_dep_station_time, $pass_arr_station_time, $pass_train_fare, $aid);
            $stmt->execute();
            if($stmt)
            {
                $succ = "Reserved Train PLease Proceed To Check Out";
            }
            else 
            {
                $err = "Please Try Again Later";
            }
            #echo"<script>alert('Your Profile Has Been Updated Successfully');</script>";
            }
          }
?>
<!DOCTYPE html>
<html lang="en">
<!--Head-->
<?php include('assets/inc/head.php');?>
<!--End Head-->
  <body>
    <div class="be-wrapper be-fixed-sidebar ">
    <!--Navigation Bar-->
      <?php include('assets/inc/navbar.php');?>
      <!--End Navigation Bar-->

      <!--Sidebar-->
      <?php include('assets/inc/sidebar.php');?>
      <!--End Sidebar-->
      <div class="be-content">
        <div class="page-head">
          <h2 class="page-head-title">Book Train </h2>
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb page-head-nav">
              <li class="breadcrumb-item"><a href="pass-dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Book Train</a></li>
              <li class="breadcrumb-item active">Reserve Seat</li>
            </ol>
          </nav>
        </div>
            <?php if(isset($succ)) {?>
                                <!--This code for injecting an alert-->
                <script>
                            setTimeout(function () 
                            { 
                                swal("Success!","<?php echo $succ;?>!","success");
                            },
                                100);
                </script>

        <?php } ?>
        <?php if(isset($err)) {?>
  <!--This code for injecting an alert-->
      <script>
            setTimeout(function () 
            { 
              swal("Failed!","<?php echo $err;?>!","error");
            },
              100);
      </script>
					
			<?php } ?>
        <div class="main-content container-fluid">
        <?php
            $aid=$_SESSION['pass_id'];
            $ret="select * from orrs_passenger where pass_id=?";
            $stmt= $mysqli->prepare($ret) ;
            $stmt->bind_param('i',$aid);
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            //$cnt=1;
            while($row=$res->fetch_object())
        {
        ?>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-border-color card-border-color-success">
                <div class="card-header card-header-divider"><span class="card-subtitle">Fill All Details</span></div>
                <div class="card-body">
                  <form method ="POST">
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3"> First Name</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_fname" value="<?php echo $row->pass_fname;$row->pass_lname;?>" id="inputText3" type="text">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3"> Last Name</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_lname" value="<?php echo $row->pass_lname;?>" id="inputText3" type="text">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3"> Phone Number</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_phone" value="<?php echo $row->pass_phone;?>" id="inputText3" type="text">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3"> Address</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_addr" value="<?php echo $row->pass_addr;?>" id="inputText3" type="text">
                      </div>
                    </div>

                    <!--Lets get the details of one single train using its Train Id 
                    and pass it to this user instance-->
                    <?php
                        $id=$_GET['id'];
                        $ret="select * from orrs_train where id=?";
                        $stmt= $mysqli->prepare($ret) ;
                        $stmt->bind_param('i',$id);
                        $stmt->execute() ;//ok
                        $res=$stmt->get_result();
                        //$cnt=1;
                        while($row=$res->fetch_object())
                    {
                    ?>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Train Number</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_train_number" value="<?php echo $row->number;?>" id="inputText3" type="text">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Train Name</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_train_name" value="<?php echo $row->name;?>" id="inputText3" type="text">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Train Route</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_train_route" value="<?php echo $row->route;?>" id="inputText3">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Departure Date</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_dep_date" value="<?php echo $row->date;?>" id="inputText3">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Departure Time</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_dep_station_time" value="<?php echo $row->departure_time;?>" id="inputText3">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Arrival Time</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_arr_station_time" value="<?php echo $row->arrival_time;?>" id="inputText3">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right" for="inputText3">Train Fare</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <input class="form-control" readonly name="pass_train_fare" value="<?php echo $row->fare;?>"  id="inputText3" type="text">
                      </div>
                    </div>
                    <!--End TRain  isntance-->
                    <?php }?>

                    <div class="col-sm-6">
                        <p class="text-right">
                          <input class="btn btn-space btn-outline-success" value ="Book Train" name = "Book_Train" type="submit" onclick="disableButton()">
                          <!-- <button class="btn btn-space btn-outline-danger">Cancel</button> -->
                        </p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
       
        <?php }?>
        
        </div>
        <!--footer-->
        <?php include('assets/inc/footer.php');?>
        <!--EndFooter-->
      </div>

    </div>
    <script src="assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
    <script src="assets/lib/perfect-scrollbar/js/perfect-scrollbar.min.js" type="text/javascript"></script>
    <script src="assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="assets/js/app.js" type="text/javascript"></script>
    <script src="assets/lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="assets/lib/jquery.nestable/jquery.nestable.js" type="text/javascript"></script>
    <script src="assets/lib/moment.js/min/moment.min.js" type="text/javascript"></script>
    <script src="assets/lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
    <script src="assets/lib/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="assets/lib/bootstrap-slider/bootstrap-slider.min.js" type="text/javascript"></script>
    <script src="assets/lib/bs-custom-file-input/bs-custom-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//-initialize the javascript
      	App.init();
      	App.formElements();
      });
    </script>
  </body>

</html>