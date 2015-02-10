<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function error_exit($msg){
	exit(json_encode(array("error"=>$msg)));
}

function check_auth(){
	$CI =& get_instance();
	$email = $CI->input->post("email");
	$password = $CI->input->post("password");
	$no_hash = $CI->input->post("no_hash");

	if(!$email || !$password){
		$CI->load->library("session");
		$creds = $CI->session->all_userdata();

		if(!$creds["email"] || !$creds["password"]){
			error_exit("no email or password given");
		}else{
			$email = $creds["email"];
			$password = $creds["password"];
			$no_hash = $creds["no_hash"];
		}
	}
	if($no_hash){
		$password = md5($password);
	}
	$CI->load->model("users_model");
	$res = $CI->users_model->check_login($email, $password);
	if(!$res){
		error_exit("invalid login");
	}else{
		return $res;
	}
}