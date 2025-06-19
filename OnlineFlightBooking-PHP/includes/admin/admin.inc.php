<?php
require '../../helpers/init_conn_db.php';
session_start();
if(isset($_SESSION['adminId'])) {
    if(isset($_POST['dep_but'])) {
        $flight_id = $_POST['flight_id'];
        $round_flight_id = $_POST['round_flight_id'] ?? $round_flight_id = 0 ;

        if ($round_flight_id > 0) {

            $sql = 'SELECT * FROM Round_flight WHERE flight_id=?';
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);              
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {

                $sql = "UPDATE Round_flight SET status='dep' WHERE round_flight_id=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql);
                mysqli_stmt_bind_param($stmt,'i',$row['round_flight_id']);         
                mysqli_stmt_execute($stmt);
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: ../../admin/index.php');
            exit();
        }else {
            $sql = "UPDATE Flight SET status='dep' WHERE flight_id=?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: ../../admin/index.php');
            exit();
        }

        
    }else if(isset($_POST['issue_but'])) {
        $flight_id = $_POST['flight_id'];
        $round_flight_id = $_POST['round_flight_id'] ?? $round_flight_id = 0 ;
        $issue = $_POST['issue'] ?? $issue = 0;
        $delay_time = gmdate('h:i:s',(int)$issue*60);

        if ($round_flight_id > 0) {
            $sql = 'SELECT * FROM Round_flight WHERE flight_id=?';
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);              
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {

                if ($issue > 0) {
                    $date_time_dep = $row['departure'];
                    $date_dep = substr($date_time_dep,0,10);
                    $time_dep = substr($date_time_dep,10,6) ;    
                    $date_time_arr = $row['arrivale'];
                    $date_arr = substr($date_time_arr,0,10);
                    $time_arr = substr($date_time_arr,10,6) ; 
                    $time_dep = new DateTime($date_time_dep);
                    $time_dep->add(new DateInterval('PT' . $issue . 'M'));            
                    $stamp_dep = $time_dep->format('Y-m-d H:i:s');         
                    $time_arr = new DateTime($date_time_arr);
                    $time_arr->add(new DateInterval('PT' . $issue . 'M'));            
                    $stamp_arr = $time_arr->format('Y-m-d H:i:s');                               
                    $sql = "UPDATE Round_flight SET status='issue',departure=?,arrivale=?
                        WHERE round_flight_id=?";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt,$sql);
                    mysqli_stmt_bind_param($stmt,'ssi',$stamp_dep,$stamp_arr,$row['round_flight_id']);         
                    mysqli_stmt_execute($stmt);
                }else {
                    $sql = "UPDATE Round_flight SET status='issue' WHERE round_flight_id=?";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt,$sql);
                    mysqli_stmt_bind_param($stmt,'i',$row['round_flight_id']);         
                    mysqli_stmt_execute($stmt);
                }
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);;
            header('Location: ../../admin/index.php');
            exit();

        }else {
            $sql = 'SELECT * FROM Flight WHERE flight_id=?';
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);              
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result)) {

                if ($issue > 0) {
                    $date_time_dep = $row['departure'];
                    $date_dep = substr($date_time_dep,0,10);
                    $time_dep = substr($date_time_dep,10,6) ;    
                    $date_time_arr = $row['arrivale'];
                    $date_arr = substr($date_time_arr,0,10);
                    $time_arr = substr($date_time_arr,10,6) ; 
                    $time_dep = new DateTime($date_time_dep);
                    $time_dep->add(new DateInterval('PT' . $issue . 'M'));            
                    $stamp_dep = $time_dep->format('Y-m-d H:i:s');         
                    $time_arr = new DateTime($date_time_arr);
                    $time_arr->add(new DateInterval('PT' . $issue . 'M'));            
                    $stamp_arr = $time_arr->format('Y-m-d H:i:s');                               
                    $sql = "UPDATE Flight SET status='issue',issue=?,departure=?,arrivale=?
                        WHERE flight_id=?";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt,$sql);
                    mysqli_stmt_bind_param($stmt,'sssi',$issue,$stamp_dep,$stamp_arr,$flight_id);         
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);;
                    header('Location: ../../admin/index.php');
                    exit();
                }else {
                    $sql = "UPDATE Flight SET status='issue' WHERE flight_id=?";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt,$sql);
                    mysqli_stmt_bind_param($stmt,'i',$flight_id);         
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    header('Location: ../../admin/index.php');
                    exit();
                }
                            
            }
        }


                
    } else if(isset($_POST['issue_soved_but'])) {
      $flight_id = $_POST['flight_id'];
      $round_flight_id = $_POST['round_flight_id'] ?? $round_flight_id = 0 ;

        if ($round_flight_id > 0) {

            $sql = 'SELECT * FROM Round_flight WHERE flight_id=?';
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);              
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {

                $sql = "UPDATE Round_flight SET status='' WHERE round_flight_id=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql);
                mysqli_stmt_bind_param($stmt,'i',$row['round_flight_id']);         
                mysqli_stmt_execute($stmt);
            }

            
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: ../../admin/index.php');
            exit();
        }else {
            $sql = "UPDATE Flight SET status='',issue='solved' WHERE flight_id=?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: ../../admin/index.php');
            exit();
        }

      


    }else if(isset($_POST['arr_but'])) {

      $flight_id = $_POST['flight_id'];
      $round_flight_id = $_POST['round_flight_id'] ?? $round_flight_id = 0 ;

        if ($round_flight_id > 0) {

            $sql = 'SELECT * FROM Round_flight WHERE flight_id=?';
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);              
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($result)) {

                $sql = "UPDATE Round_flight SET status='arr'WHERE round_flight_id=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql);
                mysqli_stmt_bind_param($stmt,'i',$row['round_flight_id']);         
                mysqli_stmt_execute($stmt);

            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: ../../admin/index.php');
            exit();

        }else {
            $sql = "UPDATE Flight SET status='arr'WHERE flight_id=?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt,$sql);
            mysqli_stmt_bind_param($stmt,'i',$flight_id);         
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: ../../admin/index.php');
            exit();
        }

      




    }else if(isset($_POST['rev_but'])) {

        $flight_id = $_POST['flight_id'];
        $round_flight_id = $_POST['round_flight_id'] ?? $round_flight_id = 0 ;
  
          if ($round_flight_id > 0) {
  
              $sql = 'SELECT * FROM Round_flight WHERE flight_id=?';
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt,$sql);              
              mysqli_stmt_bind_param($stmt,'i',$flight_id);         
              mysqli_stmt_execute($stmt);
              $result = mysqli_stmt_get_result($stmt);
              while($row = mysqli_fetch_assoc($result)) {
  
                  $sql = "UPDATE Round_flight SET status=''WHERE round_flight_id=?";
                  $stmt = mysqli_stmt_init($conn);
                  mysqli_stmt_prepare($stmt,$sql);
                  mysqli_stmt_bind_param($stmt,'i',$row['round_flight_id']);         
                  mysqli_stmt_execute($stmt);
  
              }
  
              mysqli_stmt_close($stmt);
              mysqli_close($conn);
              header('Location: ../../admin/index.php');
              exit();
  
          }else {
              $sql = "UPDATE Flight SET status=''WHERE flight_id=?";
              $stmt = mysqli_stmt_init($conn);
              mysqli_stmt_prepare($stmt,$sql);
              mysqli_stmt_bind_param($stmt,'i',$flight_id);         
              mysqli_stmt_execute($stmt);
              mysqli_stmt_close($stmt);
              mysqli_close($conn);
              header('Location: ../../admin/index.php');
              exit();
          }
  
        
  
  
  
  
      } else {
        header('Location: ../../admin/index.php');
        exit();
    }
}else {
    header('Location: ../../admin/index.php');
    exit();
}
