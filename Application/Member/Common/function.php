<?php 

/**
 * 验证码
 * @param  [type]  $code [description]
 * @param  integer $id   [description]
 * @return [type]        [description]
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}