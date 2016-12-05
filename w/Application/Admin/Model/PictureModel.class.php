<?php
 namespace Admin\Model; use Think\Model; use Think\Upload; class PictureModel extends Model{ protected $_auto = array( array('status', 1, self::MODEL_INSERT), array('create_time', NOW_TIME, self::MODEL_INSERT), ); public function upload($files, $setting, $driver = 'Local', $config = null){ $setting['callback'] = array($this, 'isFile'); $setting['removeTrash'] = array($this, 'removeTrash'); $Upload = new Upload($setting, $driver, $config); $info = $Upload->upload($files); if($info){ foreach ($info as $key => &$value) { if(isset($value['id']) && is_numeric($value['id'])){ continue; } $value['path'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename']; if($this->create($value) && ($id = $this->add())){ $value['id'] = $id; } else { unset($info[$key]); } } return $info; } else { $this->error = $Upload->getError(); return false; } } public function download($root, $id, $callback = null, $args = null){ $file = $this->find($id); if(!$file){ $this->error = '不存在该文件！'; return false; } switch ($file['location']) { case 0: $file['rootpath'] = $root; return $this->downLocalFile($file, $callback, $args); case 1: break; default: $this->error = '不支持的文件存储类型！'; return false; } } public function isFile($file){ if(empty($file['md5'])){ throw new \Exception('缺少参数:md5'); } $map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],); return $this->field(true)->where($map)->find(); } private function downLocalFile($file, $callback = null, $args = null){ if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){ is_callable($callback) && call_user_func($callback, $args); header("Content-Description: File Transfer"); header('Content-type: ' . $file['type']); header('Content-Length:' . $file['size']); if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"'); } else { header('Content-Disposition: attachment; filename="' . $file['name'] . '"'); } readfile($file['rootpath'].$file['savepath'].$file['savename']); exit; } else { $this->error = '文件已被删除！'; return false; } } public function removeTrash($data){ $this->where(array('id'=>$data['id'],))->delete(); } } 