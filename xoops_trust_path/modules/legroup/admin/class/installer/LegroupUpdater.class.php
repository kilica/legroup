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

require_once LEGROUP_TRUST_PATH . '/admin/class/installer/LegroupInstallUtils.class.php';

/**
 * Legroup_Updater
**/
class Legroup_Updater
{
    public /*** Legacy_ModuleInstallLog ***/ $mLog = null;

    private /*** string[] ***/ $_mMileStone = array();

    private /*** XoopsModule ***/ $_mCurrentXoopsModule = null;

    private /*** XoopsModule ***/ $_mTargetXoopsModule = null;

    private /*** int ***/ $_mCurrentVersion = 0;

    private /*** int ***/ $_mTargetVersion = 0;

    private /*** bool ***/ $_mForceMode = false;

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function __construct()
    {
        $this->mLog = new Legacy_ModuleInstallLog();
    }

    /**
     * setForceMode
     * 
     * @param   bool  $isForceMode
     * 
     * @return  void
    **/
    public function setForceMode(/*** bool ***/ $isForceMode)
    {
        $this->_mForceMode = $isForceMode;
    }

    /**
     * setCurrentXoopsModule
     * 
     * @param   XoopsModule  &$module
     * 
     * @return  void
    **/
    public function setCurrentXoopsModule(/*** XoopsModule ***/ &$module)
    {
        $moduleHandler = Legroup_Utils::getXoopsHandler('module');
        $cloneModule =& $moduleHandler->create();
    
        $cloneModule->unsetNew();
        $cloneModule->set('mid',$module->get('mid'));
        $cloneModule->set('name',$module->get('name'));
        $cloneModule->set('version',$module->get('version'));
        $cloneModule->set('last_update',$module->get('last_update'));
        $cloneModule->set('weight',$module->get('weight'));
        $cloneModule->set('isactive',$module->get('isactive'));
        $cloneModule->set('dirname',$module->get('dirname'));
        $cloneModule->set('trust_dirname',$module->get('trust_dirname'));
        $cloneModule->set('role',$module->get('role'));
        $cloneModule->set('hasmain',$module->get('hasmain'));
        $cloneModule->set('hasadmin',$module->get('hasadmin'));
        $cloneModule->set('hasconfig',$module->get('hasconfig'));
    
        $this->_mCurrentXoopsModule =& $cloneModule;
        $this->_mCurrentVersion = $cloneModule->get('version');
    }

    /**
     * setTargetXoopsModule
     * 
     * @param   XoopsModule  &$module
     * 
     * @return  void
    **/
    public function setTargetXoopsModule(/*** XoopsModule ***/ &$module)
    {
        $this->_mTargetXoopsModule =& $module;
        $this->_mTargetVersion = $this->getTargetPhase();
    }

    /**
     * getCurrentVersion
     * 
     * @param   void
     * 
     * @return  int
    **/
    public function getCurrentVersion()
    {
        return (int)$this->_mCurrentVersion;
    }

    /**
     * getTargetPhase
     * 
     * @param   void
     * 
     * @return  int
    **/
    public function getTargetPhase()
    {
        ksort($this->_mMileStone);
    
        foreach($this->_mMileStone as $tVer => $tMethod)
        {
            if($tVer >= $this->getCurrentVersion())
            {
                return (int)$tVer;
            }
        }
    
        return $this->_mTargetXoopsModule->get('version');
    }

    /**
     * hasUpgradeMethod
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function hasUpgradeMethod()
    {
        ksort($this->_mMileStone);
    
        foreach($this->_mMileStone as $tVer => $tMethod)
        {
            if($tVer >= $this->getCurrentVersion() && is_callable(array($this,$tMethod)))
            {
                return true;
            }
        }
    
        return false;
    }

    /**
     * isLatestUpgrade
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function isLatestUpgrade()
    {
        return ($this->_mTargetXoopsModule->get('version') == $this->getTargetPhase());
    }

    /**
     * _updateModuleTemplates
     * 
     * @param   void
     * 
     * @return  void
    **/
    private function _updateModuleTemplates()
    {
        Legroup_InstallUtils::uninstallAllOfModuleTemplates($this->_mTargetXoopsModule,$this->mLog);
        Legroup_InstallUtils::installAllOfModuleTemplates($this->_mTargetXoopsModule,$this->mLog);
    }

    /**
     * _updateBlocks
     * 
     * @param   void
     * 
     * @return  void
    **/
    private function _updateBlocks()
    {
        Legroup_InstallUtils::smartUpdateAllOfBlocks($this->_mTargetXoopsModule,$this->mLog);
    }

    /**
     * _updatePreferences
     * 
     * @param   void
     * 
     * @return  void
    **/
    private function _updatePreferences()
    {
        Legroup_InstallUtils::smartUpdateAllOfConfigs($this->_mTargetXoopsModule,$this->mLog);
    }

    /**
     * executeUpgrade
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function executeUpgrade()
    {
        return ($this->hasUpgradeMethod() ?
            $this->_callUpgradeMethod() :
            $this->executeAutomaticUpgrade());
    }

    /**
     * _callUpgradeMethod
     * 
     * @param   void
     * 
     * @return  bool
    **/
    private function _callUpgradeMethod()
    {
        ksort($this->_mMileStone);
    
        foreach($this->_mMileStone as $tVer => $tMethod)
        {
            if($tVer >= $this->getCurrentVersion() && is_callable(array($this,$tMethod)))
            {
                return $this->$tMethod();
            }
        }
    
        return false;
    }

    /**
     * executeAutomaticUpgrade
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function executeAutomaticUpgrade()
    {
        $this->mLog->addReport(_MI_LEGROUP_INSTALL_MSG_UPDATE_STARTED);
    
        $this->_updateModuleTemplates();
        if(!$this->_mForceMode && $this->mLog->hasError())
        {
            $this->_processReport();
            return false;
        }
    
        $this->_updateBlocks();
        if(!$this->_mForceMode && $this->mLog->hasError())
        {
            $this->_processReport();
            return false;
        }
    
        $this->_updatePreferences();
        if(!$this->_mForceMode && $this->mLog->hasError())
        {
            $this->_processReport();
            return false;
        }
    
        $this->saveXoopsModule($this->_mTargetXoopsModule);
        if(!$this->_mForceMode && $this->mLog->hasError())
        {
            $this->_processReport();
            return false;
        }
    
        $this->_processReport();
    
        return true;
    }

    /**
     * saveXoopsModule
     * 
     * @param   XoopsModule  &$module
     * 
     * @return  void
    **/
    public function saveXoopsModule(/*** XoopsModule ***/ &$module)
    {
        $moduleHandler = Legroup_Utils::getXoopsHandler('module');
    
        if($moduleHandler->insert($module))
        {
            $this->mLog->addReport(_MI_LEGROUP_INSTALL_MSG_UPDATE_FINISHED);
        }
        else
        {
            $this->mLog->addError(_MI_LEGROUP_INSTALL_ERROR_UPDATE_FINISHED);
        }
    }

    /**
     * _processReport
     * 
     * @param   void
     * 
     * @return  void
    **/
    private function _processReport()
    {
        if(!$this->mLog->hasError())
        {
            $this->mLog->add(
                XCube_Utils::formatString(
                    _MI_LEGROUP_INSTALL_MSG_MODULE_UPDATED,
                    $this->_mCurrentXoopsModule->get('name')
                )
            );
        }
        else
        {
            $this->mLog->add(
                XCube_Utils::formatString(
                    _MI_LEGROUP_INSTALL_ERROR_MODULE_UPDATED,
                    $this->_mCurrentXoopsModule->get('name')
                )
            );
        }
    }
}
