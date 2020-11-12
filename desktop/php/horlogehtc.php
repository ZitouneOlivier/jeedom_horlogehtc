<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('horlogehtc');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoSecondary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fab fa-android"></i> {{Mes horloges}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $eqLogic->getImage() . '"/>';
				echo "<br>";
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
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
						<br/>
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
						<br/>
						<?php
						if (class_exists('weather')) {
							$weatherEqLogics = eqLogic::bytype('weather', true);
							if (is_array($weatherEqLogics) && count($weatherEqLogics)>0) {
							?>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Equipement météo}}</label>
									<div class="col-sm-3">
										<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="weatherEqLogic">
											<?php
												foreach ($weatherEqLogics as $eqLogic) {
													echo '<option value="' . $eqLogic->getId() . '">' . $eqLogic->getName() . '</option>';
												}
											?>
										</select>
									</div>
								</div>
								<br/>
							<?php
							}
						}
					?>
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
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th style="width: 100px;">{{Unité}}</th>
							<th style="width: 300px;">{{Options}}</th>
							<th style="width: 150px;">{{Actions}}</th>
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