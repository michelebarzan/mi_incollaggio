    function resetStyle(button)
    {
        var all = document.getElementsByClassName("functionListButton");
        for (var i = 0; i < all.length; i++) 
        {
            all[i].classList.remove("functionListButtonActive");
        }
        button.classList.add("functionListButtonActive");
    }
    function getTabellaParametriLana()
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                if(this.responseText.indexOf("Error")!=-1 || this.responseText.indexOf("Notice")!=-1)
                    window.alert(this.responseText);
                else
                    document.getElementById('containerTabellaParametri').innerHTML= this.responseText;
            }
        };
        xmlhttp.open("POST", "getTabellaParametriLana.php?", true);
        xmlhttp.send();
    }
    function modificaParametroLana(i,id_correzione_lana)
    {
        document.getElementById('risultato'+i).innerHTML="";
        var SPESS=document.getElementById('SPESS'+i).innerHTML;
        var ANG_MIN=document.getElementById('ANG_MIN'+i).innerHTML;
        var ANG_MAX=document.getElementById('ANG_MAX'+i).innerHTML;
        var latoStretto=document.getElementById('latoStretto'+i).innerHTML;
        var latoLargo=document.getElementById('latoLargo'+i).innerHTML;
        
        if(ANG_MIN=='' || ANG_MAX=='' || latoStretto=='' || latoLargo=='' || SPESS=='')	
            document.getElementById('risultato'+i).innerHTML="<b style='color:red'>Tutti i campi sono obbligatori</b>";
        else
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) 
                {
                    document.getElementById('risultato'+i).innerHTML= this.responseText;
                }
            };
            xmlhttp.open("POST", "modificaParametroLana.php?id_correzione_lana="+id_correzione_lana+"&ANG_MIN="+ANG_MIN+"&ANG_MAX="+ANG_MAX+"&latoStretto="+latoStretto+"&latoLargo="+latoLargo+"&SPESS="+SPESS, true);
            xmlhttp.send();
        }
    }
    function eliminaParametroLana(i,id_correzione_lana)
    {
        document.getElementById('risultato'+i).innerHTML="";
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                if(this.responseText.indexOf("eliminata")>0)
                        getTabellaParametriLana();
                    else
                        document.getElementById('risultato'+i).innerHTML= this.responseText;
            }
        };
        xmlhttp.open("POST", "eliminaParametroLana.php?id_correzione_lana="+id_correzione_lana, true);
        xmlhttp.send();
    }
    function inserisciParametroLana()
    {
        document.getElementById('risultatoInserimento').innerHTML="";
        var SPESS=document.getElementById('nuovoSPESS').innerHTML;
        var ANG_MIN=document.getElementById('nuovoANG_MIN').innerHTML;
        var ANG_MAX=document.getElementById('nuovoANG_MAX').innerHTML;
        var latoStretto=document.getElementById('nuovoLatoStretto').innerHTML;
        var latoLargo=document.getElementById('nuovoLatoLargo').innerHTML;
        
        if(ANG_MIN=='' || ANG_MAX=='' || latoStretto=='' || latoLargo=='' || SPESS=='')	
            document.getElementById('risultatoInserimento').innerHTML="<b style='color:red'>Tutti i campi sono obbligatori</b>";
        else
        {					
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (this.readyState == 4 && this.status == 200) 
                {
                    if(this.responseText.indexOf("inserita")>0)
                        getTabellaParametriLana();
                    else
                        document.getElementById('risultatoInserimento').innerHTML= this.responseText;
                }
            };
            xmlhttp.open("POST", "inserisciParametroLana.php?ANG_MIN="+ANG_MIN+"&ANG_MAX="+ANG_MAX+"&latoStretto="+latoStretto+"&latoLargo="+latoLargo+"&SPESS="+SPESS, true);
            xmlhttp.send();
        }
    }
    /*function getTabellaParametriApplicazione()
    {
        getTable("parametri");
	}*/
	function getTable(table,orderBy,orderType)
    {
        if(table=="parametri")
        {
            getEditableTable
            ({
                table:'parametri',
                primaryKey:"id_parametro",
                editable: true,
                readOnlyColumns:["id_parametro"],
                noInsertColumns:["id_parametro"],
				container:'containerTabellaParametri',
				noFilterColumns:['id_parametro','parametro','descrizione','valoreOLD'],
                orderBy:orderBy,
                orderType:orderType
            });
        }
    }
    function editableTableLoad()
    {

    }