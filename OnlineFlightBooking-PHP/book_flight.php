<?php include_once 'helpers/helper.php'; ?>
<?php subview('header.php'); 
require 'helpers/init_conn_db.php';                      
?> 	
<link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200&display=swap" rel="stylesheet">
<style>
table {
  background-color: white;
}
@font-face {
  font-family: 'product sans';
  src: url('assets/css/Product Sans Bold.ttf');
}
h1{
    font-family :'product sans' !important;
	color:#424242 ;
	font-size:40px !important;
	margin-top:20px;
	text-align:center;
}
body {
  background: #bdc3c7;  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #2c3e50, #bdc3c7);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #2c3e50, #bdc3c7); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}
th {
  font-size: 22px;
  /* font-family: 'Courier New', Courier, monospace; */
}
td {
  margin-top: 10px !important;
  font-size: 16px;
  font-weight: bold;
  /* color: #3931af; */
  color: #424242;
}
</style>
    <main>
<?php if(isset($_POST['search_but'])) { 
            $dep_date = $_POST['dep_date'];                        
            //$ret_date = $_POST['ret_date']; 
            $dep_city = $_POST['dep_city'] ?? 0;  
            $arr_city = $_POST['arr_city'] ?? 0;
            $type = $_POST['type'];
            $f_class = $_POST['f_class'];
            $passengers = $_POST['passengers'];
            $days = $_POST['days'];

            if($arr_city == 0 && $dep_city == 0) {
              // header('Location: index.php?error=selarr');
              echo '<script>
                      alert("Select Departure city and Arrival city");
                      window.history.back();
                    </script>';
              exit();              
            }
            elseif($dep_city == 0) {
              // header('Location: index.php?error=seldep');
              echo '<script>
                      alert("Select Departure city");
                      window.history.back();
                    </script>';
              exit(); 
            }
            elseif($arr_city == 0) {
              // header('Location: index.php?error=selarr');
              echo '<script>
                      alert("Select Arrival city");
                      window.history.back();
                    </script>';
              exit();              
            }
            
            ?>
          <div class="container-md mt-2">
            <h1 class="display-4 text-center text-light">FLIGHTS FROM: <br> <?php echo $dep_city; ?> to <?php echo $arr_city; ?> </h1>
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr class="text-center">
                  <th scope="col">Airline</th>
                  <th scope="col">Departure</th>
                  <th scope="col">Arrival</th>
                  <th scope="col">Status</th>
                  <th scope="col">Fare</th>
                  <th scope="col">Buy</th>
                </tr>
              </thead>
              <tbody>
                <?php

                if($dep_date != Null){

                  $sql = 'SELECT * FROM Flight WHERE source=? AND Destination =? AND DATE(departure)=? ORDER BY Price';
                  $stmt = mysqli_stmt_init($conn);
                  mysqli_stmt_prepare($stmt,$sql);
                  mysqli_stmt_bind_param($stmt,'sss',$dep_city,$arr_city,$dep_date);

                }else{

                  $sql = 'SELECT * FROM Flight WHERE source=? AND Destination =? ORDER BY Price';
                  $stmt = mysqli_stmt_init($conn);
                  mysqli_stmt_prepare($stmt,$sql);
                  mysqli_stmt_bind_param($stmt,'ss',$dep_city,$arr_city);
                  
                }

                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 0) {
                  echo "<script>
                          alert('No matching flights found from $dep_city to $arr_city');
                          window.history.back();
                        </script>";
                  exit();
                }

                while ($row = mysqli_fetch_assoc($result)) {
                  $price = (int)$row['Price']*(int)$passengers;
                  if($type === 'round') {
                    $price = $price*2;
                  }
                  if($f_class == 'B') {
                      $price += 0.5*$price;
                  }
                  if($row['status'] === '') {
                      $status = "Not yet Departed";
                      $alert = 'alert-primary';
                  } else if($row['status'] === 'dep') {
                      $status = "Departed";
                      $alert = 'alert-info';
                  } else if($row['status'] === 'issue') {
                      $status = "Delayed";
                      $alert = 'alert-danger';
                  } else if($row['status'] === 'arr') {
                      $status = "Arrived";
                      $alert = 'alert-success';
                  }                   
                  echo "
                  <tr class='text-center'>                  
                    <td>".$row['airline']."</td>
                    <td>".$row['departure']."</td>
                    <td>".$row['arrivale']."</td>
                    <td>
                      <div>
                          <div class='alert ".$alert." text-center mb-0 pt-1 pb-1' 
                              role='alert'>
                              ".$status."
                          </div>
                      </div>  
                    </td>                   
                    <td>$ ".$price."</td>
                    ";
                  if(isset($_SESSION['userId']) && $row['status'] === '') {   
                    echo " <td>
                    <form action='pass_form.php' method='post'>
                      <input name='flight_id' type='hidden' value=".$row['flight_id'].">
                      <input name='type' type='hidden' value=".$type.">
                      <input name='passengers' type='hidden' value=".$passengers.">
                      <input name='days' type='hidden' value=".$days.">
                      <input name='price' type='hidden' value=".$price.">
                      <input name='ret_date' type='hidden' value=".$dep_date.">
                      <input name='class' type='hidden' value=".$f_class.">
                      <button name='book_but' type='submit' 
                      class='btn btn-success mt-0'>
                      <div style=''>
                      <i class='fa fa-lg fa-check'></i>  
                      </div>
                    </button>
                    </form>
                    </td>                                                       
                    "; 
                  }
                  elseif ((isset($_SESSION['userId']) && $row['status'] === 'dep') || (isset($_SESSION['userId']) && $row['status'] === 'arr') ) {
					          echo "<td>Not Available</td>";
				          }
                  else {
                    echo "<td>Login to continue</td>";
                  }
                  echo '</tr> ';                 
                }
                ?>

              </tbody>
            </table>

          </div>
        <?php
        } 
        else {

          $dep_date = $_POST['dep_date'];                        
          //$ret_date = $_POST['ret_date'];  
          // $dep_city = $_POST['dep_city'];  
          // $arr_city = $_POST['arr_city'];     
          $type = $_POST['type'];
          $f_class = $_POST['f_class'];
          $passengers = $_POST['passengers'];
          $days = $_POST['days'];

        ?>
        <div class="container-md mt-2">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr class="text-center">
                  <th scope="col">Source</th>
                  <th scope="col">Destination</th>
                  <!-- <th scope="col">from</th>
                  <th scope="col">to</th> -->
                  <th scope="col">Airline</th>
                  <th scope="col">Departure</th>
                  <th scope="col">Arrival</th>
                  <th scope="col">Status</th>
                  <th scope="col">Fare</th>
                  <th scope="col">Buy</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $sql = 'SELECT * FROM Flight ORDER BY Price';
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 0) {

                  echo "<script>
                          alert('No flights in the database yet');
                          window.history.back();
                        </script>";
                  exit();
                }

                while ($row = mysqli_fetch_assoc($result)) {
                  $price = (int)$row['Price']*(int)$passengers;
                  if($type === 'round') {
                    $price = $price*2;
                  }
                  if($f_class == 'B') {
                      $price += 0.5*$price;
                  }
                  if($row['status'] === '') {
                      $status = "Not yet Departed";
                      $alert = 'alert-primary';
                  } else if($row['status'] === 'dep') {
                      $status = "Departed";
                      $alert = 'alert-info';
                  } else if($row['status'] === 'issue') {
                      $status = "Delayed";
                      $alert = 'alert-danger';
                  } else if($row['status'] === 'arr') {
                      $status = "Arrived";
                      $alert = 'alert-success';
                  }                   
                  echo "
                  <tr class='text-center'>  
                    <td>".$row['source']."</td>
                    <td>".$row['Destination']."</td>             
                    <td>".$row['airline']."</td>
                    <td>".$row['departure']."</td>
                    <td>".$row['arrivale']."</td>
                    <td>
                      <div>
                          <div class='alert ".$alert." text-center mb-0 pt-1 pb-1' 
                              role='alert'>
                              ".$status."
                          </div>
                      </div>  
                    </td>                   
                    <td>$ ".$price."</td>
                    ";
                  if(isset($_SESSION['userId']) && $row['status'] === '') {   
                    echo " <td>
                      <form action='pass_form.php' method='post'>
                        <input name='flight_id' type='hidden' value='" . $row['flight_id'] . "'>
                        <input name='type' type='hidden' value='" . $type . "'>
                        <input name='passengers' type='hidden' value='" . $passengers . "'>
                        <input name='days' type='hidden' value='" . $days . "'>
                        <input name='price' type='hidden' value='" . $price . "'>
                        <input name='ret_date' type='hidden' value='" . $row['departure'] . "'>
                        <input name='class' type='hidden' value='E'>
                        <button name='book_but' type='submit' class='btn btn-success mt-0'>
                            <div style=''>
                                <i class='fa fa-lg fa-check'></i>
                            </div>
                        </button>
                      </form>
                      </td>                                                       
                      "; 
                  }
                  elseif ((isset($_SESSION['userId']) && $row['status'] === 'dep') || (isset($_SESSION['userId']) && $row['status'] === 'arr')) {
					          echo "<td>Not Available</td>";
				          }
                  elseif (isset($_SESSION['userId']) && $row['status'] === 'issue') {
                    echo "<td>Please Wait</td>";
                  }
                  else {
                    echo "<td>Login to continue</td>";
                  }
                  echo '</tr> ';                 
                }
                ?>

              </tbody>
            </table>

          </div>
        <?php
        }
        ?> 

    </main>
    <?php subview('footer.php'); ?> 
    <!-- <footer style="
        position: absolute;
      bottom: 0;
      width: 100%;
      height: 2.5rem;  
    ">
	<em><h5 class="text-light text-center p-0 brand mt-2">
				<img src="assets/images/airtic.png" 
					height="40px" width="40px" alt="">				
			Online Flight Booking</h5></em>
	<p class="text-light text-center">&copy; <?php echo date('Y');?> - Developed By Abdalrhman Alhrery</p>
</footer> -->