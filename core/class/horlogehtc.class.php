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

	public static $_weatherConditions = array(
		'blank' => 'Défaut',
		'cloudy' => 'Légèrement nuageux',
		'fog' => 'Brouillard',
		'cloud' => 'Nuageux',
		'thunderstorm' => 'Orage',
		'rain' => 'Pluie',
		'clear' => 'Ensoleillé',
		'partly-cloudy' => 'Partiellement ensoleillé',
		'wind' => 'Venteux',
		'hail' => 'Grêle',
		'sleet' => 'Neige fondante',
		'snow' => 'Neige'

	);

	private function getCurrentWeatherCondition() {
		asort(horlogehtc::$_weatherConditions);
		foreach (self::$_weatherConditions as $key => $desc) {

			if (jeedom::evaluateExpression($this->getConfiguration("condition_{$key}"))) {
				return $key;
			}
			log::add(__CLASS__, 'debug', "Condition for {$key} is false");
		}
		return "blank";
	}

	public static function cron30() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction cron30()');

		/** @var horlogehtc */
		foreach (eqLogic::byType(__CLASS__, true) as $horlogehtc) {
			$horlogehtc->refreshInformations();
		}
		log::add(__CLASS__, 'debug', 'Fin de la Fonction cron30()');
	}

	public function postUpdate() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction postUpdate()');


		/** @var horlogehtcCmd */
		$horlogehtcCmd = $this->getCmd('info', 'summary');
		if (!is_object($horlogehtcCmd)) {
			$horlogehtcCmd = new horlogehtcCmd();
			$horlogehtcCmd->setName(__('Résumé', __FILE__));
			$horlogehtcCmd->setEqLogic_id($this->getId());
			$horlogehtcCmd->setLogicalId('summary');
			$horlogehtcCmd->setType('info');
			$horlogehtcCmd->setSubType('string');
			$horlogehtcCmd->save();
			log::add(__CLASS__, 'debug', 'Création de la commande Résumé (summary)');
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

		log::add(__CLASS__, 'debug', 'Fin de la Fonction postUpdate()');
	}

	public function refreshInformations() {
		log::add(__CLASS__, 'debug', 'Start de la Fonction refreshInformations');

		$commandsToUpdate = array(
			'summary',
			'sunriseTime',
			'sunsetTime',
			'humidity',
			'pressure',
			'temperature',
			'windSpeed',
		);

		$sunrise = '400';
		$sunset = '2300';

		foreach ($commandsToUpdate as $cmdLogicalId) {
			$cmd = $this->getCmd('info', $cmdLogicalId);
			if (is_object($cmd)) {
				$cmdValue = jeedom::evaluateExpression($cmd->getConfiguration("infoValue"));
				$this->checkAndUpdateCmd($cmd, $cmdValue);
				if ($cmdLogicalId == 'sunriseTime') {
					$sunrise = $cmdValue;
				}
				if ($cmdLogicalId == 'sunsetTime') {
					$sunset = $cmdValue;
				}
			}
		}

		$condition = $this->getCurrentWeatherCondition();
		log::add(__CLASS__, 'debug', "condition: {$condition}");
		$icon = self::mapConditionToIcon($condition, self::isDay($sunrise, $sunset));
		$this->checkAndUpdateCmd('icon', $icon);

		$this->refreshWidget();
	}

	private static function isDay($sunrise, $sunset) {
		$hour = date('Hi');
		return ($hour >= $sunrise && $hour < $sunset);
	}

	public static function mapConditionToIcon(string $condition, bool $isDay) {
		if (!in_array($condition, ['clear', 'partly-cloudy'])) return $condition;

		return $isDay ? $condition . '-day' : $condition . '-night';
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
