<?php
/**
 * @file
 * @package legroup
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit;
}

require_once LEGROUP_TRUST_PATH . '/class/AbstractViewAction.class.php';

/**
 * Legroup_GroupViewAction
**/
class Legroup_GroupViewAction extends Legroup_AbstractViewAction
{

    /**
     * &_getHandler
     * 
     * @param    void
     * 
     * @return    Legroup_GroupHandler
    **/
    protected function &_getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Group');
        return $handler;
    }

    /**
     * _checkPublicity
     * 
     * @param    void
     * 
     * @return    bool
    **/
    protected function _checkPublicity()
    {
        if($this->mObject->get('publicity')==Legroup_Publicity::OPEN){
            return true;
        }

        return $this->mObject->isMember(Legacy_Utils::getUid(), Lenum_GroupRank::ASSOCIATE, 5);
    }

    /**
     * prepare
     * 
     * @param    void
     * 
     * @return    bool
    **/
    public function prepare()
    {
        parent::prepare();
        $this->mObject->mMember = $this->mObject->getMembers(Lenum_GroupRank::ASSOCIATE);
        return true;
    }

    /**
     * executeViewSuccess
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
        $render->setTemplateName($this->mAsset->mDirname . '_group_view.html');
        $render->setAttribute('object', $this->mObject);
        $render->setAttribute('image', $this->mObject->getImage());
        $render->setAttribute('dirname', $this->mAsset->mDirname);

        $clientList = array();
        $dataList = array('template_name'=>array(), 'title'=>array(), 'data'=>array(), 'dirname'=>array(), 'dataname'=>array());
        if($this->_checkPublicity()){
            $clientList = Legroup_Utils::getClientList($this->mAsset->mDirname);
            foreach($clientList as $client){
                $dataList = $this->mObject->getClientData($dataList, $client);
            }
        
            foreach(array_keys($dataList['data']) as $key){
                $check = false;
                XCube_DelegateUtils::call('Legacy_Group.'.$this->mAsset->mDirname.'.HasPermission', new XCube_Ref($check), $this->mAsset->mDirname, $this->mObject->get('group_id'), $dataList['dirname'][$key], $dataList['dataname'][$key], 'edit');
                $dataList['isEditor'][$key] = $check;
                unset($check);
            }
        }
        $render->setAttribute('clientList', $clientList);
        $render->setAttribute('clients', $dataList);
        $render->setAttribute('isEditor', $this->mObject->isMember(Legacy_Utils::getUid(), Lenum_GroupRank::OWNER));

        //breadcrumb
        $breadcrumbs = array();
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Global.Event.GetBreadcrumbs', new XCube_Ref($breadcrumbs), $this->mAsset->mDirname, $this->mObject);
        $render->setAttribute('xoops_breadcrumbs', $breadcrumbs);

    }

    /**
     * executeViewError
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeRedirect($this->_getNextUri('group', 'list'), 1, _MD_LEGROUP_ERROR_CONTENT_IS_NOT_FOUND);
    }
}

?>
