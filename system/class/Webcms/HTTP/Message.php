<?php 

interface Webcms_HTTP_Message {
	
	public function protocol($protocol = NULL);

	public function headers($key = NULL, $value = NULL);

	public function body($content = NULL);

	public function render();
}