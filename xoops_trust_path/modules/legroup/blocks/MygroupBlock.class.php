<?php
/**
 * @file
 * @package legroup
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit();
}

/**
 * Legroup_ListBlock
**/
class Legroup_MygroupBlock extends Legacy_BlockProcedure
{
    /**
     * @var Legroup_CatHandler
     * 
     * @private
    **/
    var $_mHandler = null;
    
    /**
     * @var Legroup_CatObject
     * 
     * @private
    **/
    var $_mObject = null;
    
    /**
     * @var string[]
     * 
     * @private
    **/
    var $_mOptions = array();
    
    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @public
    **/
    public function prepare()
    {
        return parent::prepare() && $this->_parseOptions() && $this->_setupObject();
    }
    
    /**
     * _parseOptions
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    protected function _parseOptions()
    {
        $opts = explode('|',$this->_mBlock->get('options'));
        $this->_mOptions = array(
            'rank'	=> (intval($opts[0])>0 ? intval($opts[0]) : 0),
        );
        return true;
    }
    
    /**
     * getBlockOption
     * 
     * @param   string  $key
     * 
     * @return  string
     * 
     * @public
    **/
    public function getBlockOption($key)
    {
        return isset($this->_mOptions[$key]) ? $this->_mOptions[$key] : null;
    }
    
    /**
     * getOptionForm
     * 
     * @param   void
     * 
     * @return  string
     * 
     * @public
    **/
    public function getOptionForm()
    {
        if(!$this->prepare())
        {
            return null;
        }
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_rank">'._AD_LEGROUP_LANG_RANK_FILTER.'</label>&nbsp;:
		<input type="text" size="5" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_rank" value="'.$this->getBlockOption('rank').'" />';
		return $form;
    }

    /**
     * _setupObject
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    protected function _setupObject()
    {
        //get module asset for handlers
        $asset = null;
        XCube_DelegateUtils::call(
            'Module.legroup.Global.Event.GetAssetManager',
            new XCube_Ref($asset),
            $this->_mBlock->get('dirname')
        );
    
        $this->_mHandler = $asset->getObject('handler','group');
        $mHandler = $asset->getObject('handler', 'member');
        $this->_mObject = $this->_mHandler->getGroupListByIds($mHandler->getMyGroupIdList());

        return true;
    }

    /**
     * execute
     * 
     * @param   void
     * 
     * @return  void
     * 
     * @public
    **/
    function execute()
    {
        $root = XCube_Root::getSingleton();
    
        $render = $this->getRenderTarget();
        $render->setTemplateName($this->_mBlock->get('template'));
        $render->setAttribute('block', $this->_mObject);
        $render->setAttribute('dirname', $this->_mBlock->get('dirname'));
        $renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
        $renderSystem->renderBlock($render);
    }
}

