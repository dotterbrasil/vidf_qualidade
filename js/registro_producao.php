<?php

$cnpj_licenca = "00762956000120";
$empresa = "bbraun";
$caminho = "../../".$empresa."/";

$sucesso = 0;
$falha = 0;

$conteudo = "";
$registro = "";
$distribuidor = "";
$zona = "";
$embarque = "";
$auxiliar = chr(39);
$id = "1";
$origem = $cnpj_licenca;

$conteudo = stripslashes($_POST["conteudo"]);
$campos = $_POST["campos"];
$dados = explode(";",$_POST["dados"]);
$registros = $_POST["registros"];
$contador = $registros*$campos;

$anvisa = $_POST["fanvisa"];
$identificador = $_POST["fserial"];
$lote = $_POST["flote"];
$validade = $_POST["fvalidade"];//$validade = str_replace("/","",$validade);
$natureza = $_POST["fnatureza"];
$id = $_POST["fid"];


//define formato de data
date_default_timezone_set("America/Sao_Paulo");


//verifica se licenca e valida
$licenca = "../licencas/".$id.".lic";
$licenca = str_replace("Plataforma: ","",$licenca);
$licenca = str_replace(" - UUID: ","",$licenca);

if(1==1) {

	for ($x=0; $x<$contador; $x++)
  		{

		if (!($x<$campos-1))
			{

			$alfa = $x%$campos;

			if ($alfa==0)
				{

				$serial = $dados[$x];

				//prepara conteudo para registro
				$conteudo2 =  "Evento: ".str_pad(time(), 12, "0", STR_PAD_LEFT)."\r\n Natureza: ".$natureza."\r\n Data Registro: ".date("d/m/Y - h:i:sa")." - ID: ".$id."\r\n Validade: ".$validade."\r\n Id-Embalagem: ".$identificador."\r\n -----------------------------------------------------------\r\n";

				//verifica se registro anvisa existe e cria caso nao exista
				if(!file_exists($caminho.$anvisa)) {mkdir($caminho.$anvisa);}

				//verifica se lote existe e cria caso nao exista
				if(!file_exists($caminho.$anvisa."/".$lote)) {

					//registra os dados em quarentena
					if($natureza!="liberacao") {

						if(!file_exists($caminho.$anvisa."/q".$lote)) {mkdir($caminho.$anvisa."/q".$lote);}

						//prepara dados de agregacao
						$endereco_caixa = $anvisa."/q".$lote."/".$identificador;
						$FILE_caixa = $caminho.$endereco_caixa.".box";
						$lista = $serial.";";

						//registra agregacao e eventual falha
						$fp1 = fopen($FILE_caixa, "a");
						if(!fwrite($fp1, $lista)) {
							$endereco_caixa = date("d/m/Y - h:i:sa")." - Falha ao gravar registro - ".$endereco_caixa."\r\n";
							$FILE2 = $caminho."alertas/log_de_erros.txt";
							$fp2 = fopen($FILE2, "a+");
							fwrite($fp2, $endereco_caixa);
							fclose($fp2);
							$falha = $falha+1;
							}
							
						fclose($fp1);

						$endereco = $anvisa."/q".$lote."/".$serial;

						$FILE = $caminho.$endereco.".vid";

						//registra IUM e eventual falha
						$fp = fopen($FILE, "w+");
						if(!fwrite($fp, $conteudo2)) {
							$endereco = date("d/m/Y - h:i:sa")." - Falha ao gravar registro - ".$endereco."\r\n";
							$FILE2 = $caminho."alertas/log_de_erros.txt";
							$fp2 = fopen($FILE2, "a+");
							fwrite($fp2, $endereco);
							fclose($fp2);
							$falha = $falha+1;
							}
							else {
								$sucesso = $sucesso+1;
							}
						fclose($fp);
						}

					//libera o lote da quarentena
					if($natureza=="liberacao") {
						rename($caminho.$anvisa."/q".$lote,"../".$anvisa."/".$lote);
						exit("Operacao Efetuada!");
						}
					}

				}
			}
  		} 
	if($sucesso==$contador) {exit("Operacao Efetuada com 100% de sucesso!");}
	if($sucesso!=$contador) {exit("Operacao Efetuada com ".$falha." erros e ".$sucesso." registros bem sucedidos");}
	}

//registra tentativa de acesso nao autorizado
$licenca = date("d/m/Y - h:i:sa")." - ".$licenca." - IP: ".$_SERVER["REMOTE_ADDR"]." - HOST: ".$_SERVER["REMOTE_HOST"]." - PORT: ".$_SERVER["REMOTE_PORT"].chr(10).chr(13)."\r\n";
$FILE2 = $caminho."alertas/log_de_erros.txt";
$fp2 = fopen($FILE2, "a+");
fwrite($fp2, $licenca);
fclose($fp2);
exit("Dispositivo nao licenciado! Atencao: Esta tentativa de acesso foi identificada e registrada. O uso indevido de dispositivos e licencas, assim como a tentativa de acesso nao autorizado configuram infracao prevista no codigo penal brasileiro e estao sujeitas a acoes judiciais.");


?>
