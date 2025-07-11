<?php include_once 'header.php'; ?> 
<?php include_once 'footer.php';
require '../helpers/init_conn_db.php';?>
<style> 
table {
  background-color: white;
}
h1 {
  margin-top: 20px;
  margin-bottom: 20px;
  font-family: 'product sans';  
  font-size: 50px !important; 
  font-weight: lighter;
}
body {
  background-color: #efefef;
}
th {
  font-size: 22px;
  text-align : center !important;
  /* font-weight: lighter; */
  /* font-family: 'Courier New', Courier, monospace; */
}
td {
  margin-top: 10px !important;
  font-size: 16px;
  font-weight: bold;
  /* font-family: 'Assistant', sans-serif !important; */
  font-family: arial !important;
}
</style>
    <main>
        <?php if(isset($_SESSION['adminId'])) { ?>
          <div class="container-md mt-2">
            <h1 class="display-4 text-center text-secondary"
              >Passenger List</h1>
            <table class="table table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th>#</th> 
                  <th scope="col">First Name</th>
                  <th scope="col">Middle Name</th>
                  <th scope="col">Last Name</th>
                  <th scope="col">Contact</th>
                  <th scope="col">D.O.B</th>
                  <th scope="col">Paid By</th>
                  <th scope="col">Amount</th>

                </tr>
              </thead>
              <tbody>
                <?php
                $cnt=1;
                $flight_id = $_GET['flight_id'];
                // $stmt_t = mysqli_stmt_init($conn);
                // $sql_t = 'SELECT * FROM Ticket WHERE flight_id=?';
                // $stmt_t = mysqli_stmt_init($conn);
                // if(!mysqli_stmt_prepare($stmt_t,$sql_t)) {
                //     header('Location: ticket.php?error=sqlerror');
                //     exit();            
                // } else {
                    // mysqli_stmt_bind_param($stmt_t,'i',$flight_id);            
                    // mysqli_stmt_execute($stmt_t);
                    // $result_t = mysqli_stmt_get_result($stmt_t);
                    // while($row_t = mysqli_fetch_assoc($result_t)) {                  
                      $sql = 'SELECT * FROM Passenger_profile WHERE flight_id=?';  
                      $stmt = mysqli_stmt_init($conn);
                      mysqli_stmt_prepare($stmt,$sql);
                      if(!mysqli_stmt_prepare($stmt,$sql)) {
                          header('Location: ticket.php?error=sqlerror');
                          exit();  
                      }else {
                      mysqli_stmt_bind_param($stmt,'i',$flight_id);          
                      mysqli_stmt_execute($stmt);
                      $result = mysqli_stmt_get_result($stmt);                
                      // if ($row = mysqli_fetch_assoc($result)) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          $sql_t = 'SELECT * FROM Ticket WHERE flight_id=? AND passenger_id=?';  
                          $stmt_t = mysqli_stmt_init($conn);
                          mysqli_stmt_prepare($stmt_t,$sql_t);  
                          mysqli_stmt_bind_param($stmt_t,'ii',$flight_id,$row['passenger_id']);          
                          mysqli_stmt_execute($stmt_t);
                          $result_t = mysqli_stmt_get_result($stmt_t);                
                          if ($row_t = mysqli_fetch_assoc($result_t)) {
                            $sql_u = 'SELECT * FROM Users WHERE user_id=?';  
                            $stmt_u = mysqli_stmt_init($conn);
                            mysqli_stmt_prepare($stmt_u,$sql_u);  
                            mysqli_stmt_bind_param($stmt_u,'i',$row['user_id']);          
                            mysqli_stmt_execute($stmt_u);
                            $result_u = mysqli_stmt_get_result($stmt_u);                
                            if ($row_u = mysqli_fetch_assoc($result_u)) {
                              echo "                  
                              <tr class='text-center'>
                                <td>".$cnt."</td>
                                <td>".$row['f_name']."</td>
                                <td>".$row['m_name']."</td>
                                <td>".$row['l_name']."</td>
                                <td>".$row['mobile']."</td>
                                <td>".$row['dob']."</td>
                                <td scope='row'>".$row_u['username']."</td>
                                <td>$ ".$row_t['cost']."</td>
                              </tr>
                              "; 
                            }                       
                          } 
                          $cnt++;                   
                      }
                      // $cnt++;
                    }
                    //  }
                      // }
                ?>

              </tbody>
            </table>

          </div>
        <?php } ?>

    </main> 
