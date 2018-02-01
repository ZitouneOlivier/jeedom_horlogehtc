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

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class horlogehtc extends eqLogic {

  public static $_widgetPossibility = array('custom' => true);

  public static function cron30($_eqLogic_id = null) {
    log::add('horlogehtc', 'debug', 'Start de la Fonction cron30()');
    if ($_eqLogic_id == null) {
      $eqLogics = self::byType('horlogehtc', true);
    } else {
      $eqLogics = array(self::byId($_eqLogic_id));
    }
    foreach ($eqLogics as $horlogehtc) {

      if (null !== ($horlogehtc->getConfiguration('coordonees', '')) and null !== ($horlogehtc->getConfiguration('apikey', ''))) {
        log::add('horlogehtc', 'debug', 'Appel de getInformations() depuis cron30');
		$horlogehtc->getInformations();
      } else {
        log::add('horlogehtc', 'error', 'Pas Appel de getInformations() depuis cron30 > Coordonées ou ApiKey non saisie');
      }
    }
    log::add('horlogehtc', 'debug', 'Fin de la Fonction cron30()');
  }
  public function preUpdate() {
    log::add('horlogehtc', 'debug', 'Start de la Fonction preUpdate()');
		if ($this->getConfiguration('MeteoOn') != '1') {
		    log::add('horlogehtc', 'debug', 'Horloge Sans Météo');
		} else {
		    log::add('horlogehtc', 'debug', 'Horloge avec Météo');
				if ($this->getConfiguration('coordonees') == '') {
					throw new Exception(__('La localisation ne peut etre vide',__FILE__));
				} else {
						$this->setConfiguration('coordonees',str_replace(' ','',$this->getConfiguration('coordonees', '')));
						log::add('horlogehtc', 'debug', 'Suppression des espaces des coordonees gps > ' . $this->getConfiguration('coordonees', ''));
				}
				if ($this->getConfiguration('apikey') == '') {
					throw new Exception(__('La clef API ne peut etre vide',__FILE__));
				} else {
						$this->setConfiguration('apikey',str_replace(' ','',$this->getConfiguration('apikey', '')));
						log::add('horlogehtc', 'debug', 'Suppression des espaces des apikey forecast.io > ' . $this->getConfiguration('apikey', ''));
				}
				if ($this->getConfiguration('PressionIsLocal') != '1') {
					log::add('horlogehtc', 'debug', 'Pas de Pression Local');
				} else {
					log::add('horlogehtc', 'debug', 'Utilise la Pression Local');
				}

				if ($this->getConfiguration('TemperatureIsLocal') == '1') {
					log::add('horlogehtc', 'debug', 'Temperature Local');
				}
				if ($this->getConfiguration('HumiditeIsLocal') == '1') {
					log::add('horlogehtc', 'debug', 'Humidite Local');
				}

		}
    log::add('horlogehtc', 'debug', 'Fin de la Fonction preUpdate()');
  }

  public function postUpdate() {
	log::add('horlogehtc', 'debug', 'Start de la Fonction postUpdate()');
		if ($this->getConfiguration('MeteoOn') != '1') {
		    log::add('horlogehtc', 'debug', 'Horloge Sans Météo');
		} else {
		    log::add('horlogehtc', 'debug', 'Horloge avec Météo');
		
			$horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'summary');
				if (!is_object($horlogehtcCmd)) {
					$horlogehtcCmd = new horlogehtcCmd();
					$horlogehtcCmd->setName(__('Condition', __FILE__));
					$horlogehtcCmd->setEqLogic_id($this->getId());
					$horlogehtcCmd->setLogicalId('summary');
					$horlogehtcCmd->setType('info');
					$horlogehtcCmd->setSubType('string');
					log::add('horlogehtc', 'debug', 'Création de objet Condition (LogicalId summary)');
				} else {
					log::add('horlogehtc', 'debug', 'Objet Condition (LogicalId summary) existe deja > pas de création');
				}
				$horlogehtcCmd->setConfiguration('category','actual');
				$horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'icon');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Icone', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('icon');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('string');
	  log::add('horlogehtc', 'debug', 'Création de objet Icone (LogicalId icon)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Icone (LogicalId icon) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'temperature');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Température', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('temperature');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('numeric');
	  log::add('horlogehtc', 'debug', 'Création de objet Température (LogicalId temperature)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Température (LogicalId temperature) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->setUnite( '°C' );
    $horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'humidity');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Humidité', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('humidity');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('numeric');
	  log::add('horlogehtc', 'debug', 'Création de objet Humidité (LogicalId humidity)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Humidité (LogicalId humidity) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->setUnite( '%' );
    $horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'windSpeed');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Vitesse du Vent', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('windSpeed');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('numeric');
	  log::add('horlogehtc', 'debug', 'Création de objet Vitesse du Vent (LogicalId windSpeed)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Vitesse du Vent (LogicalId windSpeed) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->setUnite( 'km/h' );
    $horlogehtcCmd->save();


    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'pressure');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Pression', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('pressure');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('numeric');
	  log::add('horlogehtc', 'debug', 'Création de objet Pression (LogicalId pressure)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Pression (LogicalId pressure) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->setUnite( 'hPa' );
    $horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'sunriseTime');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Lever du Soleil', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('sunriseTime');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('numeric');
	  log::add('horlogehtc', 'debug', 'Création de objet Lever du Soleil (LogicalId sunriseTime)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Lever du Soleil (LogicalId sunriseTime) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'sunsetTime');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Coucher du Soleil', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('sunsetTime');
      $horlogehtcCmd->setType('info');
      $horlogehtcCmd->setSubType('numeric');
	  log::add('horlogehtc', 'debug', 'Création de objet Coucher du Soleil (LogicalId sunsetTime)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Coucher du Soleil (LogicalId sunsetTime) existe deja > pas de création');
	}
	$horlogehtcCmd->setConfiguration('category','actual');
    $horlogehtcCmd->save();

    $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),'refresh');
    if (!is_object($horlogehtcCmd)) {
      $horlogehtcCmd = new horlogehtcCmd();
      $horlogehtcCmd->setName(__('Rafraichir', __FILE__));
      $horlogehtcCmd->setEqLogic_id($this->getId());
      $horlogehtcCmd->setLogicalId('refresh');
      $horlogehtcCmd->setType('action');
      $horlogehtcCmd->setSubType('other');
      $horlogehtcCmd->save();
	  log::add('horlogehtc', 'debug', 'Création de objet Rafraichir (LogicalId refresh)');
    } else {
	  log::add('horlogehtc', 'debug', 'Objet Rafraichir (LogicalId refresh) existe deja > pas de création');
	}
	
		if (null !== ($this->getConfiguration('coordonees', '')) && $this->getConfiguration('apikey', '') != 'none') {
			log::add('horlogehtc', 'debug', ' Apikey et coordonnees passés donc Appel de getInformation()');
			horlogehtc::getInformations();
		} else {
			log::add('horlogehtc', 'error', 'Appel de getInformation impossible coordonees et/ou ApiKey vide');
		}
	}

	log::add('horlogehtc', 'debug', 'Fin de la Fonction postUpdate()');
  }

  public function getInformations() {
	log::add('horlogehtc', 'debug','Start de la Fonction getInformations()');

    $coordonees = $this->getConfiguration('coordonees', '');
    $apikey = $this->getConfiguration('apikey', '');
    $lang = explode('_',config::byKey('language'));
    $url = 'https://api.forecast.io/forecast/' . $apikey .'/' . $coordonees . '?units=ca&lang=' . $lang[0];
	log::add('horlogehtc', 'debug','Appel de l API > '. $url);
    $json_string = file_get_contents($url);
    $parsed_json = json_decode($json_string, true);
	log::add('horlogehtc', 'debug'," Passage Mode DAILY");
	foreach ($parsed_json['daily']['data'][0] as $key => $value) {
        //log::add('horlogehtc', 'debug'," Recup Mode DAILY > ". $key . ' >  ' . $value);
        if ($key == 'sunsetTime' || $key == 'sunriseTime') {
			$horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),$key);
				if (is_object($horlogehtcCmd)) {
					$value = date('Hi',$value);
					$horlogehtcCmd->setConfiguration('value',$value);
					log::add('horlogehtc', 'debug'," Enregistrement Mode DAILY > ". $key . ' = ' . $value);
					$horlogehtcCmd->save();
					 $horlogehtcCmd->event($value);
				}
		}
	}

    log::add('horlogehtc', 'debug','Passage en Mode CURRENTLY');
	foreach ($parsed_json['currently'] as $key => $value) {
        //log::add('horlogehtc', 'debug',' Recup Mode CURRENTLY > '. $key . ' > ' . $value);
        if ($key != 'time') {
          $horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(),$key);
			if (is_object($horlogehtcCmd)) {
					if ($key == 'humidity') {
						$value = $value * 100;
					}
					//if ($key == 'windSpeed') {
					//	$value = $value * 3.6;
					//}
				$horlogehtcCmd->setConfiguration('value',$value);
				log::add('horlogehtc', 'debug', '  Enregistrement Mode CURRENTLY > '. $key . ' = ' . $value);
				$horlogehtcCmd->save();
				$horlogehtcCmd->event($value);
			}
		}
	}
    log::add('horlogehtc', 'debug','Sortie du Mode CURRENTLY');
	$this->refreshWidget();
	log::add('horlogehtc', 'debug', 'Fin de la fonction getInformations()');
  }
 
  public function toHtml($_version = 'dashboard') {
  	log::add('horlogehtc', 'debug', 'Start de la fonction toHtml()');
	
    $replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
    $version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}

	if ($this->getConfiguration('TemperatureIsLocal') != '1') {
		$temperature = $this->getCmd(null, 'temperature');
		$replace['#temperature#'] = is_object($temperature) ? round($temperature->execCmd(),1) : '';
	} else {
		$temperature = jeedom::evaluateExpression($this->getConfiguration('temperaturelocal'));
		$replace['#temperature#'] = $temperature;
	}

	if ($this->getConfiguration('HumiditeIsLocal') != '1') {
	    $humidity = $this->getCmd(null, 'humidity');
		$replace['#humidity#'] = is_object($humidity) ? $humidity->execCmd() : '';
	} else {
		$humidity = jeedom::evaluateExpression($this->getConfiguration('humiditelocal'));
		$replace['#humidity#'] = $humidity;
	}
	if ($this->getConfiguration('PressionIsLocal') != '1') {
	    $pressure = $this->getCmd(null, 'pressure');
	    $replace['#pressure#'] = is_object($pressure) ? $pressure->execCmd() : '';
	} else {
		$pressure = jeedom::evaluateExpression($this->getConfiguration('pressionlocal'));
		$replace['#pressure#'] = $pressure;
	}

    $wind_speed = $this->getCmd(null, 'windSpeed');
    $replace['#windspeed#'] = is_object($wind_speed) ? $wind_speed->execCmd() : '';

    $sunrise = $this->getCmd(null, 'sunriseTime');
    $replace['#sunrise#'] = is_object($sunrise) ? substr_replace($sunrise->execCmd(),':',-2,0) : '';

    $sunset = $this->getCmd(null, 'sunsetTime');
    $replace['#sunset#'] = is_object($sunset) ? substr_replace($sunset->execCmd(),':',-2,0) : '';

    $refresh = $this->getCmd(null, 'refresh');
    $replace['#refresh_id#'] = is_object($refresh) ? $refresh->getId() : '';

    $condition = $this->getCmd(null, 'summary');
    $icone = $this->getCmd(null, 'icon');
		if (is_object($condition)) {
			$replace['#condition#'] = $condition->execCmd();
				if ($this->getConfiguration('CollectDateIsVisible') != '1') {
					$replace['#collectDate#'] = '';
				} else {
					$replace['#collectDate#'] = 'Actualisé le '.$condition->getCollectDate();
				}
		} else {
			$replace['#icone#'] = '';
			$replace['#condition#'] = '';
			$replace['#collectDate#'] = '';
		}

    $icone = $this->getCmd(null, 'icon');
    $replace['#icone#'] = is_object($icone) ? $icone->execCmd() : '';

    $parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		if ($this->getConfiguration('MeteoOn') != '1') {
		    log::add('horlogehtc', 'debug', 'Horloge Sans Météo');
			    $html = template_replace($replace, getTemplate('core', $version, 'sansmeteo', 'horlogehtc'));
			cache::set('horlogehtcWidget' . $_version . $this->getId(), $html, 0);
			return $html;

		} else {
		    log::add('horlogehtc', 'debug', 'Horloge avec Météo');

    $html = template_replace($replace, getTemplate('core', $version, 'current', 'horlogehtc'));
    cache::set('horlogehtcWidget' . $_version . $this->getId(), $html, 0);
    return $html;
		}
  	log::add('horlogehtc', 'debug', 'Fin de la fonction toHtml()');
  }

}

class horlogehtcCmd extends cmd {

  public function execute($_options = null) {
    if ($this->getLogicalId() == 'refresh') {
      $eqLogic = $this->getEqLogic();
      $eqLogic->getInformations();
    } else {
      return $this->getConfiguration('value');
    }
  }

}

?>
