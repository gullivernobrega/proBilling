//MUDA A COR DE UM IMPUT RADIO
var gbTrow;
var callit;
callit = false;
function mudacor(numCor) {
trow = document.getElementById("nivel"+numCor);
if (callit==false) {
callit = true;
} else {
gbTrow.bgColor = "#ffffff";
}
trow.bgColor = "#cccccc";
gbTrow = trow;
return true;
}
