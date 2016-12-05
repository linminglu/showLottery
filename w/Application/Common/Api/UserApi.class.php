<?php
 namespace Common\Api; class UserApi { public static function is_login(){ $user = session('user_auth'); if (empty($user)) { return 0; } else { return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0; } } public static function is_administrator($uid = null){ $uid = is_null($uid) ? is_login() : $uid; return $uid && (intval($uid) === C('USER_ADMINISTRATOR')); } public static function get_username($uid = 0){ static $list; if(!($uid && is_numeric($uid))){ return session('user_auth.username'); } if(empty($list)){ $list = S('sys_active_user_list'); } $key = "u{$uid}"; if(isset($list[$key])){ $name = $list[$key]; } else { $User = new User\Api\UserApi(); $info = $User->info($uid); if($info && isset($info[1])){ $name = $list[$key] = $info[1]; $count = count($list); $max = C('USER_MAX_CACHE'); while ($count-- > $max) { array_shift($list); } S('sys_active_user_list', $list); } else { $name = ''; } } return $name; } public static function get_nickname($uid = 0){ static $list; if(!($uid && is_numeric($uid))){ return session('user_auth.username'); } if(empty($list)){ $list = S('sys_user_nickname_list'); } $key = "u{$uid}"; if(isset($list[$key])){ $name = $list[$key]; } else { $info = M('Member')->field('nickname')->find($uid); if($info !== false && $info['nickname'] ){ $nickname = $info['nickname']; $name = $list[$key] = $nickname; $count = count($list); $max = C('USER_MAX_CACHE'); while ($count-- > $max) { array_shift($list); } S('sys_user_nickname_list', $list); } else { $name = ''; } } return $name; } }