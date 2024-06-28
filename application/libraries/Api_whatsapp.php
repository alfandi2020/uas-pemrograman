<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
// require_once('PHPExcel.php');

class Api_whatsapp{
    function wa_notif($phonee)
    {
      $curl = curl_init();
      $curl2 = curl_init();
      $curl3 = curl_init();
      $token = "gYGG2YKTv9odqMHhyi2PFIFo2eMSrCom9wVAJmVpLi8"; 
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
          'to_number' => "62".substr($phonee,1),
          'to_name' => $get_client['nama'],
          'message_template_id' => 'c83eba1a-1c1c-4100-a3a1-c335d042d12a',
          'channel_integration_id' => 'c7b25ef0-9ea4-4aff-9536-eb2eadae3400',
          'room' => [
            'tags' => ['mahfud'],
          ],
          'language' => [
            'code' => 'id'
          ],
          'parameters' => [
            'body' => [
              [
                'key' => '1', //{{ buat key 1,2,3,4 }}
                'value' => 'name', //field di excel contact
                'value_text' => $get_client['nama'] //value
              ],
              [
                'key' => '2', //{{ buat key 1,2,3,4 }}
                'value' => 'company', //kode pelanggan
                'value_text' => $get_client['kode_pelanggan'] //value
              ],
              [
                'key' => '3', //{{ buat key 1,2,3,4 }}
                'value' => '165000', //tagihan
                'value_text' => number_format(floor($xx + $ppn)) //value
              ],
              [
                'key' => '4', //{{ buat key 1,2,3,4 }}
                'value' => '124', //kode unik
                'value_text' => $kd_unik_in //value
              ],
              [
                'key' => '5', //{{ buat key 1,2,3,4 }}
                'value' => '150000', //total tagihan
                'value_text' => number_format(floor($xx + $ppn -$kd_unik_in)) //value
              ]
            ]
          ]
        ]),
        CURLOPT_HTTPHEADER => [
          "Authorization: Bearer ".$token."",
          "Content-Type: application/json"
        ],
      ]);
  
      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      echo $response;

                // redirect('permohonan/index/'.$this->hash_url->base64_url_encode($otp).'/'.$this->hash_url->base64_url_encode($phone));
        // }
    }
    function wa_notif_doc($msgg,$phonee,$file)
    {
        $sender = 'ljnbabelan';
        $phone = $phonee;
        $msg = $msgg;
        // if ($sender == "mahfud") {
                // $token = "rasJFCC37ewayax21uu2Caog9CCqyT3KSwBWFqQAbQMdMAefxa";
                // $phone = $phone; //untuk group pakai groupid contoh: 62812xxxxxx-xxxxx
                $curl = curl_init();

                   curl_setopt_array($curl, array(
                     CURLOPT_URL => 'http://103.171.85.211:8000/send-media',
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_ENCODING => '',
                     CURLOPT_MAXREDIRS => 10,
                     CURLOPT_TIMEOUT => 0,
                     CURLOPT_FOLLOWLOCATION => true,
                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                     CURLOPT_CUSTOMREQUEST => 'POST',
                     CURLOPT_POSTFIELDS => array('sender' => $sender,'number' => $phone,'file' => $file,'caption' => $msgg),
                   ));
                   
                   $response = curl_exec($curl);
                   
                   curl_close($curl);
                return $response;
                // redirect('permohonan/index/'.$this->hash_url->base64_url_encode($otp).'/'.$this->hash_url->base64_url_encode($phone));
        // }
    }
}