<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require '../application/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Woocommerce extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
        $username = 'ck_0657e94e2ebe427b5a79f64391e1184ff1bc3c4f'; // Add your own Consumer Key here
        $password = 'cs_12477007153077b9e259e58bafb55422225aa1cb'; // Add your own Consumer Secret here

        $ch = curl_init('https://esctoserba.com/wc-api/v3/products?filter[limit]=-1');
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);                                  
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                     
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'                                                                 
        ));                                                                                                                   

        $result = curl_exec($ch);
        $result = json_decode($result);
        $result = $result->products;

        //print_r(count($result));
        $no = 1;
        for($i=0; $i<count($result); $i++){
            echo $no.". ".$result[$i]->title." <img src='".$result[$i]->featured_src."' /><br/>";
            $no++;
        }
    }

}