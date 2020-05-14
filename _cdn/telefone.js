
$("#telefone").keydown(function(){
    try {
        $("#telefone").unmask();
    } catch (e) {}

    var tamanho = $("#telefone").val().length;

    if(tamanho < 11){
        $("#telefone").mask("(62).9999-9999");
    } else if(tamanho = 11){
        $("#telefone").mask("(62).99999-9999");
    }

    // ajustando foco
    var elem = this;
    setTimeout(function(){
        // mudo a posição do seletor
        elem.selectionStart = elem.selectionEnd = 10000;
    }, 0);
    // reaplico o valor para mudar o foco
    var currentValue = $(this).val();
    $(this).val('');
    $(this).val(currentValue);
});
