<?php
if(!defined('BASEPATH')) exit('Hacking Attempt : Keluar dari sistem !! ');

class Tripay_payment extends CI_Model{
    public function __construct(){
        parent::__construct();
    }
    
    private function set($get="semua"){
        $arr = array(
            "merchant_code" => "T2545",
            //"merchant_code" => "T2546",
            "private_key" => "Y59F1-tJeZg-3i6N9-Ze2bF-H0QKm",
            //"private_key" => "A87sp-WAcsd-zu7Po-epHKw-lpmJo",
            "api_key" => "DEV-gz6dOlB2SUsMWG7TBa3U1b5CMbznteBuUj57TYbZ",
            //"api_key" => "YsevYB1qFvSW6aoB0BjLal2ILn3TNhDfIC81NofB",
            "url" => "https://payment.tripay.co.id/api-sandbox/",
        );

        $result = ($get == "semua") ? (object)$arr : $arr[$get];
        return $result;
    }

    private function signature($invoice,$jumlah){
        $set = $this->set("semua");
        $signature = hash_hmac('sha256', $set->merchant_code.$invoice.$jumlah, $set->private_key);
        return $signature;
    }

    function metode($get="semua"){
        $arr = array(
            "bcava"     => array("kode"=>"BCAVA","nama"=>"BCA Virtual Account","logo"=>"00.webp","biaya"=>4000),
            "briva"     => array("kode"=>"BRIVA","nama"=>"BRI Virtual Account","logo"=>"01.webp","biaya"=>4000),
            "mandiriva" => array("kode"=>"MANDIRIVA","nama"=>"Mandiri Virtual Account","logo"=>"03.webp","biaya"=>3000),
            "bniva"     => array("kode"=>"BNIVA","nama"=>"BNI Virtual Account","logo"=>"02.webp","biaya"=>4000),
            "permatava" => array("kode"=>"PERMATAVA","nama"=>"Permata Virtual Account","logo"=>"04.webp","biaya"=>4000),
            "smsva"     => array("kode"=>"SMSVA","nama"=>"Sinarmas Virtual Account","logo"=>"06.webp","biaya"=>4000),
            "mybva"     => array("kode"=>"MYBVA","nama"=>"Maybank Virtual Account","logo"=>"08.webp","biaya"=>4000),
            "alfamart"  => array("kode"=>"ALFAMART","nama"=>"Alfamart","logo"=>"10.webp","biaya"=>2000),
            "alfamidi"  => array("kode"=>"ALFAMIDI","nama"=>"Alfamidi","logo"=>"09.webp","biaya"=>2000),
            "qris"      => array("kode"=>"QRIS","nama"=>"QRIS (Gopay, OVO, Linkaja, Dana)","logo"=>"qris.png","biaya"=>5000)
        );

        $result = ($get == "semua") ? $arr : (object)$arr[$get];
        return $result;
    }

    function createPayment($trx,$metode,$jumlah,$pembeli,$produk=null){
        $set = $this->set("semua");
        $trx = $this->func->getTransaksi($trx,"semua");
        $produk = is_array($produk) ? $produk : [['sku'=>'BO1','name'=>'Paket Khusus Bikinonline','price'=> $jumlah,'quantity'=>1]];

        $data = [
            'method'            => $metode,
            'merchant_ref'      => $trx->invoice,
            'amount'            => $jumlah,
            'customer_name'     => $pembeli["nama"],
            'customer_email'    => $pembeli["email"],
            'customer_phone'    => $pembeli["nohp"],
            'order_items'       => $produk,
            'callback_url'      => 'https://app.jadiorder.com/tripay/webhook',
            //'return_url'        => 'https://domainanda.com/redirect',
            'expired_time'      => (time()+(24*60*60)), // 24 jam
            'signature'         => hash_hmac('sha256', $set->merchant_code.$trx->invoice.$jumlah, $set->private_key)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT     => true,
            CURLOPT_URL               => $set->url."transaction/create",
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_HEADER            => false,
            CURLOPT_HTTPHEADER        => array(
                "Authorization: Bearer ".$set->api_key
            ),
            CURLOPT_FAILONERROR       => false,
            CURLOPT_POST              => true,
            CURLOPT_POSTFIELDS        => http_build_query($data)
        ));

        $responses = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        /*
        print_r($response);
        print_r($err);
        */
        if(!empty($err)){
            $err = array("success"=>false,"msg"=>$err);
            $err = (object)$err;
            return $err;
        }else{
            //print_r($response);exit;
            $response = json_decode($responses,true);
            if($response["success"] != 1){
                $err = array("success"=>false,"msg"=>"Gagal memproses pembayaran");
                $err = (object)$err;
                return $err;
            }else{
                $response = $response["data"];
                //print_r($response);exit;
                $status = ($response["status"] == "PAID") ? 1 : 0;
                $fee = isset($response["fee"]) ? $response["fee"] : null;
                $qr_string = isset($response["qr_string"]) ? $response["qr_string"] : "";
                $qr_url = isset($response["qr_url"]) ? $response["qr_url"] : "";
                $data = array(
                    "tgl"   => date("Y-m-d H:i:s"),
                    "amount"=> $response["amount"],
                    "amount_received"=> $response["amount_received"],
                    "checkout_url"=> $response["checkout_url"],
                    "expired_time"=> $response["expired_time"],
                    "fee"=> $fee,
                    "qr_string" => $qr_string,
                    "qr_url" => $qr_url,
                    "merchant_ref"=> $response["merchant_ref"],
                    "pay_code"=> $response["pay_code"],
                    "pay_url"=> $response["pay_url"],
                    "payment_method"=> $response["payment_method"],
                    "reference"=> $response["reference"],
                    "status"=> $response["status"],
                    "statusbayar"=> $status,
                    "instructions"=> json_encode($response["instructions"]),
                    "rawdump"  => $responses
                );
                $this->db->insert("tripay",$data);

                $status = ($response["status"] == "PAID") ? 1 : 0;
                $datas = [
                    "tripay_ref"=>$response["reference"],
                    "tripay_metode"=>$metode,
                    "status"=>$status
                ];
                $this->db->where("id",$trx->id);
                $this->db->update("transaksi",$datas);

                $res = array("success"=>true,"msg"=>"Berhasil memproses pembayaran");
                $res = (object)$res;
                return $res;
            }
        }
    }

    function cekPayment($ref){
        $set = $this->set("semua");
        $payload = [
            'reference'	=> $ref
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_FRESH_CONNECT     => true,
          CURLOPT_URL               => $set->url."transaction/detail?".http_build_query($payload),
          CURLOPT_RETURNTRANSFER    => true,
          CURLOPT_HEADER            => false,
          CURLOPT_HTTPHEADER        => array(
            "Authorization: Bearer ".$set->api_key
          ),
          CURLOPT_FAILONERROR       => false,
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if(!empty($err)){
            $err = array("success"=>false,"msg"=>$err);
            //$err = (object)$err;
            return $err;
        }else{
            $response = json_decode($response,true);
            if($response["success"] != 1){
                $err = array("success"=>false,"msg"=>"Gagal memproses pembayaran");
                $err = (object)$err;
                return $err;
            }else{
                $response = $response["data"];
                $status = ($response["status"] == "PAID") ? 1 : 0;
                $data = array(
                    "amount"=> $response["amount"],
                    "amount_received"=> $response["amount_received"],
                    "checkout_url"=> $response["checkout_url"],
                    "expired_time"=> $response["expired_time"],
                    "fee"=> $response["fee"],
                    "merchant_ref"=> $response["merchant_ref"],
                    "pay_code"=> $response["pay_code"],
                    "pay_url"=> $response["pay_url"],
                    "payment_method"=> $response["payment_method"],
                    "reference"=> $response["reference"],
                    "status"=> $response["status"],
                    "statusbayar"=> $status,
                    "instructions"=> json_encode($response["instructions"])
                );
                $this->db->where("reference",$response["reference"]);
                $this->db->update("tripay",$data);

                $status = ($response["status"] == "PAID") ? 1 : 0;
                $datas = [
                    "tripay_ref"=>$response["reference"],
                    "tripay_metode"=>$metode,
                    "status"=>$status
                ];
                $this->db->where("id",$trx);
                $this->db->update("transaksi",$datas);

                $res = array("success"=>true,"response"=>$response);
                //$res = (object)$res;
                return $res;
            }
        }
    }

	function getTripay($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("tripay");

		if($res->num_rows() > 0){
			if($what == "semua"){
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}else{
				foreach($res->result() as $re){
					$result = $re->$what;
				}
			}
		}else{
			$result = new stdClass();
			$result->reference = "";
			$result->pay_url = "";
			$result->checkout_url = "";
			$result->tgl = "";
			$result->merchant_ref = "";
			$result->payment_method = "";
			$result->instructions = "";
			$result->paid_at = "";
			$result->status = 0;
			$result->statusbayar = 0;
			$result->amount = 0;
			$result->amount_received = 0;
			$result->expired_time = 0;
			$result->fee = 0;
			$result->paycode = 0;
		}
		return $result;
	}
}