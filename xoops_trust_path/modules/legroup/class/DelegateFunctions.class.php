<?php
/**
 * @package legroup
 */

if (!defined('XOOPS_ROOT_PATH')) exit();


class Legroup_ImageClientDelegate
{
	/**
	 * getClientList
	 *
	 * @param mixed[]	&$list
	 *  @list[]['dirname']
	 *  @list[]['dataname']
	 * @param string	$dirname
	 *
	 * @return	void
	 */ 
	public static function getClientList(/*** mixed[] ***/ &$list, /*** string ***/ $dirname)
	{
		$dirnames = Legacy_Utils::getDirnameListByTrustDirname('legroup');
	
		//don't call this method multiple times when site owner duplicate.
		static $isCalled = false;
		if($isCalled === true){
			return;
		}
	
		foreach($dirnames as $dir){
			$list[] = array('dirname'=>$dir, 'dataname'=>'group');
		}
	
		$isCalled = true;
	}
}

class Legroup_CoolUriDelegate
{
	/**
	 * getNormalUri
	 *
	 * @param string	$uri
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param int		$data_id
	 * @param string	$action
	 * @param string	$query
	 *
	 * @return	void
	 */ 
	public function getNormalUri(/*** string ***/ &$uri, /*** string ***/ $dirname, /*** string ***/ $dataname=null, /*** int ***/ $data_id=0, /*** string ***/ $action=null, /*** string ***/ $query=null)
	{
		$sUri = '/%s/index.php?action=%s%s';
		$lUri = '/%s/index.php?action=%s%s&%s=%d';
		$table = isset($dataname) ? $dataname : 'Group';
	
		$key = $table.'_id';
	
		if(isset($dataname)){
			if($data_id>0){
				if(isset($action)){
					$uri = sprintf($lUri, $dirname, ucfirst($dataname), ucfirst($action), $key, $data_id);
				}
				else{
					$uri = sprintf($lUri, $dirname, ucfirst($dataname), 'View', $key, $data_id);
				}
			}
			else{
				if(isset($action)){
					$uri = sprintf($sUri, $dirname, ucfirst($dataname), ucfirst($action));
				}
				else{
					$uri = sprintf($sUri, $dirname, ucfirst($dataname), 'List');
				}
			}
			$uri = isset($query) ? $uri.'&'.$query : $uri;
		}
		else{
			if($data_id>0){
				if(isset($action)){
					die();
				}
				else{
					$handler = Legacy_Utils::getModuleHandler($table, $dirname);
					$key = $handler->mPrimary;
					$uri = sprintf($lUri, $dirname, ucfirst($table).'View', ucfirst($action), $key, $data_id);
				}
				$uri = isset($query) ? $uri.'&'.$query : $uri;
			}
			else{
				if(isset($action)){
					die();
				}
				else{
					$uri = sprintf('/%s/', $dirname);
					$uri = isset($query) ? $uri.'index.php?'.$query : $uri;
				}
			}
		}
	}
}



?>
