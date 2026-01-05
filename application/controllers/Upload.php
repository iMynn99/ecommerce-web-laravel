<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function summernoteImage() {
        header("Content-Type: text/plain");

        $config['upload_path'] = FCPATH . 'cdn/uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        // Pastikan direktori ada dan writable
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        if (!$this->upload->do_upload('image')) {
            // Kirim response gagal (bukan HTML)
            http_response_code(400);
            echo "Gagal upload: " . strip_tags($this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            $imageURL = base_url('cdn/uploads/' . $data['file_name']);
            echo $imageURL;
        }
    }
}