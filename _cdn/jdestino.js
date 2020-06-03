
$(document).ready(function () {

//    $('#iax, #sip, #fqueue, #fgroup, #fcustom').change(function () {
//
//        if ($('input[name="ramal"]:checked').val() === "IAX") {
//            $('#ramalIax').fadeIn();
//        } else {
//            $('#ramalIax').hide();
//        }
//        
//        if ($('input[name="ramal"]:checked').val() === "SIP") {
//            $('#ramalSip').fadeIn();
//        } else {
//            $('#ramalSip').hide();
//        }
//
//        if ($('input[name="ramal"]:checked').val() === "QUEUE") {
//            $('#queue').fadeIn();
//        } else {
//            $('#queue').hide();
//        }
//
//        if ($('input[name="ramal"]:checked').val() === "GROUP") {
//            $('#group').fadein();
//        } else {
//            $('#group').hide();
//        }
//
//        if ($('input[name="ramal"]:checked').val() === "CUSTOM") {
//            $('#custom').fadeIn();
//        } else {
//            $('#custom').hide();
//        }
//
//    });


    $('#iax, #sip, #fqueue, #fgroup, #fcustom').click(function () {

        if (this.value === "IAX") {            
            $('#ramalIax').fadeTo(1000, 1.0);            
        } else {
            $('#ramalIax').hide();
        }
        if (this.value === "SIP") {
            $('#ramalSip').fadeTo(1000, 1.0);
        } else {
            $('#ramalSip').hide();
        }

        if (this.value === "QUEUE") {
            $('#queue').fadeTo(1000, 1.0);
        } else {
            $('#queue').hide();
        }

        if (this.value === "URA") {
            $('#ura-id').fadeTo(1000, 1.0);
        } else {
            $('#ura-id').hide();
        }

        if (this.value === "CUSTOM") {
            $('#custom').fadeTo(1000, 1.0);
        } else {
            $('#custom').hide();
        }

    });
    
    $("#did_hora_ss_ini, #did_hora_ss_fim").mask("99:99:99"); 
    $("#did_hora_s_ini, #did_hora_s_fim").mask("99:99:99"); 
    $("#did_hora_d_ini, #did_hora_d_fim").mask("99:99:99"); 

});
