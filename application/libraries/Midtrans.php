<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tripay {

    protected $apiKey;
    protected $privateKey;

    public function __construct()
    {
        $CI =& get_instance();
        $setting = $CI->func->globalset("semua");

        $this->apiKey     = $setting->tripay_apikey;
        $this->privateKey = $setting->tripay_privatekey;
    }

    public function metode()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tripay.co.id/api/merchant/payment-channel",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $this->apiKey
            ]
        ));

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return []; // jika gagal return array kosong
        }

        $data = json_decode($response, true);
        return isset($data['data']) ? $data['data'] : [];
    }

    public function getTripay($reference, $field = "semua", $key = "reference")
    {
        $CI =& get_instance();
        $CI->db->where($key, $reference);
        $query = $CI->db->get("tripay");

        if ($query->num_rows() > 0) {
            $row = $query->row();
            if ($field == "semua") {
                return $row;
            } else {
                return $row->$field;
            }
        }

        return null;
    }

    // Tambahan createPayment bisa disesuaikan kalau kamu butuh
}