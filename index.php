<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Titre</title>	
		<script language="javascript" src="Script/GestionListe.js"></script>
		
		<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
		<script src="js/c3.js"></script>

    </head>
    <body style="min-height: 100%: 100%;display:table; width:100%; height:100%; border-collapse:collapse; text-align: center; vertical-align: middle;">

	<?php
	
		$pyscript = "C:\\wamp\\www\\ProjetWebElection\\Script\\parser.py ";
		$python = "C:\\Python27\\python.exe ";

		$rep_xml = "http://www.interieur.gouv.fr/avotreservice/elections/telechargements/EssaiPR2017/";
		$xml_listeregdptcom = simplexml_load_file($rep_xml . "referencePR/listeregdptcom.xml");
		
		$xml_listecandidats = simplexml_load_file($rep_xml . 'candidatureT1/CandidatureT1.xml');
		//echo $xml_listecandidats;
		
		$xml_listeNat = $rep_xml . 'resultatsT1/FE.xml';
		
		$cmd = $python . $pyscript . $xml_listeNat;
		//echo $cmd;
		//$resultatN = shell_exec($cmd);
		exec($cmd, $resultatN);
		//echo 'N : ' . $resultatN[1] . '<br>';
		
	    if(!empty($_GET['choixRegion']) && $_GET['choixRegion'] != 0)
		{	
			$codeR = $_GET['choixRegion'];
			//echo "code R = " . $codeR;
			
			$xml_listeReg = $rep_xml . "resultatsT1/" . $codeR ."/" . $codeR . ".xml";
			//echo $xml_listeReg;
				
			$cmd = $python . $pyscript . $xml_listeReg;
			exec($cmd, $resultatR);
			//echo 'R : ' . $resultatR[1] . '<br>';
		}
		else
		{
			$resultatR = array();
		}
		if(!empty($_GET['choixDepartement']) && $_GET['choixDepartement'] != 0)
		{	
			$codeD = $_GET['choixDepartement'];
			//echo "code D = " . $codeD;
			
			$xml_listeRegDep = $rep_xml . "resultatsT1/" . $codeR ."/" . $codeD ."/" . $codeD . ".xml";
			//echo $xml_listeRegDep;
				
			$cmd = $python . $pyscript . $xml_listeRegDep;
			exec($cmd, $resultatD);
			//echo 'D : ' . $resultatD[1] . '<br>';
		}
		else
		{
			$resultatD = array();
		}
		if(!empty($_GET['choixCommune']) && $_GET['choixCommune'] != 0)
		{	
			$codeC = $_GET['choixCommune'];
			//echo "code C = " . $codeC;
			
			$xml_listeRegDepCom = $rep_xml . "resultatsT1/" . $codeR ."/" . $codeD ."/" . $codeD . ".xml";
			//echo $xml_listeRegDepCom;
				
			$cmd = $python . $pyscript . $xml_listeRegDepCom;
			exec($cmd, $resultatC);
			//echo 'C : ' . $resultatC[1] . '<br>';

		}
		else
		{
			$resultatC = array();	
			//echo 'C : ' . $resultatC[0] . '<br>';			
		}
		
		function js_str($s)
		{
			return '"' . addcslashes($s, "\0..\37\"\\") . '"';
		}

		function js_array($array)
		{
			$temp = array_map('js_str', $array);
			return '[' . implode(',', $temp) . ']';
		}
		
	?>
	<div style="background-color:AliceBlue; height:10%">
		<form name="monFormulaire" action="index.php" method="GET">
		<select name="choixRegion" onChange="remplirDep(this.options[this.selectedIndex].value);remplirCom(0);">
		<option value="0" selected>-- choisissez une Région</option>
		</select>
		<select name="choixDepartement" onFocus="verifChoixRegion();" onChange="remplirCom(this.options[this.selectedIndex].value);">
		<option value="0" selected>-- choisissez un Département</option>
		</select>
		<select name="choixCommune" onFocus="verifChoixDepartement();">
		<option value="0" selected>-- choisissez une Commune</option>
		</select>
		<input type="submit" value="Valider" />
		</form>
	</div>
	<script type="text/javascript">
		var json = <?php echo json_encode($xml_listeregdptcom); ?>;
		ListeRegion(json);
		
		var listCandidats = <?php //echo json_encode($xml_listecandidats); ?>;
		console.log(listCandidats);
		var TabListeCandidats = ListeCandidat(listCandidats);
		//var TabListeCandidats = ['x','1','2','3','4','5','6','7','8','9','10','11','12'];
		console.log(TabListeCandidats);
	</script>
	
	<br>
	<div id="national" style="width:100%; height:50%;">	

	</div>
	
	<script type="text/javascript">
	if(!<?php echo empty($resultatN); ?>)
	{
		var StringN =<?php echo json_encode($resultatN[1]);?>;
		StringN = StringN.substring(1,StringN.length-1);
		console.log(StringN);
		var TabN = StringN.split(",");	
		TabN.unshift("National");
		console.log(TabN);
	}
	else
	{
		//var TabN = new Array {};
	}
	if(!<?php echo empty($resultatR); ?>)
	{
		var StringR =<?php echo json_encode($resultatR[1]);?>;
		StringR = StringR.substring(1,StringR.length-1);
		var TabR = StringR.split(",");	
		TabR.unshift("Régional");
	}
	else
	{
		//var TabR = new Array {};
	}
	if(!<?php echo  empty($resultatD); ?>)
	{
		var StringD =<?php echo json_encode($resultatD[1]);?>;
		StringD = StringD.substring(1,StringD.length-1);
		var TabD = StringD.split(",");	
		TabD.unshift("Departemental");
	}
	else
	{
		//var TabD = new Array {};
	}
	
	if(!<?php echo  empty($resultatC); ?>)
	{
		var StringC =<?php echo json_encode($resultatC[1]);?>;
		console.log("log stringC :" + StringC);
		StringC = StringC.substring(1,StringC.length-1);
		var TabC = StringC.split(",");	
		TabC.unshift("Communal");
	}
	else
	{
		//var TabC = new Array {};
	}
    var chart = c3.generate({
        data: {
			x : 'x',
            columns: [	
					TabListeCandidats,
					TabN,
					TabR,
					TabD,
					TabC,
               ],
               type: 'bar'
           },
		axis: {
			x: {
				type: 'category' // this needed to load string x value
			}
		},
        bar: {
            width: {
                ratio: 0.5 // this makes bar width 50% of length between ticks
            }
               // or
               //width: 100 // this makes bar width 100px
           },
           bindto: '#national'
          });
    </script>
    </body>

</html>