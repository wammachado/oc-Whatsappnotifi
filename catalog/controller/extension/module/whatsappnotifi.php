<?php
class ControllerExtensionModuleWhatsappnotifi extends Controller
{
    public function sendmsg($urldefault,$token, $openTicket, $queueId, $phone,$body){

        $url = $urldefault;

        $data = array(
            "number" => $phone,
            "openTicket" => $openTicket,
            "queueId" => $queueId,
            "body" => $body
        );

        $postdata = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Authorization: Bearer '.$token
				)
			);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;


	}
}