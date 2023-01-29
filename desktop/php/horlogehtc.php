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
				<br />
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br />
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fab fa-android"></i> {{Mes horloges}}</legend>
		<?php
		if (count($eqLogics) == 0) {
			echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement Template trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
		} else {
			// Champ de recherche
			echo '<div class="input-group" style="margin:5px;">';
			echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			echo '</div>';
			echo '</div>';
			// Liste des équipements du plugin
			echo '<div class="eqLogicThumbnailContainer">';
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '">';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '<span class="hiddenAsCard displayTableRight hidden">';
				echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
				echo '</span>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs"> {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i><span class="hidden-xs"> {{Supprimer}}</span>
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation" id="commandes"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Nom de l'horloge}}</label>
								<div class="col-md-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'horloge}}" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Objet parent}}</label>
								<div class="col-md-6">
									<select class="form-control eqLogicAttr" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										$options = '';
										foreach ((jeeObject::buildTree(null, false)) as $object) {
											$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
										}
										echo $options;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Catégorie}}</label>
								<div class="col-md-8">
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
								<label class="col-md-4 control-label"></label>
								<div class="col-md-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked />{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked />{{Visible}}</label>
								</div>
							</div>
							<legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Heure de collecte}}</label>
								<div class="col-md-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="CollectDateIsVisible" checked />{{Afficher}}</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Météo}}</label>
								<div class="col-md-4">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="MeteoOn" checked />{{Afficher}}</label>
								</div>
							</div>
							<br />
							<div class="form-group">
								<label class="col-md-4 control-label">{{Coordonnées GPS}}</label>
								<div class="col-md-6">
									<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="coordonees" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Clef API Forecast.io}}</label>
								<div class="col-md-6">
									<input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="apikey" />
								</div>
							</div>
							<br />
							<?php
							if (class_exists('weather')) {
								$weatherEqLogics = eqLogic::bytype('weather', true);
								if (is_array($weatherEqLogics) && count($weatherEqLogics) > 0) {
							?>
									<div class="form-group">
										<label class="col-sm-4 control-label">{{Equipement météo}}</label>
										<div class="col-sm-6">
											<select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="weatherEqLogic">
												<option value="">{{Aucun (utiliser Forecast.io)}}</option>
												<?php
												foreach ($weatherEqLogics as $eqLogic) {
													echo '<option value="' . $eqLogic->getId() . '">' . $eqLogic->getName() . '</option>';
												}
												?>
											</select>
										</div>
									</div>
									<br />
							<?php
								}
							}
							?>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Température locale}}</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="temperaturelocal" />
										<span class="input-group-btn">
											<a class="btn btn-default cursor roundedRight" title="Rechercher une commande" id="bt_selectTemperature"><i class="fas fa-list-alt"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Pression locale}}</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="pressionlocal" />
										<span class="input-group-btn">
											<a class="btn btn-default cursor roundedRight" title="Rechercher une commande" id="bt_selectPression"><i class="fas fa-list-alt"></i></a>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">{{Humidité locale}}</label>
								<div class="col-md-6">
									<div class="input-group">
										<input type="text" class="eqLogicAttr form-control roundedLeft" data-l1key="configuration" data-l2key="humiditelocal" />
										<span class="input-group-btn">
											<a class="btn btn-default cursor roundedRight" title="Rechercher une commande" id="bt_selectHumidite"><i class="fas fa-list-alt"></i></a>
										</span>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<div class="table-responsive">
					<table id="table_cmd" class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th style="min-width:220px;width:350px;">{{Nom}}</th>
								<th style="min-width:140px;width:200px;">{{Type}}</th>
								<th style="min-width:260px;">{{Options}}</th>
								<th>{{Etat}}</th>
								<th style="min-width:80px;width:140px;">{{Actions}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_file('desktop', 'horlogehtc', 'js', 'horlogehtc'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>