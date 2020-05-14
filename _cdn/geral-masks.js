//Função geral para elaborar marcaras inputs
jQuery(function ($) {
//$("#host").mask("999.999.999.999", {autoclear: false}); 
    $("#host").mask("9?99.9?99.9?99.9?99", {autoclear: false});
    $("#cpf").mask("999.999.999-99");
    $("#cnpj").mask("99.999.999/9999-99");
    $("#conf_fone").mask("99 9999 9999");
    $("#telefone").mask("99 9999 9999");
    $("#conf_celular").mask("99 9 9999 9999");
    $("#celular").mask("99 9 9999 9999");
    //$("#dep_data").mask("99/99/9999 99:99:99"); 
    //$("#campanha_data_inicio").mask("99/99/9999 99:99:99");
    //$("#campanha_data_fim").mask("99/99/9999 99:99:99");
});
