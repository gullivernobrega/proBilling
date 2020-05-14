$(document).ready(function () {
//   fadeTo(1000, 1.0)
    $('#Ramal').click(function () {

        if (this.value === "R") {
            $('#sectionRamais').show();
            $('#sectionAgents').hide();
        }
        
    });
   
    $('#Agent').click(function () {

        if (this.value === "A") {
            $('#sectionAgents').show();
            $('#sectionRamais').hide();
        }

    });

});
