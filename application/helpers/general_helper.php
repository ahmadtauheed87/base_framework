<?php

function notification()
    {
$CI = get_instance();
        //$query= $CI->query("select * from `notification` where `timestamp` LIKE '".date('Y-m-d')."%'");
        $query= "select * from `notification` where `timestamp` LIKE '".date('Y-m-d')."%'";
        $row=manual_query($query);

       // print_r($this->db->last_query());
       
        $html= '';
        $html .="<b>$query->num_rows</b>";
        echo $html;
        return $query->row();
        // exit();
    }

function getdata($select, $table, $where = '', $group_by = '', $order_by = '', $limit = '') {
    $CI = get_instance();
    $CI->load->model('common');
    return $CI->common->getdata_mod($select, $table, $where, $group_by, $order_by, $limit);
}

function getdatajoin($select, $table, $join_table, $Join_coloumn, $join_type = '', $where = '', $group_by = '', $order_by = '', $limit = '') {
    $CI = get_instance();
    $CI->load->model('common');
    return $CI->common->getdatajoin_mod($select, $table, $where, $join_table, $Join_coloumn, $join_type);
}

function adddata($data, $table) {
    $CI = get_instance();
    $CI->load->model('common');
    return $CI->common->adddata_mod($data, $table);
}

function updatedata($data, $where, $table) {
    $CI = get_instance();
    $CI->load->model('common');
    return $CI->common->updatedata_mod($data, $where, $table);
}

function deletedata($table, $where) {
    $CI = get_instance();
    $CI->load->model('common');
    return $CI->common->deletedata_mod($table, $where);
}

function sort_select_data($arr, $key, $value) {
    if (sizeof($arr) > 0) {
        foreach ($arr as $a) {
            $new[$a[$key]] = $a[$value];
        }
    }
    return $new;
}

function select_query($query) {
    $CI = get_instance();
    $CI->load->model('common');
    return $CI->common->select_query_mod($query);
}



function send_transaction_mail($cust_email, $mer_email) {
    if (!empty($cust_email)) {
        $to[] = $cust_email;
    }
    if (!empty($mer_email)) {
        $to[] = $mer_email;
    }
    $sub = 'Payment Transaction';
    $msg = 'Payment Transaction Done Please check the Portal for Details';
    email_send($to, $sub, $msg, $cc = '', $bcc = '', $attach = '');
}

function email_send($to, $sub, $msg, $cc = '', $bcc = '', $attach = '') {
    $CI = get_instance();
    $email_conf = getdata('*', 'email_config', array('active' => 1));
    $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => $email_conf[0]['host'],
        'smtp_port' => $email_conf[0]['port'],
        'smtp_user' => $email_conf[0]['username'], // change it to yours
        'smtp_pass' => $email_conf[0]['password'], // change it to yours
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE);

    $CI->load->library('email', $config);
    $CI->email->from($email_conf[0]['from']);
    $CI->email->to($to);
    $CI->email->subject($sub);
    $CI->email->message($msg);

    if ($attach != "") {
        $CI->email->attach($attach);
    }
    if ($cc != "") {
        $CI->email->cc($cc);
    }
    if ($bcc != "") {
        $CI->email->bcc($bcc);
    }

    if (!$CI->email->send()) {
        echo $CI->email->print_debugger();
    }
}

// get locality from geolocation (By Dharmesh)
function getPlaceName($latitude, $longitude)
{
   //This below statement is used to send the data to google maps api and get the place name in different formats. we need to convert it as required. 
   $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBstPPuQPSSJNXE4iWco_5aAqVSeFsSM-E&latlng='.$latitude.','.$longitude.'&sensor=false');
   $output= json_decode($geocode,true);

   foreach ($output['results'][0]['address_components'] as $value) {
       if($value['types'][0]=="locality")
       {
        echo  $value['long_name']; 
      }
       //var_dump($value);
        //echo "<hr>";
      //key=AIzaSyBstPPuQPSSJNXE4iWco_5aAqVSeFsSM-E
   }

       
}


//currency converted in USD
function convertCurrencyUnit($ugx_amount)
{
 $amount = urlencode(1);
  $from_Currency = urlencode("USD");
  $to_Currency = urlencode("UGX");
  $get = file_get_contents("https://www.google.com/finance/converter?a=$amount&from=USD&to=$to_Currency");
  $get = explode("<span class=bld>",$get);
  $get = explode("</span>",$get[1]);  
  // $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
  $converted_amount = round ($ugx_amount/$get[0],2);
  return $converted_amount;
  // $decodedArray = json_decode($rawdata, true);
}

//currency converted in USD END

// get distance 
        function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
      }
      // get distance END

      // send sms
       function httpGet($url)
        {
          $ch = curl_init();  
       
          curl_setopt($ch,CURLOPT_URL,$url);
          // curl_setopt($ch, CURLOPT_PORT, 10010);
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
          // curl_setopt($ch,CURLOPT_HEADER, false); 
       
          $output=curl_exec($ch);
       
        if($output === false)
        {
            echo "Error Number:".curl_errno($ch)."<br>";
            echo "Error String:".curl_error($ch);
        }

          curl_close($ch);
          // return $output;
          // echo $output;
      }


  function send_sms($message,$no)
  {
     // echo $no;
     $mes = urlencode($message);
     httpGet("http://smpp0.smsintervas.co.uk/http/sendsms.php?username=bargaincry&password=hqzE9s3T&sender=8884&to=%2B".$no."&text=".$mes);

  }

  // send END

  // send email function
  function send_email($cust_email,$cust_name,$subject,$body_message)
  {
    require_once('class/mailer/custom_mail.php');
    $mail = new PHPMailer;

      //$mail->SMTPDebug = 3;                               // Enable verbose debug output

      $mail->isSMTP();  
      $mail->Debugoutput = 'html';                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.elasticemail.com';                // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'asif.dange@gmail.com';                 // SMTP username
      $mail->Password = 'd53d90a1-578e-4978-b20e-df65408a93e7';  // SMTP password
      $mail->Port = '2525';                                    // Set mailer to use SMTP
      // $mail->Host = 'smtp.zoho.com';                        // Specify main and backup SMTP servers
      // $mail->SMTPAuth = true;                               // Enable SMTP authentication
      // $mail->Username = 'auto-mail@bargaincry.com';                 // SMTP username
      // $mail->Password = 'hogo@007';                           // SMTP password
      // $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
      // $mail->Port = 465;                                    // TCP port to connect to

      $mail->setFrom('easynikaah@gmail.com', 'Admin - Easy Nikah');
      $mail->addAddress($cust_email, $cust_name);     // Add a recipient

      // $mail->addAttachment('assets/images/logo.png','new');    // Optional name
      // $a=1;
      // for ($i=0; $i < $quantity[$key]; $i++) { 
      // $mail->addAttachment('assets/images/sorry-search.png', $data1['purchaseID'].'-'.$a);   
      // $a++; 
      //  }
        
      $mail->isHTML(true);                                  // Set email format to HTML

      $mail->Subject = $subject;
      $mail->Body    = $body_message;
      // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->send();
  }
