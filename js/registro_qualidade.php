<?php
$conteudo = "";
$registro = "";
$distribuidor = "";
$zona = "";
$embarque = "";
$auxiliar = chr(39);
$id = "1";
$origem = "00762956000120";

$conteudo = stripslashes($_POST["conteudo"]);
$campos = $_POST["campos"];
$dados = explode(";",$_POST["dados"]);
$registros = $_POST["registros"];
$contador = $registros*$campos;

$anvisa = $_POST["fanvisa"];
$serial = $_POST["fserial"];
$lote = $_POST["flote"];
$validade = $_POST["fvalidade"];$validade = str_replace("/","",$validade);
$natureza = $_POST["fnatureza"];
$id = $_POST["fid"];

date_default_timezone_set("America/Sao_Paulo");

$licenca = "../licencas/".$id.".lic";
$licenca = str_replace("Plataforma: ","",$licenca);
$licenca = str_replace(" - UUID: ","",$licenca);

if(file_exists($licenca)) {

	for ($x=0; $x<$contador; $x++)
  		{

		if (!($x<$campos-1))
			{

			$alfa = $x%$campos;

			if ($alfa==0)
				{

				$serial = $dados[$x];

				$conteudo2 =  date("d/m/Y - h:i:sa")." - ID: ".$id."\r\n Natureza: ".$natureza"\r\n -----------------------------------------------------------\r\n";

				$endereco = $anvisa."/".$lote."/".$serial;

				$FILE = "../".$endereco.".vid";

				$fp = fopen($FILE, "a+");
				if(!fwrite($fp, $conteudo2)) {
						$endereco = date("d/m/Y - h:i:sa")." - Falha ao gravar registro - ".$endereco."\r\n";
						$FILE2 = "../alertas/log_de_erros.txt";
						$fp2 = fopen($FILE2, "a+");
						fwrite($fp2, $endereco);
						fclose($fp2);
						}
				fclose($fp);
				echo "<html><script>texto_alerta = 'registrou arquivo'; alert(texto_alerta);</script></html>";
				

				}
			}
  		} 
	exit("Operacao Efetuada!");
	}
$licenca = date("d/m/Y - h:i:sa")." - ".$licenca." - IP: ".$_SERVER["REMOTE_ADDR"]." - HOST: ".$_SERVER["REMOTE_HOST"]." - PORT: ".$_SERVER["REMOTE_PORT"].chr(10).chr(13)."\r\n";
$FILE2 = "../alertas/log_de_erros.txt";
$fp2 = fopen($FILE2, "a+");
fwrite($fp2, $licenca);
fclose($fp2);
exit("Dispositivo nao licenciado! Atencao: Esta tentativa de acesso foi identificada e registrada. O uso indevido de dispositivos e licencas, assim como a tentativa de acesso nao autorizado configuram infracao prevista no codigo penal brasileiro e estao sujeitas a acoes judiciais.");


?>
