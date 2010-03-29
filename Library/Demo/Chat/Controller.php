<?php
class Demo_Chat_Controller extends Framework_Controller {

	protected $_key = 519051;

	public function indexAction() {
		Framework_View::getInstance()->addStyleSheet('/css/chat.css');
		Framework_View::getInstance()->addScript('/js/jquery-1.3.2.min.js');
		Framework_View::getInstance()->addScript('/js/chat.js');
		Framework_View::getInstance()->htmlResult('chat');	
	}

	public function postAction() {
		$line = $_REQUEST['msg'];
		$id = $_REQUEST['id'];
		$msg_id = msg_get_queue($this->_key, 0600);
	    	die(json_encode(msg_send($msg_id, 1, "$id!$line", true, true, $msg_err)));
	}

	public function readAction() {
		$msg_id = msg_get_queue($this->_key, 0600);
		while(true) {
		   if(msg_receive($msg_id, 1, $msg_type, 16384, $msg, true, 0, $msg_error)) {
			list($id, $msg) = split('!', $msg);
			echo json_encode(array('id' => $id, 'msg' => $msg));
		   }
		}
		msg_remove_queue($msg_id);
	}

}

