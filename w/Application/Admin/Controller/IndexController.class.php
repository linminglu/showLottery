<?php
 namespace Admin\Controller; use User\Api\UserApi as UserApi; class IndexController extends AdminController { public function index(){ if(UID){ $this->redirect("Config/group"); } else { $this->redirect('Public/login'); } } } 