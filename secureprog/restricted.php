<?php
function setSession(){
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
}

function checkSession(){
    session_start();
    if(empty($_SESSION['userLogin']) || $_SESSION['userLogin'] == ''){
        header("Location: http://localhost:8000/");
        die();
    }
    else{
        $login_session_duration = 360; // Adjust the session duration as needed
        $current_time = time(); 
        if(isset($_SESSION['loggedin_time'])){  
            if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){
                session_destroy();
                header("Location: http://localhost:8000/");
                die();
            } 
        }
    }
}
?>