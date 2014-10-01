
function processaDados() {

	Lote = document.getElementById("texto").value;

	for (i=0;i<Lote.lastIndexOf(String.fromCharCode(10));i++) {
		Lote = Lote.replace(String.fromCharCode(10),"");
		Lote = Lote.replace(String.fromCharCode(13),"");
		}

	for (i=0;i<Lote.lastIndexOf(" ");i++) {
		Lote = Lote.replace(" ","");
		}

	document.getElementById("texto").value = Lote;
	document.getElementById("dados").value = Lote;
	
	contador = 0;
}

function validaArquivo(){

	var tamanho = 0;
	var final = 0;
	var Lote = "";
	var num_campos = 1;
	var valido = 1;

	processaDados();
	Lote = document.getElementById("texto").value;

	tamanho = Lote.length;
	final = Lote.lastIndexOf(";");
		
	for (i=0;i<final;i++) {
		if(Lote.indexOf(";",i)>0) {
			contador++;
			i = Lote.indexOf(";",i);
		} else {valido = -1;}
	}

	if(contador%num_campos!=0) {valido = -1;}

	if(valido>0)
		{
		texto_alerta = "Arquivo Correto com "+contador/num_campos+" registros";
		alert(texto_alerta);
		document.formulario.registros.value = contador/num_campos;
		document.formulario.campos.value = num_campos;
		chama_suporte();
		} else {
			texto_alerta = "Arquivo com Erro";
			alert(texto_alerta);
			}

	document.formulario.registros.value = contador/num_campos;
	document.formulario.campos.value = num_campos;
}


function envio(){

		parent.VID.formulario.submit();
		vid = document.formulario.fid.value;
		document.formulario.reset();
		document.formulario.fid.value = vid;
}