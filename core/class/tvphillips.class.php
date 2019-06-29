<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/tvphillips.inc.php';
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class tvphillips extends eqLogic {


    public static function pull() {
    
        foreach (eqLogic::byType('tvphillips') as $tvphillips) {
            $mc = cache::byKey('tvphillipsWidgetmobile' . $tvphillips->getId());
            $mc->remove();
            $mc = cache::byKey('tvphillipsWidgetdashboard' . $tvphillips->getId());
            $mc->remove();
            $tvphillips->toHtml('mobile');
            $tvphillips->toHtml('dashboard');
            $tvphillips->refreshWidget();
        }
	}
   
    public function toHtml($_version) 
	{
		$_version = jeedom::versionAlias($_version);
        $mc = cache::byKey('tvphillipsWidget' . $_version . $this->getId());
        if ($mc->getValue() != '') {
            return $mc->getValue();
        }
        
        $info = '';
        $action = '';
        
        if ($this->getIsEnable()) {
			$j=0;
            foreach ($this->getCmd() as $cmd) {
                $cmdtable[$cmd->getConfiguration('order')]=$cmd;  
				$j++;
            }
            for ($i=1;$i<=$j;$i++) {  
                
				
				if ($cmdtable[$i]->getIsVisible() == 1){
					if ($i<$j) {
						$action.=$cmdtable[$i]->toHtml(jeedom::versionAlias($_version), $this->getConfiguration('widgettelecommande'));
					}else {
						if ($this->getConfiguration('isWidgetSon')) {
							$action.=$cmdtable[$i]->toHtml(jeedom::versionAlias($_version), $this->getConfiguration('widgettelecommande'));
						}
					}
				}
				if (($this->getConfiguration('widgettelecommande') ==  'remote2') && ($i==7)) {
					$action.='<span class="cmd" style="float:left; margin: 0 0 0 0;" data-type="action" data-subtype="other" data-cmd_id="#id#">';
					$action.='<img src="plugins/tvphillips/core/template/images/remote2/Guide.png" />';
					$action.='</span>';
				}
				if (($this->getConfiguration('widgettelecommande') ==  'remote2') && ($i==7)) {
					$action.='<span class="cmd" style="float:left; margin: 0 0 0 0;" data-type="action" data-subtype="other" data-cmd_id="#id#">';
					$action.='<img src="plugins/tvphillips/core/template/images/remote2/3D.png" />';
					$action.='</span>';
				}
				if (($this->getConfiguration('widgettelecommande') ==  'remote2') && ($i==12)) {
					$action.='<span class="cmd" style="float:left; margin: 0 0 0 0;" data-type="action" data-subtype="other" data-cmd_id="#id#">';
					$action.='<img src="plugins/tvphillips/core/template/images/remote2/SmartTV.png" />';
					$action.='</span>';
				}
				if (($this->getConfiguration('widgettelecommande') ==  'remote2') && ($i==12)) {
					$action.='<span class="cmd" style="float:left; margin: 0 0 0 0;" data-type="action" data-subtype="other" data-cmd_id="#id#">';
					$action.='<img src="plugins/tvphillips/core/template/images/remote2/List.png" />';
					$action.='</span>';
				}
				if (($this->getConfiguration('widgettelecommande') ==  'remote2') && ($i==43)) {
					$action.='<span class="cmd" style="float:left; margin: 0 0 0 0;" data-type="action" data-subtype="other" data-cmd_id="#id#">';
					$action.='<img src="plugins/tvphillips/core/template/images/remote2/PhillipsBottom.png" />';
					$action.='</span>';
				}
				if (($this->getConfiguration('widgettelecommande') ==  'remote3') && ($i==43)) {
					$action.='<span class="cmd" style="float:left; margin: 0 0 0 0;" data-type="action" data-subtype="other" data-cmd_id="#id#">';
					$action.='<img src="plugins/tvphillips/core/template/images/remote3/PhillipsBottom.png" />';
					$action.='</span>';
				}
				
            }
        }
        $object = $this->getObject();
/*        
		if ($this->getConfiguration('isWidgetson')) {
			$widget_son = "<input class="eqLogicAttr form-control" data-l1key='configuration' data-l2key='IPaddress' />";
		
		}
		else {
			$widget_son = "";
		}
 */       
		$replace = array(
            '#id#' => $this->getId(),
            '#name#' => ($this->getIsEnable()) ? $this->getName() : '<del>' . $this->getName() . '</del>',
            '#eqLink#' => $this->getLinkToConfiguration(),
            '#action#' => (isset($action)) ? $action : '',
            '#object_name#' => (is_object($object)) ? $object->getName() . ' - ' : '',
            '#background_color#' => $this->getBackgroundColor(jeedom::versionAlias($_version)),
//			'#widget_son#' => $widget_son,
        );
		
        if ($_version == 'dview') {
            $object = $this->getObject();
            $replace['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace['#name#'] : $replace['#name#'];
        }
        if ($_version == 'mview') {
            $object = $this->getObject();
            $replace['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace['#name#'] : $replace['#name#'];
        }

        
        if (($_version == 'dashboard') && ($this->getConfiguration('widgettelecommande') !=  'name')) {
			if ($this->getConfiguration('widgettelecommande') ==  'remote1')
				return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'tvphillips_remote1', 'tvphillips'));
			if ($this->getConfiguration('widgettelecommande') ==  'remote2')
				return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'tvphillips_remote2', 'tvphillips'));
			if ($this->getConfiguration('widgettelecommande') ==  'remote3')
				return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'tvphillips_remote3', 'tvphillips'));
       }
        else {
            return template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'tvphillips_simple', 'tvphillips'));
        }
        
        
        cache::set('tvphillipsWidget' . $_version . $this->getId(), $html, 0);
        return $html;
	}
     
    public function preUpdate() {
        if ($this->getConfiguration('IPaddress') == '') {
            throw new Exception(__('L\'adresse IP de la TV ne peut pas être vide',__FILE__));
        }
        
        
 }
    
    public function preSave() {
        /*$j=1;
        foreach ($this->getCmd() as $cmd) {
            $ordertable[$j]=$cmd->getConfiguration('order');  
            if ($ordertable[$j]< 1 || $ordertable[$j] > 47) {
                throw new Exception(__('Le tableau des ordres contient une valeur en dehors du range [1:47]',__FILE__));
            }
            $j++;
        }
        $array_unique = array_unique($ordertable);
        if (count($ordertable) - count($array_unique)) {
            throw new Exception(__('Le tableau des ordres contient des doublons',__FILE__));
        }
        
		*/
		  
    }
	
	
    public function postInsert() {
    
        //Activation par default du widget télécommande
        $this->setConfiguration('widgettelecommande', 'remote1');
    
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('OFF', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Standby');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 1);
		$tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Back', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Back');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 22);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Find', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Find');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 11);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Red', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'RedColour');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 7);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Green', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'GreenColour');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 8);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Yellow', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'YellowColour');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 9);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Blue', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'BlueColour');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 10);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Home', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Home');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 12);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('VolumeUp', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'VolumeUp');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 26);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('VolumeDown', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'VolumeDown');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 24);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Mute', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Mute');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 25);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Options', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Options');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 23);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Dot', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Dot');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 39);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('0', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit0');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 37);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('1', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit1');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 27);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('2', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit2');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 28);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('3', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit3');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 29);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('4', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit4');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 30);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('5', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit5');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 31);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('6', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit6');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 32);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('7', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit7');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 33);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('8', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit8');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 34);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('9', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Digit9');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 35);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Info', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Info');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 40);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('CursorUp', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'CursorUp');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 14);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('CursorDown', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'CursorDown');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 20);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('CursorLeft', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'CursorLeft');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 16);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('CursorRight', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'CursorRight');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 18);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Confirm', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Confirm');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 17);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Next', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Next');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 41);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Previous', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Previous');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 42);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Adjust', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Adjust');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 13);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('WatchTV', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'WatchTV');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 43);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Viewmode', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Viewmode');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 44);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Teletext', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Teletext');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 38);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Subtitle', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Subtitle');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 36);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('ChannelStepUp', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'ChannelStepUp');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 19);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('ChannelStepDown', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'ChannelStepDown');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 15);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Source', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Source');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 21);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('AmbilightOnOff', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'AmbilightOnOff');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 45);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('PlayPause', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'PlayPause');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 5);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Pause', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Pause');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 3);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('FastForward', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'FastForward');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 6);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Stop', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Stop');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 2);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Rewind', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Rewind');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
		$tvPhillipsCmd->setConfiguration('order', 4);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Record', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Record');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 46);
        $tvPhillipsCmd->save();
        
        $tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Online', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'Online');
		$tvPhillipsCmd->setConfiguration('ApiType', 'key');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setIsVisible(false);
		$tvPhillipsCmd->setConfiguration('order', 47);
        $tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('HDMI 1', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'hdmi1');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 48);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('HDMI 2', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'hdmi2');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 49);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('HDMI 3', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'hdmi3');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 50);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('HDMI Side', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'hdmiside');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 51);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('TV', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'tv');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 52);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Satellite', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'satellite');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 53);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('EXT 1', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'ext1');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 54);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('EXT 2', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'ext2');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 55);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Y Pb Pr', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'ypbpr');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 56);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('VGA', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('key_data', 'vga');
		$tvPhillipsCmd->setConfiguration('ApiType', 'sources');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 57);
		$tvPhillipsCmd->save();
		
		$tvPhillipsCmd = new tvphillipsCmd();
        $tvPhillipsCmd->setName(__('Z_WidgetVolume', __FILE__));
        $tvPhillipsCmd->setEqLogic_id($this->id);
		$tvPhillipsCmd->setConfiguration('ApiType', 'volume');
		$tvPhillipsCmd->setConfiguration('key_data', 'WidgetVolume');
        $tvPhillipsCmd->setType('action');
        $tvPhillipsCmd->setSubType('other');
        $tvPhillipsCmd->setConfiguration('order', 58);
		$tvPhillipsCmd->save();
        
    }

}

class tvphillipsCmd extends cmd {



    public function preSave() {
		if ($this->getConfiguration('key_data') == '') {
            throw new Exception(__('La clé (key) ne peut pas être vide',__FILE__));
        }
    }
    
    public function toHtml($_version = 'dashboard', $options = '', $_cmdColor = null, $_cache = 2) {
        if ($this->getType() == 'info') {
            return parent::toHtml($_version, $options);
        }
        if ($options == 'name') {
            $name_new = $this->getName();
        }else {
            $name_new = $this->getConfiguration('key_data');
        }
		
		if ($this->getConfiguration('key_data') == 'WidgetVolume') {
			if ($options == 'remote1')
				$width_son = '300px';
			if ($options == 'remote2')
				$width_son = '150px';
			if ($options == 'name')
				$width_son = '150px';
			$replace = array(
				'#id#' => $this->getId(),
				'#width#' => $width_son,
			);
        
			$html = template_replace($replace, getTemplate('core', $_version, 'cmd_son', 'tvphillips'));		
		}
		else 
		{
			$replace = array(
				'#id#' => $this->getId(),
				'#name#' => $name_new,
				'#remote#' => $options
			);
        
			if (($_version == 'dashboard') && ($options !=  'name')) {
				$html = template_replace($replace, getTemplate('core', $_version, 'cmd', 'tvphillips'));
			}
			else {
				$html = template_replace($replace, getTemplate('core', $_version, 'cmd_simple', 'tvphillips'));
			}
		}
        return $html;
    }

    public function execute($_options = null) {
	
		$eqLogic   = $this->getEqLogic();
		$IPaddress = $eqLogic->getConfiguration('IPaddress');
		$key_data  = $this->getConfiguration('key_data');
		$api_type  = $this->getConfiguration('ApiType');

		$request1 = "curl -X POST http://";
		$request4 = '"}';
		$request5 = "'";
		
		switch($api_type) {
			case 'key':
				$request2 = ":1925/1/input/key -d '{";
				$request3 = '"key":"';
			break;
			case 'volume':
				if ($_options !== null && $_options !== '') {
					$options = self::cmdToValue($_options);
					if (is_json($_options)) {
						$options = json_decode($_options, true);
					}
				} else {
					$options = null;
				}
				if (isset($options['volume'])) {
					$request2 = ":1925/1/audio/volume -d '{";
					$request3 = '"muted": false,"current":"';
					$key_data = $options['volume'];
				}
			break;
			
			case 'sources':
				$request2 = ":1925/1/sources/current -d '{";
				$request3 = '"id":"';
			break;
			default:
			break;
		}
		
		
		
		
		$request = $request1.$IPaddress.$request2.$request3.$key_data.$request4.$request5;
        $request_shell = new com_shell($request . ' 2>&1');
        $result = trim($request_shell->exec());
		return $result;
    }

}
