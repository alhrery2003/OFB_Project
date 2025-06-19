<?php include_once 'helpers/helper.php'; ?>
<?php subview('header.php'); ?>
<style>
body {
  background: #bdc3c7;  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #2c3e50, #bdc3c7);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #2c3e50, #bdc3c7); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}
@font-face {
  font-family: 'product sans';
  src: url('assets/css/Product Sans Bold.ttf');
}
h2.brand {
    /* font-style: italic; */
    font-size: 27px !important;
}
.vl {
  border-left: 6px solid #424242;
  height: 400px;
}
p.head {
    text-transform: uppercase;
    font-family: arial;
    font-size: 17px;
    margin-bottom: 10px ;
    color: grey;  
}
p.txt {
    text-transform: uppercase;
    font-family: arial;
    font-size: 25px;
    font-weight: bolder;
}
.out {
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);  
    background-color: white;
    padding-left: 25px;
    padding-right: 0px;
    padding-top: 20px;
}
h2 {
    font-weight: lighter !important;
    font-size: 35px !important;
    margin-bottom: 20px;  
    font-family :'product sans' !important;
    font-weight: bolder;
}
.text-light2 {
    color: #d9d9d9;
}
h3 {
    /* font-weight: lighter !important; */
    font-size: 21px !important;
    margin-bottom: 20px;  
    font-family: Tahoma, sans-serif;
    font-weight: lighter;
}
h1 {
    font-weight: lighter !important;
    font-size: 45px !important;
    margin-bottom: 20px;  
    font-family :'product sans' !important;
    font-weight: bolder;
  }
</style>
<main>
  <?php if(isset($_SESSION['userId'])) {   
    require 'helpers/init_conn_db.php';   
    
    if(isset($_POST['cancel_but'])) {
        $ticket_id = $_POST['ticket_id'];
        $stmt = mysqli_stmt_init($conn);
        $sql = 'SELECT * FROM Ticket WHERE ticket_id=?';
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header('Location: ticket.php?error=sqlerror');
            exit();            
        } else {
            mysqli_stmt_bind_param($stmt,'i',$ticket_id);            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {  

                if ($row['type'] == 'round') {

                    $sql_t = 'DELETE FROM Ticket WHERE ticket_id=?';
                    $stmt_t = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt_t,$sql_t)) {
                        header('Location: ticket.php?error=sqlerror');
                        exit();            
                    } else {
                        mysqli_stmt_bind_param($stmt_t,'i',$row['ticket_id']);            
                        mysqli_stmt_execute($stmt_t);
                    }


                    $sql_test_round = 'SELECT * FROM Round_flight WHERE passenger_id=?';
                    $stmt_test_round = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt_test_round,$sql_test_round)) {
                        header('Location: ticket.php?error=sqlerror');
                        exit();            
                    } else {
                        mysqli_stmt_bind_param($stmt_test_round,'i',$row['passenger_id']);            
                        mysqli_stmt_execute($stmt_test_round);
                        $result_test_round = mysqli_stmt_get_result($stmt_test_round);
                        if ($row_test_round = mysqli_fetch_assoc($result_test_round)) {
                            $sql_round = 'DELETE FROM Round_flight WHERE round_flight_id=?';
                            $stmt_round = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt_round,$sql_round)) {
                                header('Location: ticket.php?error=sqlerror');
                                exit();            
                            } else {
                                mysqli_stmt_bind_param($stmt_round,'i',$row_test_round['round_flight_id']);            
                                mysqli_stmt_execute($stmt_round);
                            }
                        }
                    }


                    // $sql_pas = 'DELETE FROM Passenger_profile WHERE passenger_id=?';
                    // $stmt_pas = mysqli_stmt_init($conn);
                    // if(!mysqli_stmt_prepare($stmt_pas,$sql_pas)) {
                    //     header('Location: ticket.php?error=sqlerror');
                    //     exit();            
                    // } else {
                    //     mysqli_stmt_bind_param($stmt_pas,'i',$row['passenger_id']);            
                    //     mysqli_stmt_execute($stmt_pas);
                    // }

                }else {
                    $stmt_test = mysqli_stmt_init($conn);
                    $sql_test = 'SELECT * FROM Ticket WHERE passenger_id=? AND flight_id=? AND user_id=?';
                    if(!mysqli_stmt_prepare($stmt_test,$sql_test)) {
                        header('Location: ticket.php?error=sqlerror');
                        exit();            
                    } else {
                        mysqli_stmt_bind_param($stmt_test,'iii',$row['passenger_id'],$row['flight_id'],$_SESSION['userId']);            
                        mysqli_stmt_execute($stmt_test);

                        $result_test = mysqli_stmt_get_result($stmt_test);
                        $num_rows = mysqli_num_rows($result_test);

                        if ($num_rows>1) {
                            while($row_test = mysqli_fetch_assoc($result_test)) {

                                $sql_t = 'DELETE FROM Ticket WHERE ticket_id=?';
                                $stmt_t = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmt_t,$sql_t)) {
                                    header('Location: ticket.php?error=sqlerror');
                                    exit();            
                                } else {
                                    mysqli_stmt_bind_param($stmt_t,'i',$row_test['ticket_id']);            
                                    mysqli_stmt_execute($stmt_t);
                                }

                            }
                        }else {
                            $sql_t = 'DELETE FROM Ticket WHERE ticket_id=?';
                            $stmt_t = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt_t,$sql_t)) {
                                header('Location: ticket.php?error=sqlerror');
                                exit();            
                            } else {
                                mysqli_stmt_bind_param($stmt_t,'i',$row['ticket_id']);            
                                mysqli_stmt_execute($stmt_t);
                            }
                        }                 
                    }

                    $sql_test_round = 'SELECT * FROM Round_flight WHERE passenger_id=?';
                    $stmt_test_round = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt_test_round,$sql_test_round)) {
                        header('Location: ticket.php?error=sqlerror');
                        exit();            
                    } else {
                        mysqli_stmt_bind_param($stmt_test_round,'i',$row['passenger_id']);            
                        mysqli_stmt_execute($stmt_test_round);
                        $result_test_round = mysqli_stmt_get_result($stmt_test_round);
                        if ($row_test_round = mysqli_fetch_assoc($result_test_round)) {
                            $sql_round = 'DELETE FROM Round_flight WHERE round_flight_id=?';
                            $stmt_round = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt_round,$sql_round)) {
                                header('Location: ticket.php?error=sqlerror');
                                exit();            
                            } else {
                                mysqli_stmt_bind_param($stmt_round,'i',$row_test_round['round_flight_id']);            
                                mysqli_stmt_execute($stmt_round);
                            }
                        }
                    }
                    


                    $sql_pas = 'DELETE FROM Passenger_profile WHERE passenger_id=?';
                    $stmt_pas = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt_pas,$sql_pas)) {
                        header('Location: ticket.php?error=sqlerror');
                        exit();            
                    } else {
                        mysqli_stmt_bind_param($stmt_pas,'i',$row['passenger_id']);            
                        mysqli_stmt_execute($stmt_pas);
                    } 
                }

                             
            }
        }  
        
        
    }elseif (isset($_POST['delete_but'])) {
        $ticket_id = $_POST['ticket_id'];
        $stmt = mysqli_stmt_init($conn);
        $sql = 'SELECT * FROM Ticket WHERE ticket_id=?';
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            header('Location: ticket.php?error=sqlerror');
            exit();            
        } else {
            mysqli_stmt_bind_param($stmt,'i',$ticket_id);            
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $sql_t = 'DELETE FROM Ticket WHERE ticket_id=?';
                $stmt_t = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt_t,$sql_t)) {
                    header('Location: ticket.php?error=sqlerror');
                    exit();            
                } else {
                    mysqli_stmt_bind_param($stmt_t,'i',$row['ticket_id']);            
                    mysqli_stmt_execute($stmt_t);
                }

            }

        }

    }
    
    ?>     
    <div class="container mb-5"> 
    <h1 class="text-center text-light mt-4 mb-4">E-TICKETS</h1>

    <?php 
    $flag=true;
    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT * FROM Ticket WHERE user_id=?';
    //   $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)) {
        header('Location: ticket.php?error=sqlerror');
        exit();            
    } else {
        mysqli_stmt_bind_param($stmt,'i',$_SESSION['userId']);            
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {

            if ($flag) {

                $stmt_test = mysqli_stmt_init($conn);
                $sql_test = 'SELECT * FROM Ticket WHERE user_id=? AND flight_id=? AND passenger_id=?';
                if(!mysqli_stmt_prepare($stmt_test,$sql_test)) {
                    header('Location: ticket.php?error=sqlerror');
                    exit();            
                } else {
                    mysqli_stmt_bind_param($stmt_test,'iii',$_SESSION['userId'],$row['flight_id'],$row['passenger_id']);            
                    mysqli_stmt_execute($stmt_test);
                    $result_test = mysqli_stmt_get_result($stmt_test);
                    $num_rows = mysqli_num_rows($result_test);

                    if ($num_rows>1) {
                        $flag=false;
                        $i=1;
                        while($row_test = mysqli_fetch_assoc($result_test)) { 

                            $stmt_test_dub = mysqli_stmt_init($conn);
                            $sql_test_dub = 'SELECT * FROM Round_flight WHERE user_id=? AND flight_id=? AND passenger_id=?';
                            if(!mysqli_stmt_prepare($stmt_test_dub,$sql_test_dub)) {
                                header('Location: ticket.php?error=sqlerror');
                                exit();            
                            } else {
                                mysqli_stmt_bind_param($stmt_test_dub,'iii',$_SESSION['userId'],$row_test['flight_id'],$row_test['passenger_id']);            
                                mysqli_stmt_execute($stmt_test_dub);
                                $result_test_dub = mysqli_stmt_get_result($stmt_test_dub);
                                if ($row_test_dub = mysqli_fetch_assoc($result_test_dub)) {



                                    $sql_p = 'SELECT * FROM Passenger_profile WHERE passenger_id=?';
                                    $stmt_p = mysqli_stmt_init($conn);
                                    if(!mysqli_stmt_prepare($stmt_p,$sql_p)) {
                                        header('Location: ticket.php?error=sqlerror');
                                        exit();            
                                    } else {
                                        mysqli_stmt_bind_param($stmt_p,'i',$row_test['passenger_id']);            
                                        mysqli_stmt_execute($stmt_p);
                                        $result_p = mysqli_stmt_get_result($stmt_p);
                                        if($row_p = mysqli_fetch_assoc($result_p)) {
                                            $sql_f = 'SELECT * FROM Flight WHERE flight_id=?';
                                            $stmt_f = mysqli_stmt_init($conn);
                                            if(!mysqli_stmt_prepare($stmt_f,$sql_f)) {
                                                header('Location: ticket.php?error=sqlerror');
                                                exit();            
                                            } else {
                                                mysqli_stmt_bind_param($stmt_f,'i',$row_test['flight_id']);            
                                                mysqli_stmt_execute($stmt_f);
                                                $result_f = mysqli_stmt_get_result($stmt_f);
                                                if($row_f = mysqli_fetch_assoc($result_f)) {
                                                    if ($i%2!=0) {
                                                        $date_time_dep = $row_f['departure'];
                                                        $date_dep = substr($date_time_dep,0,10);
                                                        $time_dep = substr($date_time_dep,10,6) ;    
                                                        $date_time_arr = $row_f['arrivale'];
                                                        $date_arr = substr($date_time_arr,0,10);
                                                        $time_arr = substr($date_time_arr,10,6) ; 
                                                    }else {
                                                        $date_time_dep = $row_test_dub['departure'];
                                                        $date_dep = substr($date_time_dep,0,10);
                                                        $time_dep = substr($date_time_dep,10,6) ;    
                                                        $date_time_arr = $row_test_dub['arrivale'];
                                                        $date_arr = substr($date_time_arr,0,10);
                                                        $time_arr = substr($date_time_arr,10,6) ;
                                                    }
                                                    
                                                    if($row['class'] === 'E') {
                                                        $class_txt = 'ECONOMY';
                                                    } else if($row['class'] === 'B') {
                                                        $class_txt = 'BUSINESS';
                                                    }
                                                    
                                                    $unique_id = $row_test['ticket_id'];


                                                    if ($i%2!=0) {
                                                        echo '
                                                        <div class="row mb-5">                                                         
                                                        <div class="col-8 out">
                                                            <div class="row ">                                                     
                                                                <div class="col">
                                                                    <h2 class="text-secondary mb-0 brand">
                                                                        Online Flight Booking</h2> 
                                                                </div>
                                                                <div class="col">
                                                                    <h2 class="mb-0">'.$class_txt.' CLASS</h2>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row mb-3">  
                                                                <div class="col-4">
                                                                    <p class="head">Airline</p>
                                                                    <p class="txt">'.$row_f['airline'].'</p>
                                                                </div>            
                                                                <div class="col-4">
                                                                    <p class="head">from</p>
                                                                    <p class="txt">'.$row_f['source'].'</p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="head">to</p>
                                                                    <p class="txt">'.$row_f['Destination'].'</p>                
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-8">
                                                                    <p class="head">Passenger</p>
                                                                    <p class=" h5 text-uppercase">
                                                                    '.$row_p['f_name'].' '.$row_p['m_name'].' '.$row_p['l_name'].'
                                                                    </p>                              
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="head">board time</p>
                                                                    <p class="txt">12:45</p>
                                                                </div> 
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <p class="head">departure</p>
                                                                    <p class="txt mb-1">'.$date_dep.'</p>
                                                                    <p class="h1 font-weight-bold mb-3">'.$time_dep.'</p>  
                                                                </div>            
                                                                <div class="col-3">
                                                                    <p class="head">arrival</p>
                                                                    <p class="txt mb-1">'.$date_arr.'</p>
                                                                    <p class="h1 font-weight-bold mb-3">'.$time_arr.'</p>  
                                                                </div>
                                                                <div class="col-3">
                                                                    <p class="head">gate</p>
                                                                    <p class="txt">A22</p>
                                                                </div>            
                                                                <div class="col-3">
                                                                    <p class="head">seat</p>
                                                                    <p class="txt">'.$row_test['seat_no'].'</p>
                                                                </div>                
                                                            </div>                    
                                                        </div>
                                                        <div class="col-3 pl-0" style="background-color:#376b8d !important;
                                                            padding:20px; border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                                                            <div class="row">  
                                                                <div class="col">                                    
                                                                <h2 class="text-light text-center brand">
                                                                    Online Flight Booking</h2> 
                                                                </div>                                      
                                                            </div>                             
                                                            <div class="row justify-content-center">
                                                                <div class="col-12">                                    
                                                                    <img src="assets/images/airtic.png" class="mx-auto d-block"
                                                                    height="200px" width="200px" alt="">
                                                                </div>                                
                                                            </div>
                                                            <div class="row">
                                                                <h3 class="text-light2 text-center mt-2 mb-0">
                                                                &nbsp Thank you for choosing us. </br> </br>
                                                                    Please be at the gate at boarding time</h3>   
                                                            </div>                            
                                                        </div>   
                                                        
                                                        <div class="col-1">
                                                            <div class="dropdown">
                                                                <button class="btn btn-danger dropdown-toggle" type="button" 
                                                                    id="dropdownMenuButton" data-toggle="dropdown" 
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    
                                                                    <i class="fa fa-ellipsis-v"></i> </td>
                                                                </button>  
                                                                <div class="dropdown-menu">
                                                                    <form class="px-4 py-3" action="e_ticket.php" target="_blank"
                                                                        method="post">
                                                                        <input type="hidden" name="ticket_id" 
                                                                            value="'.$unique_id.'">
                                                                        <button class="btn w-100  btn-primary btn"
                                                                            name="print_but">
                                                                            <i class="fa fa-print"></i> &nbsp; Print Ticket</button>
                                                                    </form>
                                                                    <!-- Cancel Ticket Form -->
                                                                    <form class="px-4 py-3" id="cancelForm-' . $unique_id . '" action="ticket.php" method="post">
                                                                        <input type="hidden" name="ticket_id" value="' . $unique_id . '">
                                                                        <button type="button" class="btn btn-danger btn" name="cancel_but"
                                                                        onclick="
                                                                            var userConfirmed = confirm(\'Are you sure you want to Cancel the ticket and Flight?                        **NOTE THAT THIS WILL ALSO CANCEL THE ROUND FLIGHT**\');
                                                                            if (userConfirmed) {
                                                                                var status = \'' . $row_f['status'] . '\';
                                                                                if (status === \'dep\') {
                                                                                    alert(\'Cannot cancel ticket, Flight already departed!\');
                                                                                } else if (status === \'arr\') {
                                                                                    alert(\'Cannot cancel ticket, Flight already arrived!\');
                                                                                } else if (status === \'issue\') {
                                                                                    alert(\'Flight has an issue, we are working on it.\');
                                                                                } else {
                                                                                    var form = document.getElementById(\'cancelForm-' . $unique_id . '\');
                                                                                    var input = document.createElement(\'input\');
                                                                                    input.type = \'hidden\';
                                                                                    input.name = \'cancel_but\';
                                                                                    form.appendChild(input);
                                                                                    form.submit();
                                                                                }
                                                                            }
                                                                        ">
                                                                            <i class="fa fa-trash"></i> &nbsp; Cancel Ticket
                                                                        </button>
                                                                    </form>
                                                                    <!-- Delete Ticket Form -->
                                                                    <form class="px-4 py-3" id="deleteForm-'.$unique_id.'" action="ticket.php" method="post">
                                                                        <input type="hidden" name="ticket_id" value="'.$unique_id.'">
                                                                        <button type="button" class="btn btn-danger btn" name="delete_but" 
                                                                            onclick="
                                                                                var userConfirmed = confirm(\'Are you sure you want to Delete the ticket?\');
                                                                                if (userConfirmed) {
                                                                                    var status = \'' . $row_f['status'] . '\';
                                                                                    if (status === \'issue\') {
                                                                                        alert(\'Flight has an issue, we are working on it.\');
                                                                                    } else {
                                                                                    var form = document.getElementById(\'deleteForm-' . $unique_id . '\');
                                                                                    var input = document.createElement(\'input\');
                                                                                    input.type = \'hidden\';
                                                                                    input.name = \'delete_but\';
                                                                                    form.appendChild(input);
                                                                                    form.submit();
                                                                                    }
                                                                                }
                                                                            ">
                                                                            <i class="fa fa-trash"></i> &nbsp; Delete Ticket
                                                                        </button>
                                                                    </form>                                    
                                                                </div>
                                                            </div>              
                                                        </div>                          
                                                        </div>                                              
                                                        ' ;




                                                    }else {
                                                        echo '
                                                        <div class="row mb-5">                                                         
                                                        <div class="col-8 out">
                                                            <div class="row ">                                                     
                                                                <div class="col">
                                                                    <h2 class="text-secondary mb-0 brand">
                                                                        Online Flight Booking</h2> 
                                                                </div>
                                                                <div class="col">
                                                                    <h2 class="mb-0">'.$class_txt.' CLASS</h2>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row mb-3">  
                                                                <div class="col-4">
                                                                    <p class="head">Airline</p>
                                                                    <p class="txt">'.$row_f['airline'].'</p>
                                                                </div>            
                                                                <div class="col-4">
                                                                    <p class="head">from</p>
                                                                    <p class="txt">'.$row_test_dub['source'].'</p>
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="head">to</p>
                                                                    <p class="txt">'.$row_test_dub['Destination'].'</p>                
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-8">
                                                                    <p class="head">Passenger</p>
                                                                    <p class=" h5 text-uppercase">
                                                                    '.$row_p['f_name'].' '.$row_p['m_name'].' '.$row_p['l_name'].'
                                                                    </p>                              
                                                                </div>
                                                                <div class="col-4">
                                                                    <p class="head">board time</p>
                                                                    <p class="txt">12:45</p>
                                                                </div> 
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <p class="head">departure</p>
                                                                    <p class="txt mb-1">'.$date_dep.'</p>
                                                                    <p class="h1 font-weight-bold mb-3">'.$time_dep.'</p>  
                                                                </div>            
                                                                <div class="col-3">
                                                                    <p class="head">arrival</p>
                                                                    <p class="txt mb-1">'.$date_arr.'</p>
                                                                    <p class="h1 font-weight-bold mb-3">'.$time_arr.'</p>  
                                                                </div>
                                                                <div class="col-3">
                                                                    <p class="head">gate</p>
                                                                    <p class="txt">A22</p>
                                                                </div>            
                                                                <div class="col-3">
                                                                    <p class="head">seat</p>
                                                                    <p class="txt">'.$row_test['seat_no'].'</p>
                                                                </div>                
                                                            </div>                    
                                                        </div>
                                                        <div class="col-3 pl-0" style="background-color:#376b8d !important;
                                                            padding:20px; border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                                                            <div class="row">  
                                                                <div class="col">                                    
                                                                <h2 class="text-light text-center brand">
                                                                    Online Flight Booking</h2> 
                                                                </div>                                      
                                                            </div>                             
                                                            <div class="row justify-content-center">
                                                                <div class="col-12">                                    
                                                                    <img src="assets/images/airtic.png" class="mx-auto d-block"
                                                                    height="200px" width="200px" alt="">
                                                                </div>                                
                                                            </div>
                                                            <div class="row">
                                                                <h3 class="text-light2 text-center mt-2 mb-0">
                                                                &nbsp Thank you for choosing us. </br> </br>
                                                                    Please be at the gate at boarding time</h3>   
                                                            </div>                            
                                                        </div>   
                                                        
                                                        <div class="col-1">
                                                            <div class="dropdown">
                                                                <button class="btn btn-danger dropdown-toggle" type="button" 
                                                                    id="dropdownMenuButton" data-toggle="dropdown" 
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    
                                                                    <i class="fa fa-ellipsis-v"></i> </td>
                                                                </button>  
                                                                <div class="dropdown-menu">
                                                                    <form class="px-4 py-3" action="e_ticket.php" target="_blank"
                                                                        method="post">
                                                                        <input type="hidden" name="ticket_id" 
                                                                            value="'.$unique_id.'">
                                                                        <button class="btn w-100  btn-primary btn"
                                                                            name="print_but">
                                                                            <i class="fa fa-print"></i> &nbsp; Print Ticket</button>
                                                                    </form>
                                                                    <!-- Cancel Ticket Form -->
                                                                    <form class="px-4 py-3" id="cancelForm-' . $unique_id . '" action="ticket.php" method="post">
                                                                        <input type="hidden" name="ticket_id" value="' . $unique_id . '">
                                                                        <button type="button" class="btn btn-danger btn" name="cancel_but"
                                                                        onclick="
                                                                            var userConfirmed = confirm(\'Are you sure you want to Cancel Round ticket and Round Flight?\');
                                                                            if (userConfirmed) {
                                                                                var status = \'' . $row_test_dub['status'] . '\';
                                                                                if (status === \'dep\') {
                                                                                    alert(\'Cannot cancel ticket,Round Flight already departed!\');
                                                                                } else if (status === \'arr\') {
                                                                                    alert(\'Cannot cancel ticket,Round Flight already arrived!\');
                                                                                } else if (status === \'issue\') {
                                                                                    alert(\'Round Flight has an issue, we are working on it.\');
                                                                                } else {
                                                                                    var form = document.getElementById(\'cancelForm-' . $unique_id . '\');
                                                                                    var input = document.createElement(\'input\');
                                                                                    input.type = \'hidden\';
                                                                                    input.name = \'cancel_but\';
                                                                                    form.appendChild(input);
                                                                                    form.submit();
                                                                                }
                                                                            }
                                                                        ">
                                                                            <i class="fa fa-trash"></i> &nbsp; Cancel Ticket
                                                                        </button>
                                                                    </form>
                                                                    <!-- Delete Ticket Form -->
                                                                    <form class="px-4 py-3" id="deleteForm-'.$unique_id.'" action="ticket.php" method="post">
                                                                        <input type="hidden" name="ticket_id" value="'.$unique_id.'">
                                                                        <button type="button" class="btn btn-danger btn" name="delete_but" 
                                                                            onclick="
                                                                                var userConfirmed = confirm(\'Are you sure you want to Delete Round ticket?\');
                                                                                if (userConfirmed) {
                                                                                    var status = \'' . $row_test_dub['status'] . '\';
                                                                                    if (status === \'issue\') {
                                                                                        alert(\'Round Flight has an issue, we are working on it.\');
                                                                                    } else {
                                                                                    var form = document.getElementById(\'deleteForm-' . $unique_id . '\');
                                                                                    var input = document.createElement(\'input\');
                                                                                    input.type = \'hidden\';
                                                                                    input.name = \'delete_but\';
                                                                                    form.appendChild(input);
                                                                                    form.submit();
                                                                                    }
                                                                                }
                                                                            ">
                                                                            <i class="fa fa-trash"></i> &nbsp; Delete Ticket
                                                                        </button>
                                                                    </form>                                   
                                                                </div>
                                                            </div>              
                                                        </div>                          
                                                        </div>                                               
                                                        ' ;
                                                    }
                                                }
                                            }                  
                                        }
                                    }
                                }
                            }
                            $i++;
                        }
                    }else {
                        



                        $sql_p = 'SELECT * FROM Passenger_profile WHERE passenger_id=?';
                        $stmt_p = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt_p,$sql_p)) {
                            header('Location: ticket.php?error=sqlerror');
                            exit();            
                        } else {
                            mysqli_stmt_bind_param($stmt_p,'i',$row['passenger_id']);            
                            mysqli_stmt_execute($stmt_p);
                            $result_p = mysqli_stmt_get_result($stmt_p);
                            if($row_p = mysqli_fetch_assoc($result_p)) {
                                $sql_f = 'SELECT * FROM Flight WHERE flight_id=?';
                                $stmt_f = mysqli_stmt_init($conn);
                                if(!mysqli_stmt_prepare($stmt_f,$sql_f)) {
                                    header('Location: ticket.php?error=sqlerror');
                                    exit();            
                                } else {
                                    mysqli_stmt_bind_param($stmt_f,'i',$row['flight_id']);            
                                    mysqli_stmt_execute($stmt_f);
                                    $result_f = mysqli_stmt_get_result($stmt_f);
                                    if($row_f = mysqli_fetch_assoc($result_f)) {
                                        $date_time_dep = $row_f['departure'];
                                        $date_dep = substr($date_time_dep,0,10);
                                        $time_dep = substr($date_time_dep,10,6) ;    
                                        $date_time_arr = $row_f['arrivale'];
                                        $date_arr = substr($date_time_arr,0,10);
                                        $time_arr = substr($date_time_arr,10,6) ; 
                                        if($row['class'] === 'E') {
                                            $class_txt = 'ECONOMY';
                                        } else if($row['class'] === 'B') {
                                            $class_txt = 'BUSINESS';
                                        }

                                        $unique_id = $row['ticket_id'];

                                        echo '
                                        <div class="row mb-5">                                                         
                                            <div class="col-8 out">
                                                <div class="row ">                                                     
                                                    <div class="col">
                                                        <h2 class="text-secondary mb-0 brand">
                                                            Online Flight Booking</h2> 
                                                    </div>
                                                    <div class="col">
                                                        <h2 class="mb-0">'.$class_txt.' CLASS</h2>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row mb-3">  
                                                    <div class="col-4">
                                                        <p class="head">Airline</p>
                                                        <p class="txt">'.$row_f['airline'].'</p>
                                                    </div>            
                                                    <div class="col-4">
                                                        <p class="head">from</p>
                                                        <p class="txt">'.$row_f['source'].'</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="head">to</p>
                                                        <p class="txt">'.$row_f['Destination'].'</p>                
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-8">
                                                        <p class="head">Passenger</p>
                                                        <p class="h5 text-uppercase">
                                                        '.$row_p['f_name'].' '.$row_p['m_name'].' '.$row_p['l_name'].'
                                                        </p>                              
                                                    </div>
                                                    <div class="col-4">
                                                        <p class="head">board time</p>
                                                        <p class="txt">12:45</p>
                                                    </div> 
                                                </div>
                                                <div class="row">
                                                    <div class="col-3">
                                                        <p class="head">departure</p>
                                                        <p class="txt mb-1">'.$date_dep.'</p>
                                                        <p class="h1 font-weight-bold mb-3">'.$time_dep.'</p>  
                                                    </div>            
                                                    <div class="col-3">
                                                        <p class="head">arrival</p>
                                                        <p class="txt mb-1">'.$date_arr.'</p>
                                                        <p class="h1 font-weight-bold mb-3">'.$time_arr.'</p>  
                                                    </div>
                                                    <div class="col-3">
                                                        <p class="head">gate</p>
                                                        <p class="txt">A22</p>
                                                    </div>            
                                                    <div class="col-3">
                                                        <p class="head">seat</p>
                                                        <p class="txt">'.$row['seat_no'].'</p>
                                                    </div>                
                                                </div>                    
                                            </div>
                                            <div class="col-3 pl-0" style="background-color:#376b8d !important; padding:20px; border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                                                <div class="row">  
                                                    <div class="col">                                    
                                                        <h2 class="text-light text-center brand">
                                                            Online Flight Booking</h2> 
                                                    </div>                                      
                                                </div>                             
                                                <div class="row justify-content-center">
                                                    <div class="col-12">                                    
                                                        <img src="assets/images/airtic.png" class="mx-auto d-block"
                                                        height="200px" width="200px" alt="">
                                                    </div>                                
                                                </div>
                                                <div class="row">
                                                    <h3 class="text-light2 text-center mt-2 mb-0">
                                                    &nbsp Thank you for choosing us. <br><br>
                                                    Please be at the gate at boarding time</h3>   
                                                </div>                            
                                            </div>   
                                            
                                            <div class="col-1">
                                                <div class="dropdown">
                                                    <button class="btn btn-danger dropdown-toggle" type="button" 
                                                        id="dropdownMenuButton" data-toggle="dropdown" 
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>  
                                                    <div class="dropdown-menu">
                                                                    <form class="px-4 py-3" action="e_ticket.php" target="_blank"
                                                                        method="post">
                                                                        <input type="hidden" name="ticket_id" 
                                                                            value="'.$unique_id.'">
                                                                        <button class="btn w-100  btn-primary btn"
                                                                            name="print_but">
                                                                            <i class="fa fa-print"></i> &nbsp; Print Ticket</button>
                                                                    </form>
                                                                    <!-- Cancel Ticket Form -->
                                                                    <form class="px-4 py-3" id="cancelForm-' . $unique_id . '" action="ticket.php" method="post">
                                                                        <input type="hidden" name="ticket_id" value="' . $unique_id . '">
                                                                        <button type="button" class="btn btn-danger btn" name="cancel_but"
                                                                        onclick="
                                                                            var userConfirmed = confirm(\'Are you sure you want to Cancel the ticket and Flight?\');
                                                                            if (userConfirmed) {
                                                                                var status = \'' . $row_f['status'] . '\';
                                                                                if (status === \'dep\') {
                                                                                    alert(\'Cannot cancel ticket, Flight already departed!\');
                                                                                } else if (status === \'arr\') {
                                                                                    alert(\'Cannot cancel ticket, Flight already arrived!\');
                                                                                } else if (status === \'issue\') {
                                                                                    alert(\'Flight has an issue, we are working on it.\');
                                                                                } else {
                                                                                    var form = document.getElementById(\'cancelForm-' . $unique_id . '\');
                                                                                    var input = document.createElement(\'input\');
                                                                                    input.type = \'hidden\';
                                                                                    input.name = \'cancel_but\';
                                                                                    form.appendChild(input);
                                                                                    form.submit();
                                                                                }
                                                                            }
                                                                        ">
                                                                            <i class="fa fa-trash"></i> &nbsp; Cancel Ticket
                                                                        </button>
                                                                    </form>
                                                                    <!-- Delete Ticket Form -->
                                                                    <form class="px-4 py-3" id="deleteForm-'.$unique_id.'" action="ticket.php" method="post">
                                                                        <input type="hidden" name="ticket_id" value="'.$unique_id.'">
                                                                        <button type="button" class="btn btn-danger btn" name="delete_but" 
                                                                            onclick="
                                                                                var userConfirmed = confirm(\'Are you sure you want to Delete the ticket?\');
                                                                                if (userConfirmed) {
                                                                                    var status = \'' . $row_f['status'] . '\';
                                                                                    if (status === \'issue\') {
                                                                                        alert(\'Flight has an issue, we are working on it.\');
                                                                                    } else {
                                                                                    var form = document.getElementById(\'deleteForm-' . $unique_id . '\');
                                                                                    var input = document.createElement(\'input\');
                                                                                    input.type = \'hidden\';
                                                                                    input.name = \'delete_but\';
                                                                                    form.appendChild(input);
                                                                                    form.submit();
                                                                                    }
                                                                                }
                                                                            ">
                                                                            <i class="fa fa-trash"></i> &nbsp; Delete Ticket
                                                                        </button>
                                                                    </form>                                  
                                                    </div>
                                                </div>              
                                            </div>                          
                                        </div>
                                        '; 
                                    }
                                }                  
                            }
                        }
                    }  
                }
            }else {
                $flag=true;
            }
        } 
    }  
      
    ?> 

    </div>
</main>
<?php } ?>
<?php subview('footer.php'); ?> 