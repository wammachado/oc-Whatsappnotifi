<?php
class ControllerExtensionModuleWhatsappnotifi extends Controller
{

    public function index(){

        $this->load->language('extension/module/whatsappnotifi');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('extension/module/whatsappnotifi');

		
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$stores = $this->request->post["stores"];
			// var_dump($stores);die();
			$this->model_extension_module_whatsappnotifi->clear();

			//salva statuso do modulo
			$datasave = array(
				"code"=>"module_whatsappnotifi",
				"key"=>"module_whatsappnotifi_status",
				"value"=>$this->request->post["module_whatsappnotifi_status"]
			);

			$this->model_extension_module_whatsappnotifi->status($datasave);
			
			//salva numero de fone da instancia
			$datasave = array(
				"code"=>"module_whatsappnotifi",
				"key"=>"module_whatsappnotifi_numero",
				"value"=>$this->request->post["module_whatsappnotifi_numero"]
			);

			$this->model_extension_module_whatsappnotifi->status($datasave);
			
			//salva intancia da api
			$datasave = array(
				"code"=>"module_whatsappnotifi",
				"key"=>"module_whatsappnotifi_instance",
				"value"=>$this->request->post["module_whatsappnotifi_instance"]
			);

			$this->model_extension_module_whatsappnotifi->status($datasave);
			
			//salva token da api
			$datasave = array(
				"code"=>"module_whatsappnotifi",
				"key"=>"module_whatsappnotifi_token",
				"value"=>$this->request->post["module_whatsappnotifi_token"]
			);

			$this->model_extension_module_whatsappnotifi->status($datasave);
			
			$i = 0;			
			foreach($stores as $store){
				//salva numero da loja
				$datasave = array(
					"store_id"=>$this->request->post["stores"][$i],
					"code"=>"module_whatsappnotifi",
					"key"=>"module_whatsappnotifi_store",
					"value"=>1,
					"serialized"=>0
				);
				$this->model_extension_module_whatsappnotifi->save($this->request->post["stores"][$i], $datasave);

				$i++;
				
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/whatsappnotifi', 'user_token=' . $this->session->data['user_token'], true)
		);

		$this->load->model('setting/store');
		
		$data["module_whatsappnotifi_numero"]= ($this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_numero") ? $this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_numero")["value"] : "");
		$data["module_whatsappnotifi_instance"] = ($this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_instance") ? $this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_instance")["value"] : "");
		$data["module_whatsappnotifi_token"] = ($this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_token") ? $this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_token")["value"]: "");
		$data["module_whatsappnotifi_status"] = ($this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_status") ? $this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_status")["value"]:"");

		$data['stores'] = array();
		
		$stores = $this->model_setting_store->getStores();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
			"module_whatsappnotifi_store" => $this->model_extension_module_whatsappnotifi->select(0, "module_whatsappnotifi_store") ? $this->model_extension_module_whatsappnotifi->select($store['store_id'], "module_whatsappnotifi_store")["value"] : "0",
			
		);			

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name'],
				"module_whatsappnotifi_store" => $this->model_extension_module_whatsappnotifi->select($store['store_id'], "module_whatsappnotifi_store") ? $this->model_extension_module_whatsappnotifi->select($store['store_id'], "module_whatsappnotifi_store")["value"] : "0",
				
			);			
		}
		

		$data['action'] = $this->url->link('extension/module/whatsappnotifi', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/whatsappnotifi', $data));

        
	}

	public function testsend(){

		$instance = $this->request->post["instance"];
		$token = $this->request->post["token"];
		$sendto = $this->request->post["sendto"];
		$message = $this->request->post["message"];

		// print_r("ok");
		$send = $this->sendmsg($instance, $token,$sendto,$message);

		if($send){
			print_r('{"success":1,"message":"'.$send.'"');
		}else{
			print_r('{"success":0,"message":"'.$send.'"');

		}
	}
	
	public function sendmsg($instance,$token,$phone,$body){

		$url = "https://eu120.chat-api.com/$instance/sendMessage?token=$token";

        $data = array("phone" => "$phone","body" => "$body");

        $postdata = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;


	}
}