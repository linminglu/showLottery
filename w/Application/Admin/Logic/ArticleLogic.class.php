<?php
 namespace Admin\Logic; class ArticleLogic extends BaseLogic{ protected $_validate = array( array('content', 'getContent', '内容不能为空！', self::MUST_VALIDATE , 'callback', self::MODEL_BOTH), ); protected $_auto = array(); public function update($id = 0){ $data = $this->create(); if($data === false){ return false; } if(empty($data['id'])){ $data['id'] = $id; $id = $this->add($data); if(!$id){ $this->error = '新增详细内容失败！'; return false; } } else { $status = $this->save($data); if(false === $status){ $this->error = '更新详细内容失败！'; return false; } } return true; } protected function getContent(){ $type = I('post.type'); $content = I('post.content'); if($type > 1){ if(empty($content)){ return false; } }else{ if(empty($content)){ $_POST['content'] = ' '; } } return true; } public function autoSave($id = 0){ $this->_validate = array(); $data = $this->create(); if(!$data){ return false; } if(empty($data['id'])){ $data['id'] = $id; $id = $this->add($data); if(!$id){ $this->error = '新增详细内容失败！'; return false; } } else { $status = $this->save($data); if(false === $status){ $this->error = '更新详细内容失败！'; return false; } } return true; } } 