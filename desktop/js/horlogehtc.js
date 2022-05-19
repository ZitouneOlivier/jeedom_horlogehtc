
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

$('#bt_selectTemperature').on('click', function () {
    jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
        $('.eqLogicAttr[data-l2key=temperaturelocal]').atCaret('insert', result.human);
    });
});

$('#bt_selectPression').on('click', function () {
    jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
        $('.eqLogicAttr[data-l2key=pressionlocal]').atCaret('insert', result.human);
    });
});

$('#bt_selectHumidite').on('click', function () {
    jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
        $('.eqLogicAttr[data-l2key=humiditelocal]').atCaret('insert', result.human);
    });
});


$("#table_cmd").delegate(".listEquipementInfo", 'click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function (result) {
        atCaret('insert', result.human);
    });
});

$("#table_cmd").sortable({ axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: false });


function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = { configuration: {} };
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td style="min-width:280px;width:350px;">';
    tr += '<div class="row">';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="logicalId" style="display : none;">';
    tr += '<div class="col-xs-7">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 200px;" placeholder="{{Nom}}">';
    tr += '</div>';
    tr += '<div class="col-xs-5">';
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fas fa-flag"></i> Icône</a>';
    tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
    tr += '</div>';
    tr += '</div>';
    tr += '</td>';

    tr += '<td>';
    tr += '</td>';

    tr += '<td style="min-width:100px;width:140px;">';
    tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="' + init(_cmd.type) + '" disabled style="margin-bottom : 5px;" />';
    tr += '<input class="cmdAttr form-control type input-sm" data-l1key="subType" value="' + init(_cmd.subType) + '" disabled style="margin-bottom : 5px;" />';
    tr += '</td>';

    tr += '<td style="min-width:100px;width:140px;">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="margin-top : 5px;"> ';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="margin-top : 5px;">';
    tr += '</td>';

    tr += '<td style="min-width:100px;width:140px;">';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" />{{Afficher}}</label></span> ';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
    tr += '</td>';

    tr += '<td style="min-width:100px;width:140px;">';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
