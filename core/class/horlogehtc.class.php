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

	public static function cron30() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction cron30()');

		/** @var horlogehtc */
		foreach (eqLogic::byType(__CLASS__, true) as $horlogehtc) {
			$horlogehtc->refreshInformations();
		}
		log::add(__CLASS__, 'debug', 'Fin de la Fonction cron30()');
	}

	public function preUpdate() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction preUpdate()');

		if ($this->getConfiguration('coordonees') != '') {
			$this->setConfiguration('coordonees', str_replace(' ', '', $this->getConfiguration('coordonees', '')));
			log::add(__CLASS__, 'debug', 'Suppression des espaces des coordonees gps > ' . $this->getConfiguration('coordonees', ''));
		}
		if ($this->getConfiguration('apikey') != '') {
			$this->setConfiguration('apikey', str_replace(' ', '', $this->getConfiguration('apikey', '')));
			log::add(__CLASS__, 'debug', 'Suppression des espaces des apikey forecast.io > ' . $this->getConfiguration('apikey', ''));
		}

		log::add(__CLASS__, 'debug', 'Fin de la Fonction preUpdate()');
	}

	public function postUpdate() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction postUpdate()');
		if ($this->getConfiguration('MeteoOn', '0') == '0') {
			log::add(__CLASS__, 'debug', 'Horloge sans Météo');
			$this->refreshWidget();
		} else {
			log::add(__CLASS__, 'debug', 'Horloge avec Météo');

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'summary');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Condition', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('summary');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('string');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Condition (summary)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = horlogehtcCmd::byEqLogicIdAndLogicalId($this->getId(), 'icon');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Icone', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('icon');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('string');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Icone (icon)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'temperature');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Température', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('temperature');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('numeric');
				$horlogehtcCmd->setUnite('°C');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Température (temperature)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'humidity');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Humidité', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('humidity');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('numeric');
				$horlogehtcCmd->setUnite('%');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Humidité (humidity)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'windSpeed');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Vitesse du Vent', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('windSpeed');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('numeric');
				$horlogehtcCmd->setUnite('km/h');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Vitesse du Vent (windSpeed)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'pressure');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Pression', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('pressure');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('numeric');
				$horlogehtcCmd->setUnite('hPa');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Pression (pressure)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'sunriseTime');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Lever du Soleil', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('sunriseTime');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('numeric');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Lever du Soleil (sunriseTime)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('info', 'sunsetTime');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Coucher du Soleil', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('sunsetTime');
				$horlogehtcCmd->setType('info');
				$horlogehtcCmd->setSubType('numeric');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Coucher du Soleil (sunsetTime)');
			}

			/** @var horlogehtcCmd */
			$horlogehtcCmd = $this->getCmd('action', 'refresh');
			if (!is_object($horlogehtcCmd)) {
				$horlogehtcCmd = new horlogehtcCmd();
				$horlogehtcCmd->setName(__('Rafraichir', __FILE__));
				$horlogehtcCmd->setEqLogic_id($this->getId());
				$horlogehtcCmd->setLogicalId('refresh');
				$horlogehtcCmd->setType('action');
				$horlogehtcCmd->setSubType('other');
				$horlogehtcCmd->save();
				log::add(__CLASS__, 'debug', 'Création de la commande Rafraichir (refresh)');
			}

			$this->refreshInformations();
		}

		log::add(__CLASS__, 'debug', 'Fin de la Fonction postUpdate()');
	}

	private function getInfoFromForecastIo() {
		$coordonees = $this->getConfiguration('coordonees', '');
		$apikey = $this->getConfiguration('apikey', '');

		if ($coordonees == '' || $apikey == '') return false;

		$lang = explode('_', config::byKey('language'));
		$url = 'https://api.forecast.io/forecast/' . $apikey . '/' . $coordonees . '?units=ca&lang=' . $lang[0];
		log::add(__CLASS__, 'debug', 'Appel de l API > ' . $url);
		$json_string = file_get_contents($url);
		$parsed_json = json_decode($json_string, true);
		log::add(__CLASS__, 'debug', " Passage Mode DAILY");
		foreach ($parsed_json['daily']['data'][0] as $key => $value) {
			if ($key == 'sunsetTime' || $key == 'sunriseTime') {
				$value = date('Hi', $value);
				$this->checkAndUpdateCmd($key, $value);
			}
		}

		log::add(__CLASS__, 'debug', 'Passage Mode CURRENTLY');
		foreach ($parsed_json['currently'] as $key => $value) {
			if ($key == 'humidity') {
				$value = $value * 100;
			}
			$this->checkAndUpdateCmd($key, $value);
		}
		return true;
	}

	private static function mapWeatherConditionToIcon($conditionId, $isDay) {

		if (in_array($conditionId, array('771', '781'))) {
			return "wind";
		} else if (in_array($conditionId, array('800'))) {
			return $isDay ? 'clear-day' : 'clear-night';
		} else if (in_array($conditionId, array('801'))) {
			return $isDay ? 'partly-cloudy-day' : 'partly-cloudy-night';
		} else if (in_array($conditionId, array('802'))) {
			return 'cloudy';
		} else if (in_array($conditionId, array('511'))) {
			return 'hail';
		} else if (in_array($conditionId, array('611', '612', '613'))) {
			return 'sleet';
		}

		switch (substr($conditionId, 0, 1)) {
			case '2':
				return "thunderstorm";
			case '3':
				return "rain";
			case '5':
				return "rain";
			case '6':
				return "snow";
			case '7':
				return "fog";
			case '8':
				return "Couvert";
		}

		log::add(__CLASS__, 'warning', "no match for conditionId {$conditionId}");
		return "blank";
	}

	private function getInfoFromWeather() {

		$weatherEqLogicId = $this->getConfiguration('weatherEqLogic');
		if ($weatherEqLogicId == '') return false;

		$commandToUpdate = array(
			'summary' => 'condition',
			'sunriseTime' => 'sunrise',
			'sunsetTime' => 'sunset',
			'humidity' => 'humidity',
			'pressure' => 'pressure',
			'temperature' => 'temperature',
			'windSpeed' => 'wind_speed',
		);

		$sunrise = '400';
		$sunset = '2300';

		foreach ($commandToUpdate as $htcCmd => $weatherCmd) {
			$cmd = cmd::byEqLogicIdAndLogicalId($weatherEqLogicId, $weatherCmd);
			if (is_object($cmd)) {
				$cmdValue = $cmd->execCmd();
				$this->checkAndUpdateCmd($htcCmd, $cmdValue);
				if ($weatherCmd == 'sunrise') {
					$sunrise = $cmdValue;
				}
				if ($weatherCmd == 'sunset') {
					$sunset = $cmdValue;
				}
			}
		}

		$conditionIdCmd = cmd::byEqLogicIdAndLogicalId($weatherEqLogicId, 'condition_id');
		if (is_object($conditionIdCmd)) {
			$conditionId = $conditionIdCmd->execCmd();

			$hour = date('Hi');
			if ($hour >= $sunrise && $hour < $sunset) {
				$isDay = true;
			} else {
				$isDay = false;
			}

			$icon = self::mapWeatherConditionToIcon($conditionId, $isDay);
			$this->checkAndUpdateCmd('icon', $icon);
		}

		return true;
	}

	public function refreshInformations() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction refreshInformations()');

		if (!$this->getInfoFromWeather()) {
			$this->getInfoFromForecastIo();
		}

		$this->refreshWidget();
	}

	public function toHtml($_version = 'dashboard') {
		log::add(__CLASS__, 'debug', 'Start de la fonction toHtml()');

		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}

		if ($this->getConfiguration('temperaturelocal') == '') {
			$temperature = $this->getCmd(null, 'temperature');
			$replace['#temperature#'] = is_object($temperature) ? round($temperature->execCmd(), 1) : '';
		} else {
			$replace['#temperature#'] = jeedom::evaluateExpression($this->getConfiguration('temperaturelocal'));
		}

		if ($this->getConfiguration('humiditelocal') == '') {
			$humidity = $this->getCmd(null, 'humidity');
			$replace['#humidity#'] = is_object($humidity) ? $humidity->execCmd() : '';
		} else {
			$replace['#humidity#'] = jeedom::evaluateExpression($this->getConfiguration('humiditelocal'));
		}

		if ($this->getConfiguration('pressionlocal') == '') {
			$pressure = $this->getCmd(null, 'pressure');
			$replace['#pressure#'] = is_object($pressure) ? $pressure->execCmd() : '';
		} else {
			$replace['#pressure#'] = jeedom::evaluateExpression($this->getConfiguration('pressionlocal'));
		}

		$wind_speed = $this->getCmd(null, 'windSpeed');
		$replace['#windspeed#'] = is_object($wind_speed) ? $wind_speed->execCmd() : '';

		$sunrise = $this->getCmd(null, 'sunriseTime');
		$replace['#sunrise#'] = is_object($sunrise) ? substr_replace($sunrise->execCmd(), ':', -2, 0) : '';

		$sunset = $this->getCmd(null, 'sunsetTime');
		$replace['#sunset#'] = is_object($sunset) ? substr_replace($sunset->execCmd(), ':', -2, 0) : '';

		$refresh = $this->getCmd(null, 'refresh');
		$replace['#refresh_id#'] = is_object($refresh) ? $refresh->getId() : '';

		$condition = $this->getCmd(null, 'summary');
		if (is_object($condition)) {
			$replace['#condition#'] = $condition->execCmd();
			if ($this->getConfiguration('CollectDateIsVisible') != '1') {
				$replace['#collectDate#'] = '';
			} else {
				$replace['#collectDate#'] = 'Actualisé le ' . $condition->getCollectDate();
			}
		} else {
			$replace['#condition#'] = '';
			$replace['#collectDate#'] = '';
		}

		$icon = $this->getCmd(null, 'icon');
		$replace['#icon#'] = is_object($icon) ? $icon->execCmd() : '';

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		if ($this->getConfiguration('MeteoOn') != '1') {
			log::add(__CLASS__, 'debug', 'Horloge Sans Météo');
			$html = template_replace($replace, getTemplate('core', $version, 'sansmeteo', 'horlogehtc'));
			cache::set('horlogehtcWidget' . $_version . $this->getId(), $html, 0);
			return $html;
		} else {
			log::add(__CLASS__, 'debug', 'Horloge avec Météo');

			$html = template_replace($replace, getTemplate('core', $version, 'current', 'horlogehtc'));
			cache::set('horlogehtcWidget' . $_version . $this->getId(), $html, 0);
			return $html;
		}
		log::add(__CLASS__, 'debug', 'Fin de la fonction toHtml()');
	}
}

class horlogehtcCmd extends cmd {

	public function execute($_options = null) {
		if ($this->getLogicalId() == 'refresh') {
			/** @var horlogehtc */
			$eqLogic = $this->getEqLogic();
			$eqLogic->refreshInformations();
		}
	}
}
