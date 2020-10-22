<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function _upload_image($FILES,$upload_path) {//echo '<PRE>';print_r($FILES);die;
    $ci =& get_instance();
    $image = array();
    foreach ($FILES as $key => $value) {  
        $filename   = $value['name'];
        $error_flag = FALSE;

        if(!empty($filename))
        {
            $config['upload_path']      = $upload_path;
            $config['file_name']	= $filename;
            $config['allowed_types']    = 'jpg|png|jpeg|pdf|docx|doc';//echo '<PRE>';print_r($config);
            $ci->load->library('upload');
            $ci->upload->initialize($config);
            if (!$ci->upload->do_upload($key))
            { //echo $ci->upload->display_errors();
                $error                  = $ci->upload->display_errors();//echo '<PRE>';print_r($this->upload->display_errors());
                $ci->session->set_flashdata('document_error_message',$error);
                $error_flag             = TRUE;
                return $error_flag;
            }
            else
            { //echo 'bye';
                
                $img_data = $ci->upload->data();
                array_push($image,$img_data);
            }
        }
    }
    return $image;
}