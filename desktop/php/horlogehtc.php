<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('horlogehtc');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>

		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<i class="fas fa-plus-circle" style="font-size : 7em;color:#7f7f7f;"></i>
				<br />
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<i class="fas fa-wrench" style="font-size : 7em;color:#767676;"></i>
				<br />
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fab fa-android"></i> {{Mes horloges}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
				echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
				echo '<img src="' . $eqLogic->getImage() . '" height="105" width="95" style="max-height: 95px"/>';
				echo "<br>";
				echo '<span class="name" style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-success btn-sm eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation" id="commandes"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br />
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Nom de l'horloge}}</label>
							<div class="col-md-4">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'horloge}}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Objet parent}}</label>
							<div class="col-md-4">
								<select class="form-control eqLogicAttr" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (jeeObject::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Catégorie}}</label>
							<div class="col-md-6">
								<?php
								foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>';
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-6">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked />{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked />{{Visible}}</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Commentaire}}</label>
							<div class="col-md-4">
								<textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire"></textarea>
							</div>
						</div>
					</fieldset>
				</form>
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas fa-info-circle"></i> {{Configuration Météo}}</legend>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Heure de collecte}}</label>
							<div class="col-md-4">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="CollectDateIsVisible" checked />{{Afficher}}</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Météo}}</label>
							<div class="col-md-4">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="MeteoOn" checked />{{Afficher}}</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Coordonnées GPS}}</label>
							<div class="col-md-4">
								<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="coordonees" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Clef API Forecast.io}}</label>
							<div class="col-md-4">
								<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="apikey" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Température locale}}</label>
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="temperaturelocal" />
									<span class="input-group-btn">
										<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectTemperature"><i class="fas fa-list-alt"></i></a>
									</span>
								</div>
							</div>
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="TemperatureIsLocal" checked />{{Utiliser}}</label>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Pression locale}}</label>
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="pressionlocal" />
									<span class="input-group-btn">
										<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectPression"><i class="fas fa-list-alt"></i></a>
									</span>
								</div>
							</div>
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="PressionIsLocal" checked />{{Utiliser}}</label>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">{{Humidité locale}}</label>
							<div class="col-md-4">
								<div class="input-group">
									<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="humiditelocal" />
									<span class="input-group-btn">
										<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectHumidite"><i class="fas fa-list-alt"></i></a>
									</span>
								</div>
							</div>
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="HumiditeIsLocal" checked />{{Utiliser}}</label>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<legend><i class="fas fa-cloud"></i> {{Valeurs Actuelles}}</legend>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>ID</th>
							<th>{{Nom}}</th>
							<th>{{Valeur}}</th>
							<!--<th>{{Paramètres}}</th>-->
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include_file('desktop', 'horlogehtc', 'js', 'horlogehtc'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>