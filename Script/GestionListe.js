function xml2json(xml, tab) {
   var X = {
      toObj: function(xml) {
         var o = {};
         if (xml.nodeType==1) {   // element node ..
            if (xml.attributes.length)   // element with attributes  ..
               for (var i=0; i<xml.attributes.length; i++)
                  o["@"+xml.attributes[i].nodeName] = (xml.attributes[i].nodeValue||"").toString();
            if (xml.firstChild) { // element has child nodes ..
               var textChild=0, cdataChild=0, hasElementChild=false;
               for (var n=xml.firstChild; n; n=n.nextSibling) {
                  if (n.nodeType==1) hasElementChild = true;
                  else if (n.nodeType==3 && n.nodeValue.match(/[^ \f\n\r\t\v]/)) textChild++; // non-whitespace text
                  else if (n.nodeType==4) cdataChild++; // cdata section node
               }
               if (hasElementChild) {
                  if (textChild < 2 && cdataChild < 2) { // structured element with evtl. a single text or/and cdata node ..
                     X.removeWhite(xml);
                     for (var n=xml.firstChild; n; n=n.nextSibling) {
                        if (n.nodeType == 3)  // text node
                           o["#text"] = X.escape(n.nodeValue);
                        else if (n.nodeType == 4)  // cdata node
                           o["#cdata"] = X.escape(n.nodeValue);
                        else if (o[n.nodeName]) {  // multiple occurence of element ..
                           if (o[n.nodeName] instanceof Array)
                              o[n.nodeName][o[n.nodeName].length] = X.toObj(n);
                           else
                              o[n.nodeName] = [o[n.nodeName], X.toObj(n)];
                        }
                        else  // first occurence of element..
                           o[n.nodeName] = X.toObj(n);
                     }
                  }
                  else { // mixed content
                     if (!xml.attributes.length)
                        o = X.escape(X.innerXml(xml));
                     else
                        o["#text"] = X.escape(X.innerXml(xml));
                  }
               }
               else if (textChild) { // pure text
                  if (!xml.attributes.length)
                     o = X.escape(X.innerXml(xml));
                  else
                     o["#text"] = X.escape(X.innerXml(xml));
               }
               else if (cdataChild) { // cdata
                  if (cdataChild > 1)
                     o = X.escape(X.innerXml(xml));
                  else
                     for (var n=xml.firstChild; n; n=n.nextSibling)
                        o["#cdata"] = X.escape(n.nodeValue);
               }
            }
            if (!xml.attributes.length && !xml.firstChild) o = null;
         }
         else if (xml.nodeType==9) { // document.node
            o = X.toObj(xml.documentElement);
         }
         else
            alert("unhandled node type: " + xml.nodeType);
         return o;
      },
      toJson: function(o, name, ind) {
         var json = name ? ("\""+name+"\"") : "";
         if (o instanceof Array) {
            for (var i=0,n=o.length; i<n; i++)
               o[i] = X.toJson(o[i], "", ind+"\t");
            json += (name?":[":"[") + (o.length > 1 ? ("\n"+ind+"\t"+o.join(",\n"+ind+"\t")+"\n"+ind) : o.join("")) + "]";
         }
         else if (o == null)
            json += (name&&":") + "null";
         else if (typeof(o) == "object") {
            var arr = [];
            for (var m in o)
               arr[arr.length] = X.toJson(o[m], m, ind+"\t");
            json += (name?":{":"{") + (arr.length > 1 ? ("\n"+ind+"\t"+arr.join(",\n"+ind+"\t")+"\n"+ind) : arr.join("")) + "}";
         }
         else if (typeof(o) == "string")
            json += (name&&":") + "\"" + o.toString() + "\"";
         else
            json += (name&&":") + o.toString();
         return json;
      },
      innerXml: function(node) {
         var s = ""
         if ("innerHTML" in node)
            s = node.innerHTML;
         else {
            var asXml = function(n) {
               var s = "";
               if (n.nodeType == 1) {
                  s += "<" + n.nodeName;
                  for (var i=0; i<n.attributes.length;i++)
                     s += " " + n.attributes[i].nodeName + "=\"" + (n.attributes[i].nodeValue||"").toString() + "\"";
                  if (n.firstChild) {
                     s += ">";
                     for (var c=n.firstChild; c; c=c.nextSibling)
                        s += asXml(c);
                     s += "</"+n.nodeName+">";
                  }
                  else
                     s += "/>";
               }
               else if (n.nodeType == 3)
                  s += n.nodeValue;
               else if (n.nodeType == 4)
                  s += "<![CDATA[" + n.nodeValue + "]]>";
               return s;
            };
            for (var c=node.firstChild; c; c=c.nextSibling)
               s += asXml(c);
         }
         return s;
      },
      escape: function(txt) {
         return txt.replace(/[\\]/g, "\\\\")
                   .replace(/[\"]/g, '\\"')
                   .replace(/[\n]/g, '\\n')
                   .replace(/[\r]/g, '\\r');
      },
      removeWhite: function(e) {
         e.normalize();
         for (var n = e.firstChild; n; ) {
            if (n.nodeType == 3) {  // text node
               if (!n.nodeValue.match(/[^ \f\n\r\t\v]/)) { // pure whitespace text node
                  var nxt = n.nextSibling;
                  e.removeChild(n);
                  n = nxt;
               }
               else
                  n = n.nextSibling;
            }
            else if (n.nodeType == 1) {  // element node
               X.removeWhite(n);
               n = n.nextSibling;
            }
            else                      // any other node
               n = n.nextSibling;
         }
         return e;
      }
   };
   if (xml.nodeType == 9) // document node
      xml = xml.documentElement;
   var json = X.toJson(X.toObj(X.removeWhite(xml)), xml.nodeName, "\t");
   return "{\n" + tab + (tab ? json.replace(/\t/g, tab) : json.replace(/\t|\n/g, "")) + "\n}";
}

function verif()
  {
  if (document.layers)
    {
    formulaire = document.forms.monFormulaire;
    }
  else
    {
    formulaire = document.monFormulaire;
    }
  }

function verifChoixRegion()
  {
  verif();
  if (formulaire.choixRegion.value == "0")
    {
    alert('Vous devez tout d\'abord choisir une Région!');
    formulaire.choixRegion.focus();
    }
}

function verifChoixDepartement()
  {
  verif();
  if (formulaire.choixRegion.value == "0")
    {
    alert('Vous devez tout d\'abord choisir un Département!');
    formulaire.choixRegion.focus();
    }
}

  
var Region = new Array();
var Departement = new Array();
var Commune = new Array();


function ListeRegion(Json)
{
	verif();
	//alert(Json.Regions.Region.LibReg);
	console.log(Json.Regions.Region);
	Region = Json.Regions.Region;
	
	//alert(Json.Regions.Region[1].LibReg);
	
	monFormulaire.choixRegion.options.length = Region.length +1;	
	
	formulaire.choixRegion.options[0].text = "-- choisissez une Région";
    formulaire.choixRegion.options[0].value = 0;
	
    for (i = 0; i< Region.length; i++)
      {
      formulaire.choixRegion.options[i+1].value = Region[i].CodReg3Car;
      formulaire.choixRegion.options[i+1].text = Region[i].LibReg;
      }
    document.monFormulaire.choixRegion.options.selectedIndex = 0;
}


function remplirDep(code)
  {
  if (code != 0)
  {
	  verif();
	  var RegionID;
	  for (i = 0; i< Region.length; i++)
	  {
		if(Region[i].CodReg3Car == code)
			RegionID = i;
	  }
	  Departement = Region[RegionID].Departements.Departement;
		if(Array.isArray(Departement) == true)
		{
			monFormulaire.choixDepartement.options.length = Departement.length + 1;
			
			formulaire.choixDepartement.options[0].text = "-- choisissez un Département";
			formulaire.choixDepartement.options[0].value = 0;
			
			for (i=0; i<Departement.length; i++)
			  {
			  formulaire.choixDepartement.options[i+1].value = Departement[i].CodDpt3Car;
			  formulaire.choixDepartement.options[i+1].text = Departement[i].LibDpt;
			  }		
		}
		else
		{
			console.log(Departement);
			formulaire.choixDepartement.options.length = 2;
			
			formulaire.choixDepartement.options[0].text = "-- choisissez un Département";
			formulaire.choixDepartement.options[0].value = 0;
			
			formulaire.choixDepartement.options[1].text = Departement.LibDpt;
			formulaire.choixDepartement.options[1].value = Departement.CodDpt3Car;
		}
	  document.monFormulaire.choixDepartement.options.selectedIndex = 0;
  }
  	else
	{
		formulaire.choixDepartement.options.length = 1;
		formulaire.choixDepartement.options[0].value = 0;
		formulaire.choixDepartement.options[0].text = "-- choisissez un Départment";
	}
}

function remplirCom(code)
  {
	if(code != 0)
	{
	  verif();
	  var DepartementID;
	  if(Array.isArray(Departement) == true)
	  {
		  for (i = 0; i< Departement.length; i++)
		  {
			if(Departement[i].CodDpt3Car == code)
				DepartementID = i;
		  }
		  Commune = Departement[DepartementID].Communes.Commune;
	  }
	  else
	  {
		Commune = Departement.Communes.Commune;
	  }
	  if(Array.isArray(Commune) == true)
	  {
			monFormulaire.choixCommune.options.length = Commune.length + 1;
			
			formulaire.choixCommune.options[0].text = "-- choisissez un Département";
			formulaire.choixCommune.options[0].value = 0;
			
			for (i=0; i<Commune.length; i++)
			  {
			  formulaire.choixCommune.options[i+1].value = Commune[i].CodSubCom;
			  formulaire.choixCommune.options[i+1].text = Commune[i].LibSubCom;
			  }					
		}
		else
		{
			formulaire.choixCommune.options.length = 2;
			
			formulaire.choixCommune.options[0].text = "-- choisissez une Commune";
			formulaire.choixCommune.options[0].value = 0;
			
			formulaire.choixCommune.options[1].text = Commune.LibSubCom;
			formulaire.choixCommune.options[1].value = Commune.CodSubCom;
		}
	  document.monFormulaire.choixCommune.options.selectedIndex = 0;
   }
   else
   {
		formulaire.choixCommune.options.length = 1;
		formulaire.choixCommune.options[0].value = 0;
		formulaire.choixCommune.options[0].text = "-- choisissez une Commune";
   }
 }
 
   function ListeCandidat(listCandidats)
{
	console.log(listCandidats.Candidats.Candidat);
	Candidat = listCandidats.Candidats.Candidat;
	
	var  TabCandidat = new Array();
	TabCandidat.push("x");
	
	for (i = 0; i< Candidat.length; i++)
	{
		var string = Candidat[i].CivilitePsn + ' ' + Candidat[i].PrenomPsn + ' ' + Candidat[i].NomPsn;
		TabCandidat.push(string);
	}
	
	return TabCandidat;
}

