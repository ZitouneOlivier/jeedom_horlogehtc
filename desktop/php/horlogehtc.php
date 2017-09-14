<?php

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'horlogehtc');
$eqLogics = eqLogic::byType('horlogehtc');

?>

<div class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une horloge}}</a>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
        foreach ($eqLogics as $eqLogic) {
          echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>

<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
	<legend><i class="fa fa-android"></i>  {{Mes horloges}}
	</legend>
	<div class="eqLogicThumbnailContainer">
		<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
			<center>
				<i class="fa fa-plus-circle" style="font-size : 7em;color:#00979c;"></i>
			</center>
			<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>Ajouter</center></span>
		</div>
	<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
		<center>
			<i class="fa fa-wrench" style="font-size : 7em;color:#767676;"></i>
		</center>
		<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
	</div>
</div>      

      <?php
      foreach ($eqLogics as $eqLogic) {
        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
        echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
        echo "<center>";
        echo '<img src="plugins/horlogehtc/doc/images/horlogehtc_icon.png" height="105" width="95" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
        echo '</div>';
      }
      ?>
    </div>
  </div>


  <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">

    <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
    <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

			    <ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
					<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
				</ul>
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <form class="form-horizontal">
          <fieldset>
            <legend>
				<i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i>  {{Général}}
				<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
            </legend>

            <div class="form-group">
              <label class="col-md-3 control-label">{{Nom de l'horloge}}</label>
              <div class="col-md-4">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'horloge}}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" >{{Objet parent}}</label>
              <div class="col-md-4">
                <select class="form-control eqLogicAttr" data-l1key="object_id">
                  <option value="">{{Aucun}}</option>
                  <?php
                  foreach (object::all() as $object) {
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
			  <label class="col-md-3 control-label" ></label>
              <div class="col-md-6">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
              </div>
			</div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{Commentaire}}</label>
              <div class="col-md-4">
                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
              </div>
            </div>

          </fieldset>

        </form>

        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fa fa-info-circle"></i>  {{Configuration Météo}}</legend>

			<div class="form-group">
				<label class="col-md-3 control-label">{{Heure de collecte}}</label>
					<div class="col-md-4">
					    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="CollectDateIsVisible" checked/>{{Afficher}}</label>
					</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label">{{Météo}}</label>
					<div class="col-md-4">
						<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="MeteoOn" checked/>{{Afficher}}</label>
					</div>
			</div>


            <div class="form-group">
              <label class="col-md-3 control-label">{{Coordonnées GPS}}</label>
              <div class="col-md-4">
                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="coordonees"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label">{{Clef API Forecast.io}}</label>
              <div class="col-md-4">
                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="apikey"/>
              </div>
            </div>

           	<div class="form-group">
			<label class="col-md-3 control-label">{{Température Local}}</label>
				<div class="col-md-4">
					<div class="input-group">
						<input type="text"  class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="temperaturelocal" />
						<span class="input-group-btn">
							<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectTemperature"><i class="fa fa-list-alt"></i></a>
						</span>
					</div>
				</div>
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="TemperatureIsLocal" checked/>{{Utiliser}}</label>
			</div>
			
			<div class="form-group">
			<label class="col-md-3 control-label">{{Pression Local}}</label>
				<div class="col-md-4">
					<div class="input-group">
						<input type="text"  class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="pressionlocal" />
						<span class="input-group-btn">
							<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectPression"><i class="fa fa-list-alt"></i></a>
						</span>
					</div>
				</div>
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="PressionIsLocal" checked/>{{Utiliser}}</label>
			</div>
			
			<div class="form-group">
			<label class="col-md-3 control-label">{{Humidite Local}}</label>
				<div class="col-md-4">
					<div class="input-group">
						<input type="text"  class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="humiditelocal" />
						<span class="input-group-btn">
							<a class="btn btn-default cursor" title="Rechercher une commande" id="bt_selectHumidite"><i class="fa fa-list-alt"></i></a>
						</span>
					</div>
				</div>
			<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="HumiditeIsLocal" checked/>{{Utiliser}}</label>
			</div>


          </fieldset>
        </form>
      </div>


	<div role="tabpanel" class="tab-pane" id="commandtab">
		<legend><i class="fa fa-cloud"></i>  {{Valeurs Actuelles}}</legend>
		<table id="table_cmd" class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th>{{Origine}}</th>
					<th>{{Nom}}</th>
					<th>{{Valeur}}</th>
					<th>{{Paramètres}}</th>
				 </tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

  </div>
</div>

<?php include_file('desktop', 'horlogehtc', 'js', 'horlogehtc'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
