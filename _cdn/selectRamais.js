$(document).ready(function () {
    //RAMAIS
    var ramal = $('.ramal').bootstrapDualListbox({
        nonSelectedListLabel: 'Lista de Ramais',
        selectedListLabel: 'Ramal(s) Selecionados',
        preserveSelectionOnMove: 'false',
        moveOnSelect: false,
        infoText: 'Total Itens: {0}',
        infoTextEmpty: 'Nenhum Item!',
        filterPlaceHolder: 'Localizar',
        filterTextClear: 'Mostrar todos',
//                nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
//                'refresh', true

    });
  
    //AGENTES
    var agents = $('.agents').bootstrapDualListbox({
        nonSelectedListLabel: 'Lista de agents',
        selectedListLabel: 'Agents(s) Selecionados',
        preserveSelectionOnMove: 'false',
        moveOnSelect: false,
        infoText: 'Total Itens: {0}',
        infoTextEmpty: 'Nenhum Item!',
        filterPlaceHolder: 'Localizar',
        filterTextClear: 'Mostrar todos',
//                nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
//                'refresh', true

    });


});