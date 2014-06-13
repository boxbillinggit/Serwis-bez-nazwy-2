<?php 

interface Webcms_HTTP_Response extends HTTP_Message {

	public function status($code = NULL);

}