<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function _send_sms ($mobile,$message) {
    $result = file_get_contents('http://www.smsjust.com/sms/user/urlsms.php?username=mbafna&pass=123456&senderid=MBAFNA&dest_mobileno='.$mobile.'&message='.$message.'&response=Y');
    
    return $result;
}