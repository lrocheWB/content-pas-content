<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle;



/**
 * Description of Mailer
 *
 * @author bpiscart
 */
class Mailer {
    
    public function __construct($api_key) {
        
        $this->api_key = $api_key;
    }
    
    public function send($params){
        $res = "";
        
        $data = "username=" . urlencode($this->api_key);
        $data .= "&api_key=" . urlencode($this->api_key);

        
        $data .= "&from=" . urlencode($params['from']);
        $data .= "&from_name=" . urlencode($params['from_string']);
        $data .= "&to=" . urlencode($params['to']);
        $data .= "&subject=" . urlencode($params['subject']);

        if (isset($params['body_html']) && !empty($params['body_html']))
        {
            $data .= "&body_html=" . urlencode($params['body_html']);
        }

        if (isset($params['body_txt']) && !empty($params['body_txt']))
        {
            $data .= "&body_text=" . urlencode($params['body_txt']);
        }

        $header = "POST /mailer/send HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($data) . "\r\n\r\n";
        $fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30);

        if (!$fp)
        {
            return "ERROR. Could not open connection";
        } 
        else
        {
            fputs($fp, $header . $data);
            while (!feof($fp))
            {
                $res .= fread($fp, 1024);
            }
        }

        fclose($fp);

        if(preg_match('#Content-Length: 36#',$res))
        {
            return true;
        }
        
        return false;
    }
}