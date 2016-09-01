<?php

use Base\ApplicationController;

use Orm\Mapper\Map\PointModel as MapPoint;
use Orm\Map\PointModel as ModPoint;

use Orm\Mapper\Map\GroupModel as MapGroup;
use Orm\Map\GroupModel as ModGroup;


/**
 * 首页
 */
class IndexController extends ApplicationController
{

    /**
     * 布局模板的名称
     * @var string
     */
    protected $layout = null;

    protected $needlogin = true;

    protected $username = 'cjadmin';

    protected $cookieKey = '_cj_usn';

    /**
     * 首页
     *
     */
    public function indexAction()
    {

        $id = intval($this->getRequest()->get('id'));
        if (!empty($id)) {
            $points = MapPoint::getInstance()->fetchAll(array('group_id' => $id));
        } else {
            $points = MapPoint::getInstance()->fetchAll();
        }
        $grouplist = MapGroup::getInstance()->getNameList();
        $this->assign('points', $points);
        $this->assign('grouplist', $grouplist);

        return true;
    }

    public function detailAction()
    {
        $id = intval($this->getRequest()->get('id'));
        $modPoint = MapPoint::getInstance()->find($id);
        if(!$modPoint instanceof ModPoint){
           $this->redirect('/');
        }
        $this->assign('point',$modPoint);
    }



    public function pointAction()
    {
        $this->checkLogin();
        $mapPoint  = MapPoint::getInstance();
        $points    = $mapPoint->fetchAll();
        $grouplist = MapGroup::getInstance()->getNameList();

        $this->assign('points', $points);
        $this->assign('grouplist', $grouplist);
    }


    public function groupAction()
    {
        $this->checkLogin();
        $mapGroup = MapGroup::getInstance();
        $list     = $mapGroup->fetchAll(array('is_delete'=>0));
        $this->assign('groups', $list);
    }

    public function groupreplaceAction()
    {
        $this->checkLogin();
        $id     = abs(intval($this->getRequest()->get('id', 0)));
        $author = '';
        if ($id) {
            $modAuthor = MapGroup::getInstance()->find($id);
            if ($modAuthor) {
                $author = $modAuthor->toArray();
            }
        }

        $this->assign('group', $author);
    }

    public function groupupdateAction()
    {

        $this->disableView();

        $new     = true;
        $request = $this->getRequest();
        $name    = trim($request->get('name'));
        $sort    = intval($request->get('sort'));

        $updatetype = trim($request->get('updatetype'));
        if ('update' == $updatetype) {

            $new = false;
        }


        $mapGroup = MapGroup::getInstance();
        if ($new) {
            $modGroup = new ModGroup();
        } else {
            $id       = intval($request->get('id', 0));
            $modGroup = $mapGroup->find($id);
            if (!$modGroup instanceof ModGroup) {
                $new      = true;
                $modGroup = new ModGroup();
            }
        }

        $modGroup->setName($name)->setSort($sort);

        if ($new) {
            $mapGroup->insert($modGroup);
        } else {
            $mapGroup->update($modGroup);

        }

        $this->redirect($this->getUrl('index/index/group'));


    }

    public function groupdeleteAction()
    {
        $this->disableView();
        $this->checkLogin();

        $request  = $this->getRequest();
        $id       = intval($request->get('id', 0));

        $mapGroup = MapGroup::getInstance();
        $modGroup = $mapGroup->find($id);


        if (!empty($modGroup)) {
            $modGroup->setIsDelete(1);
            $mapGroup->update($modGroup);
        }

        $this->redirect($this->getUrl('index/index/group'));

    }

    public function pointreplaceAction()
    {
        $this->checkLogin();
        $id    = abs(intval($this->getRequest()->get('id', 0)));
        $point = '';
        if ($id) {
            $modPoint = MapPoint::getInstance()->find($id);
            if ($modPoint) {
                $point = $modPoint->toArray();
            }
        }

        $grouplist = MapGroup::getInstance()->getNameList();

        $this->assign('grouplist', $grouplist);
        $this->assign('point', $point);
    }

    public function pointupdateAction()
    {

        $this->disableView();
        $this->checkLogin();
        $new     = true;
        $request = $this->getRequest();


        $name        = trim($request->get('name'));
        $remark      = trim($request->get('remark'));
        $phone       = trim($request->get('phone'));
        $address     = trim($request->get('address'));
        $url_address = trim($request->get('url_address'));
        $point       = trim($request->get('point'));
        $group_id    = intval($request->get('group_id'));

        $updatetype = trim($request->get('updatetype'));
        if ('update' == $updatetype) {
            $new = false;
        }


        $mapPoint = MapPoint::getInstance();
        if ($new) {
            $modPoint = new ModPoint();
        } else {
            $id       = intval($request->get('id', 0));
            $modPoint = $mapPoint->find($id);
            if (!$modPoint instanceof ModPoint) {
                $new      = true;
                $modPoint = new ModPoint();
            }
        }

        $modPoint->setName($name)
            ->setAddress($address)
            ->setRemark($remark)
            ->setPhone($phone)
            ->setGroupId($group_id)
            ->setUrlAddress($url_address)
            ->setPoint($point);
        $pics = '';
        if (isset($_FILES['picurl']['name']) && $_FILES['picurl']['name']) {
            $pics = $this->uploadfile($_FILES['picurl']);

        }
        if($pics){
            $modPoint->setPics($pics);
        }else{
            if($new){
                $modPoint->setPics($pics);
            }
        }
        if ($new) {
            $mapPoint->insert($modPoint);
        } else {
            $mapPoint->update($modPoint);
        }


        $this->redirect($this->getUrl('index/index/point'));


    }

    public function pointdeleteAction()
    {
        $this->disableView();
        $this->checkLogin();
        $request  = $this->getRequest();
        $id       = intval($request->get('id', 0));
        $mapPoint = MapPoint::getInstance();
        $modPoint = $mapPoint->find($id);
        if (!empty($modPoint)) {
            $mapPoint->delete($modPoint);
        }

        $this->redirect($this->getUrl('index/index/point'));

    }

    public function loginAction()
    {
        if ($this->isLogined()) {
            $this->redirect('/index/index/index');
        }


    }


    public function checkloginAction()
    {
        $request  = $this->getRequest();
        $username = trim($request->get('username'));
        $pwd      = trim($request->get('pwd'));
        if ($username == 'cjadmin' && md5($pwd) == '0c4db3932509bbd0ff1d54c942bc6925') {
            setcookie($this->cookieKey, $this->username);
            $this->redirect('/index/index/point');

            return true;
        }

        $this->redirect('/index/index/login');

    }

    protected function isLogined()
    {
        $username = isset($_COOKIE[$this->cookieKey]) ? $_COOKIE[$this->cookieKey] : '';
        if ($username && $username == $this->username) {
            return true;
        }

        return false;
    }

    protected function checkLogin()
    {
        if (!$this->isLogined()) {
            $this->redirect('/index/index/login');
        }
    }

    /**
     * @param $file array
     * @param $modPoint Orm\Map\PointModel
     */
    protected function uploadfile($file)
    {
        $picsConfig = \Bootstrap::getPicsConfig();
        $path = $picsConfig['directory'];
        $url  = $picsConfig['url'];

        $name = $file['name'];
        list($name, $ext) = explode('.', $name);
        $filename =  date('YmdHis') .'.'. $ext;

        $type = $file['type'];
        list($filetype_a, $filetype_b) = explode('/', $type);
        if ('image' != $filetype_a) {
            return '';
        }
        $temp = $file['tmp_name'];
        $error = $file['error'];
        if ($error) {
            return '';
        }

        move_uploaded_file($temp, $path . '/' .$filename);

        return $url.$filename;
    }


}
