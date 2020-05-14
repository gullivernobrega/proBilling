//ALERT BOX
$('#alert').on('click',function(){
  $.dialogbox({
  type:'msg',
  title:'Mensagem',
  icon:1,
  content:'Mensagem enviaca com sucesso, aguarde nosso contato!',
  btn:['sendEmail'],
  call:[
    function(){
      $.dialogbox.close(); 
    }
  ]
});
});


