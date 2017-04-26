<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Titre</title>	
		<script language="javascript" src="Script/GestionListe.js"></script>
		
		<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
		<script src="js/c3.js"></script>

    </head>
    <body style="min-height: 100%: 100%;display:table; width:100%; height:100%; border-collapse:collapse; text-align: center; vertical-align: middle;margin: 0 auto">

	<?php
	
		$pyscript = "C:\\wamp\\www\\ProjetWebElection\\Script\\parser.py ";
		$python = "C:\\Python27\\python.exe ";

		$rep_xml = "http://elections.interieur.gouv.fr/telechargements/PR2017/";
		$xml_listeregdptcom = simplexml_load_file($rep_xml . "referencePR/listeregdptcom.xml");
		
		$xml_listecandidats = simplexml_load_file($rep_xml . 'candidatureT1/CandidatureT1.xml');
		//echo $xml_listecandidats;
		
		$xml_listeNat = $rep_xml . 'resultatsT1/FE.xml';
		
		$cmd = $python . $pyscript . $xml_listeNat;
		//$resultatN = shell_exec($cmd);
		exec($cmd, $resultatN);
		
	    if(!empty($_GET['choixRegion']) && $_GET['choixRegion'] != 0)
		{	
			$codeR = $_GET['choixRegion'];
			
			$xml_listeReg = $rep_xml . "resultatsT1/" . $codeR ."/" . $codeR . ".xml";
				
			$cmd = $python . $pyscript . $xml_listeReg;
			exec($cmd, $resultatR);
			$boolRegion = 1;
		}
		else
		{
			$resultatR = [
				"",""
			];	
			
			$boolRegion = 0;
			
		}
		if(!empty($_GET['choixDepartement']) && $_GET['choixDepartement'] != 0)
		{	
			$codeD = $_GET['choixDepartement'];
			
			$xml_listeRegDep = $rep_xml . "resultatsT1/" . $codeR ."/" . $codeD ."/" . $codeD . ".xml";
				
			$cmd = $python . $pyscript . $xml_listeRegDep;
			exec($cmd, $resultatD);
			$boolDep = 1;
		}
		else
		{
			$resultatD = [
				"",""
			];	
			$boolDep = 0;
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
			$boolCommune = 1;

		}
		else
		{
			$resultatC = [
				"",""
			];	
			$boolCommune = 0;
		}
		
	?>
	<div style="background-color:AliceBlue; height:10%">
		<form name="monFormulaire" action="index.php" method="GET">
		<select name="choixRegion" onChange="remplirDep(this.options[this.selectedIndex].value);remplirCom(0);">
		<option value="0" selected>-- choisissez une Region</option>
		</select>
		<select name="choixDepartement" onFocus="verifChoixRegion();" onChange="remplirCom(this.options[this.selectedIndex].value);">
		<option value="0" selected>-- choisissez un Departement</option>
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
		
		var listCandidats = <?php echo json_encode($xml_listecandidats); ?>;
		console.log(listCandidats);
		var TabListeCandidats = ListeCandidat(listCandidats);
		//var TabListeCandidats = ['x','1','2','3','4','5','6','7','8','9','10','11','12'];
		console.log(TabListeCandidats);
	</script>
	
	<br>
	<div id="corp" style="width:100%; height:100%; margin:0 auto;">
		<h3>Résultats des élections : </h3>
		<div id="national" style="width:100%; height:45%;">	
		</div>
		<h3>Participation : </h3>
		<div id="Cnational" class="toto" style="width:25%; float:left; ">
		</div>
		
		<div id="Cregional" class="toto" style="width:25%; height:22%; float:left; ">
		</div>
		
		<div id="Cdepartmental" class="toto" style="width:25%; height:22%;float:left;">
		</div>
		
		<div id="Ccommunal" class="toto" style="width:25%; height:22%;float:left;">
		</div>
	</div>
	
	<script type="text/javascript">

	var StringN =<?php echo json_encode($resultatN[1]);?>;
	StringN = StringN.substring(1,StringN.length-1);
	console.log(StringN);
	var TabN = StringN.split(",");	
	TabN.unshift("National");
	console.log(TabN);
	
	var CamN =<?php echo json_encode($resultatN[0]);?>;
	CamN2 = CamN.split("'").join('"');
	console.log(CamN);
	var DonutN = JSON.parse(CamN2);
	console.log(DonutN);

	if(<?php echo $boolRegion;?> >= 1)
	{
		var StringR =<?php echo json_encode($resultatR[1]);?>;
		StringR = StringR.substring(1,StringR.length-1);
		var TabR = StringR.split(",");	
		TabR.unshift("Régional");
		console.log(TabR);
		
		var CamR =<?php echo json_encode($resultatR[0]);?>;
		CamR2 = CamR.split("'").join('"');
		console.log(CamR2);
		var DonutR = JSON.parse(CamR2);
		console.log(DonutR);
	}
	else
	{
		var TabR = [];
	}
	if(<?php echo $boolDep;?> >= 1)
	{
		var StringD =<?php echo json_encode($resultatD[1]);?>;
		StringD = StringD.substring(1,StringD.length-1);
		var TabD = StringD.split(",");	
		TabD.unshift("Departemental");
		console.log(TabD);
		
		var CamD =<?php echo json_encode($resultatD[0]);?>;
		CamD2 = CamD.split("'").join('"');
		console.log(CamD2);
		var DonutD = JSON.parse(CamD2);
		console.log(DonutD);
	}
	else
	{
		var TabD = [];
	}
	
	if(<?php echo $boolCommune;?> >= 1)
	{
		var StringC = <?php echo json_encode($resultatC[1]);?>;
		console.log("log stringC :" + StringC);
		StringC = StringC.substring(1,StringC.length-1);
		var TabC = StringC.split(",");	
		TabC.unshift("Communal");
		console.log(TabC);
		
		var CamC =<?php echo json_encode($resultatC[0]);?>;
		CamC2 = CamC.split("'").join('"');
		console.log(CamC2);
		var DonutC = JSON.parse(CamC2);
		console.log(DonutC);
	}
	else
	{
		var TabC = [];
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
		  
		  
	var CNational = c3.generate({
		data: {
			columns: [
				DonutN[1],
				DonutN[3],
				DonutN[4],
				DonutN[5],
			],
			type : 'donut',
			onclick: function (d, i) { console.log("onclick", d, i); },
			onmouseover: function (d, i) { console.log("onmouseover", d, i); },
			onmouseout: function (d, i) { console.log("onmouseout", d, i); }
		},
		donut: {
			title: "National"
		},
		bindto: '#Cnational'
	});
	
	var CRegional = c3.generate({
		data: {
			columns: [
				DonutR[1],
				DonutR[3],
				DonutR[4],
				DonutR[5],
			],
			type : 'donut',
			onclick: function (d, i) { console.log("onclick", d, i); },
			onmouseover: function (d, i) { console.log("onmouseover", d, i); },
			onmouseout: function (d, i) { console.log("onmouseout", d, i); }
		},
		donut: {
			title: "Regional"
		},
		bindto: '#Cregional'
	});
	
	var CDepartemantal = c3.generate({
		data: {
			columns: [
				DonutD[1],
				DonutD[3],
				DonutD[4],
				DonutD[5],
			],
			type : 'donut',
			onclick: function (d, i) { console.log("onclick", d, i); },
			onmouseover: function (d, i) { console.log("onmouseover", d, i); },
			onmouseout: function (d, i) { console.log("onmouseout", d, i); }
		},
		donut: {
			title: "Departemental"
		},
		bindto: '#Cdepartmental'
	});
	
	var CCommune = c3.generate({
		data: {
			columns: [
				DonutC[1],
				DonutC[3],
				DonutC[4],
				DonutC[5],
			],
			type : 'donut',
			onclick: function (d, i) { console.log("onclick", d, i); },
			onmouseover: function (d, i) { console.log("onmouseover", d, i); },
			onmouseout: function (d, i) { console.log("onmouseout", d, i); }
		},
		donut: {
			title: "Communal"
		},
		bindto: '#Ccommunal'
	});
	
    </script>
    </body>

</html>