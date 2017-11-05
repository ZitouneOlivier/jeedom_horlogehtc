
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
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
		$('.eqLogicAttr[data-l2key=temperaturelocal]').atCaret('insert', result.human);
    });
});

$('#bt_selectPression').on('click', function () {
		jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
			$('.eqLogicAttr[data-l2key=pressionlocal]').atCaret('insert', result.human);
		});
});

$('#bt_selectHumidite').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
		$('.eqLogicAttr[data-l2key=humiditelocal]').atCaret('insert', result.human);
    });
});


$("#table_cmd").delegate(".listEquipementInfo", 'click', function() {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
        atCaret('insert', result.human);
    });
});

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: false});


function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }

    if (init(_cmd.configuration.category) == 'actual') {
        var disabled = (init(_cmd.configuration.virtualAction) == '1') ? 'disabled' : '';
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
			tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
			tr += '<span class="cmdAttr" data-l1key="name"></span></td>';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="configuration" data-l2key="value"></span>';
			if (init(_cmd.subType) == "numeric") {
				tr += '<span class="cmdAttr" data-l1key="unite"></span> ';
			}
        tr += '</td>';
        tr += '<td>';
			if (init(_cmd.subType) == "numeric") {
				//tr += '<span><label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="Historiser" data-l2key="isHistorized" checked/>{{Historiser}}</label></span> ';
			}
        tr += '</td>';

        tr += '</tr>';
        $('#table_cmd tbody').append(tr);
        $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    }

    
}
