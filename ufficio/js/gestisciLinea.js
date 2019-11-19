	function resetStyle()
	{
		var all = document.getElementsByClassName("btnIntestazioneGestisciLinea");
		for (var i = 0; i < all.length; i++) 
		{
			all[i].style.color = 'black';
			all[i].style.boxShadow="";
		}
		/*try
		{
			var interval=pannelliInProduzione();
			clearInterval(interval);
		}
		catch(e)
		{
			window.alert(e.message);
		}*/
	}
	function newGridSpinner(message,container,spinnerContainerStyle,spinnerStyle,messageStyle)
	{
		document.getElementById(container).innerHTML='<div id="gridSpinnerContainer"  style="'+spinnerContainerStyle+'"><div  style="'+spinnerStyle+'" class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div> <div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div><div id="messaggiSpinner" style="'+messageStyle+'">'+message+'</div></div>';
	}
	function pannelliInProduzione()
	{
		document.getElementById('btnPannelliInProduzione').style.color="#3367d6";
		document.getElementById('btnPannelliInProduzione').style.boxShadow=" 5px 5px 10px #9c9e9f";
		newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
			}
		};
		xmlhttp.open("POST", "pannelliInProduzione.php?", true);
		xmlhttp.send();
	}
	function pannelliProdotti(codpan,lotto,dataOra)
	{
		document.getElementById('btnPannelliProdotti').style.color="#3367d6";
		document.getElementById('btnPannelliProdotti').style.boxShadow=" 5px 5px 10px #9c9e9f";
		newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
			}
		};
		xmlhttp.open("POST", "pannelliProdotti.php?codpan="+codpan+"&lotto="+lotto+"&dataOra="+dataOra, true);
		xmlhttp.send();
	}
	function filtroLotto()
	{
		var lotto=document.getElementById("filtroLottoPannelliProdotti").value;
		//window.alert(lotto);
		pannelliProdotti(document.getElementById("filtroCodpanPannelliProdotti").value,lotto,document.getElementById("filtroDataOraPannelliProdotti").value);
	}
	function filtroCodpan()
	{
		var codpan=document.getElementById("filtroCodpanPannelliProdotti").value;
		//window.alert(lotto);
		pannelliProdotti(codpan,document.getElementById("filtroLottoPannelliProdotti").value,document.getElementById("filtroDataOraPannelliProdotti").value);
	}
	function filtroDataOra()
	{
		var dataOra=document.getElementById("filtroDataOraPannelliProdotti").value;
		//window.alert(lotto);
		pannelliProdotti(document.getElementById("filtroCodpanPannelliProdotti").value,document.getElementById("filtroLottoPannelliProdotti").value,dataOra);
	}
	function elencoBancali()
	{
		document.getElementById('btnElencoBancali').style.color="#3367d6";
		document.getElementById('btnElencoBancali').style.boxShadow=" 5px 5px 10px #9c9e9f";
		newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
			}
		};
		xmlhttp.open("POST", "elencoBancali.php?", true);
		xmlhttp.send();
	}
	function elencoUtenti()
	{
		document.getElementById('btnElencoUtenti').style.color="#3367d6";
		document.getElementById('btnElencoUtenti').style.boxShadow=" 5px 5px 10px #9c9e9f";
		newGridSpinner("Caricamento dati in corso...","tabelleGestisciLinea","","","font-size:80%;color:#2B586F");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('tabelleGestisciLinea').innerHTML= this.responseText;
			}
		};
		xmlhttp.open("POST", "elencoUtenti.php?", true);
		xmlhttp.send();
	}
	function modificaUtente(i)
	{
		var id_utente=document.getElementById('idUtente'+i).innerHTML;
		var nome=document.getElementById('nomeUtente'+i).innerHTML;
		var cognome=document.getElementById('cognomeUtente'+i).innerHTML;
		var username=document.getElementById('usernameUtente'+i).innerHTML;
		
		if(nome=='' || cognome=='' || username=='')	
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
			xmlhttp.open("POST", "modificaUtente.php?id_utente="+id_utente+"&nome="+nome+"&cognome="+cognome+"&username="+username, true);
			xmlhttp.send();
		}
	}
	function inserisciUtente()
	{
		var nome=document.getElementById('nuovoNomeUtente').innerHTML;
		var cognome=document.getElementById('nuovoCognomeUtente').innerHTML;
		var username=document.getElementById('nuovoUsernameUtente').innerHTML;
		
		if(nome=='' || cognome=='' || username=='')	
			document.getElementById('risultatoInserimento').innerHTML="<b style='color:red'>Tutti i campi sono obbligatori</b>";
		else
		{					
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					if(this.responseText.indexOf("inserito")>0)
						document.getElementById('btnElencoUtenti').click();
					else
						document.getElementById('risultatoInserimento').innerHTML= this.responseText;
				}
			};
			xmlhttp.open("POST", "inserisciUtente.php?nome="+nome+"&cognome="+cognome+"&username="+username, true);
			xmlhttp.send();
		}
	}
	function graficoProduzione()
	{
		document.getElementById('btnGraficoProduzione').style.color="#3367d6";
		document.getElementById('btnGraficoProduzione').style.boxShadow=" 5px 5px 10px #9c9e9f";
		document.getElementById('tabelleGestisciLinea').innerHTML= '<div id="chartContainer" style="height: 370px; width: 100%;margin-top:10px"></div>';
		creaGrafico() ;
	}
	function statisticheProduzione()
	{
		newCircleSpinner("Caricamento in corso...");
		document.getElementById('btnStatisticheProduzione').style.color="#3367d6";
		document.getElementById('btnStatisticheProduzione').style.boxShadow=" 5px 5px 10px #9c9e9f";
		
		document.getElementById('tabelleGestisciLinea').innerHTML='<div id="divStatistiche" style="height: 370px; width: 25%;display:inline-block;float:left;margin-top:10px"></div><div id="divTorta" style="height: 370px; width: 75%;display:inline-block;float:right;margin-top:10px"></div>';
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('divStatistiche').innerHTML= this.responseText;
				document.getElementById('divTorta').innerHTML= '<div id="chartContainer2" style="height: 370px; width: 100%;display:inline-block;float:right;margin-top:10px"></div>';
				creaGraficoTorta() ;
				removeCircleSpinner();
			}
		};
		xmlhttp.open("POST", "statisticheProduzione.php?", true);
		xmlhttp.send();
	}
	function svuotaLinea()
	{
		if (confirm("ATTENZIONE!\n\n Lo svuotamento comporterà la perdita di tutti i dati dei pannelli che dovranno essere tolti manualmente dalla linea. \n\nQuesta procedura deve essere seguita da un reset macchina.\n\nIl programma si riavviera su tutti i monitor.\n")) 
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					document.getElementById('risultatoManagement').style.width="300px";
					document.getElementById('risultatoManagement').innerHTML= this.responseText;
					document.getElementById('btnPannelliInProduzione').click();
					setTimeout(function()
					{ 
						document.getElementById('risultatoManagement').innerHTML= "";
						document.getElementById('risultatoManagement').style.width="0px";
						eliminaFlagSvuotaLinea();
					}, 3000);
				}
			};
			xmlhttp.open("POST", "svuotaLinea.php?", true);
			xmlhttp.send();
		} 
	}
	function eliminaFlagSvuotaLinea()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				
			}
		};
		xmlhttp.open("POST", "eliminaFlagSvuotaLinea.php?", true);
		xmlhttp.send();
	}
	function gestisciLotti()
	{
		document.getElementById('tabelleManagement').style.width="100%";
		getTable("lotti","dataImportazione","DESC");
	}
	function getTable(table,orderBy,orderType)
    {
        if(table=="lotti")
        {
            getEditableTable
            ({
                table:'lotti',
                editable: false,
				container:'tabelleManagement',
				noFilterColumns:['dataImportazione','completato','chiuso','producibile'],
                orderBy:orderBy,
                orderType:orderType
            });
        }
	}
	function editableTableLoad()
	{
		var blockActionBar=document.createElement("div");
		blockActionBar.setAttribute("class","blockActionBar");
		blockActionBar.setAttribute("style","margin-bottom:10px");

		var absoluteActionBarElement=document.createElement("div");
		absoluteActionBarElement.setAttribute("class","absoluteActionBarElement");
		absoluteActionBarElement.innerHTML='Righe: <span id="rowsNumEditableTable"></span>';
		blockActionBar.appendChild(absoluteActionBarElement);

		/*var absoluteActionBarButton=document.createElement("div");
		absoluteActionBarButton.setAttribute("class","absoluteActionBarButton");
		absoluteActionBarButton.setAttribute("onclick","scaricaExcel('tabelleManagement')");
		absoluteActionBarButton.innerHTML='Esporta <i style="margin-left:5px;color:green" class="far fa-file-excel"></i>';
		blockActionBar.appendChild(absoluteActionBarButton);*/

		var absoluteActionBarButton=document.createElement("div");
		absoluteActionBarButton.setAttribute("class","absoluteActionBarButton");
		absoluteActionBarButton.setAttribute("onclick","resetFilters();getTable(selectetTable)");
		absoluteActionBarButton.innerHTML='Ripristina <i style="margin-left:5px" class="fal fa-filter"></i>';
		blockActionBar.appendChild(absoluteActionBarButton);

		$("#tabelleManagement").prepend(blockActionBar);

		if(selectetTable=="lotti")
		{
			var table=document.getElementById("myTable"+selectetTable);


            for (var i = 1, row; row = table.rows[i]; i++)
            {
				var lotto=row.cells[0].innerText;
				var chiuso=row.cells[3].innerText;
				var completato=row.cells[4].innerText;
				var producibile=row.cells[5].innerText;
				
				if(chiuso.indexOf("false")>-1)
				{
					row.cells[3].innerHTML='<b style="color:red;margin-left:55px;">X</b>';
				}
				else
				{
					var buttonRiapri=document.createElement("button");
					buttonRiapri.setAttribute("class","btnRiapriLottoModifica");
					buttonRiapri.setAttribute("onclick","riapriLottoModifica('"+lotto+"')");
					buttonRiapri.innerHTML="Riapri";
					row.cells[3].innerHTML='';
					row.cells[3].appendChild(buttonRiapri);
				}
				if(completato.indexOf("false")>-1)
				{
					row.cells[4].innerHTML='<b style="color:red;">X</b>';
				}
				else
				{
					row.cells[4].innerHTML='<b style="color:green;">V</b>';
				}
				if(producibile.indexOf("true")>-1)
				{
					row.cells[5].innerHTML='<b style="color:green;margin-left:55px;">V</b>';
				}
				else
				{
					var buttonForzaProducibile=document.createElement("button");
					buttonForzaProducibile.setAttribute("class","btnRiapriLottoModifica");
					buttonForzaProducibile.setAttribute("onclick","forzaProducibile('"+lotto+"')");
					buttonForzaProducibile.innerHTML="Forza producibillità";
					row.cells[5].innerHTML='';
					row.cells[5].appendChild(buttonForzaProducibile);
				}
            }
		}
	}
	/*function riapriLotto()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('tabelleManagement').style.width="100%";
				document.getElementById('tabelleManagement').innerHTML= this.responseText;
			}
		};
		xmlhttp.open("POST", "riapriLotto.php?", true);
		xmlhttp.send();
	}*/
	function riapriLottoModifica(lotto)
	{
		//var lotto=document.getElementById('riapriLotto'+i).innerHTML;
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				gestisciLotti();
			}
		};
		xmlhttp.open("POST", "riapriLottoModifica.php?lotto="+lotto, true);
		xmlhttp.send();
	}
	function forzaProducibile(lotto)
	{
		//var lotto=document.getElementById('riapriLotto'+i).innerHTML;
		
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				gestisciLotti();
			}
		};
		xmlhttp.open("POST", "forzaProducibile.php?lotto="+lotto, true);
		xmlhttp.send();
	}
	function chiudiTabelleManagement()
	{
		document.getElementById('tabelleManagement').innerHTML= "";
		document.getElementById('tabelleManagement').style.width="0px";
	}
	function aggiungiPannelliLotto(lotto)
	{
		newCircleSpinner("Caricamento in corso...");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				document.getElementById('tabelleManagement').style.width="100%";
				document.getElementById('tabelleManagement').innerHTML= this.responseText;
				removeCircleSpinner();
			}
		};
		xmlhttp.open("POST", "aggiungiPannelliLotto.php?lotto="+lotto, true);
		xmlhttp.send();
	}
	function aggiungiPannelliModifica(i)
	{
		var lotto=document.getElementById('aggiungiPannelli'+i).innerHTML;
		var codpan=document.getElementById('aggiungiPannelliCodpan'+i).value;
		var qnt=document.getElementById('aggiungiPannelliQnt'+i).value;
		var finitura=document.getElementById('aggiungiPannelliFinitura'+i).value;
		
		if(codpan=='' || qnt=='' || finitura=='' || qnt==0)
		{
			document.getElementById('risultatoManagement').style.width="300px";
			setTimeout(function()
			{ 
				document.getElementById('risultatoManagement').innerHTML= "Tutti i campi sono obbligatori";
			}, 600);
			setTimeout(function()
			{ 
				document.getElementById('risultatoManagement').innerHTML= "";
				document.getElementById('risultatoManagement').style.width="0px";
			}, 2000);
		}
		else
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					document.getElementById('risultatoManagement').style.width="300px";
					document.getElementById('risultatoManagement').style.lineHeight= "50px";
					var r=this.responseText;
					setTimeout(function()
					{ 
						document.getElementById('risultatoManagement').innerHTML= r;
						document.getElementById('aggiungiPannelliCodpan'+i).value="+K4PN";
						document.getElementById('aggiungiPannelliQnt'+i).value="";
						document.getElementById('aggiungiPannelliFinitura'+i).value="";
					}, 600);
					setTimeout(function()
					{ 
						document.getElementById('risultatoManagement').innerHTML= "";
						document.getElementById('risultatoManagement').style.width="0px";
						document.getElementById('risultatoManagement').style.lineHeight= "100px";
					}, 3000);
				}
			};
			xmlhttp.open("POST", "aggiungiPannelliModifica.php?lotto="+lotto+"&codpan="+codpan+"&qnt="+qnt+"&finitura="+finitura, true);
			xmlhttp.send();
		}
	}
	function riavviaProgrammi()
	{
		if (confirm("ATTENZIONE!\n\nIl programma si riavviera su tutti i monitor.\n")) 
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					document.getElementById('risultatoManagement').style.width="300px";
					document.getElementById('risultatoManagement').innerHTML= this.responseText;
					setTimeout(function()
					{ 
						document.getElementById('risultatoManagement').innerHTML= "";
						document.getElementById('risultatoManagement').style.width="0px";
						eliminaFlagSvuotaLinea();
					}, 3000);
				}
			};
			xmlhttp.open("POST", "riavviaProgrammi.php?", true);
			xmlhttp.send();
		}
	}
	function toggleAngoli()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				if(this.responseText!="ok")
					window.alert(this.responseText);
			}
		};
		xmlhttp.open("POST", "toggleAngoli.php?", true);
		xmlhttp.send();
	}
	function getFlagLanaAngolo()
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				if(this.responseText==1)
				{
					document.getElementById("someSwitchOptionPrimary").checked = true;
				}
				if(this.responseText==0)
				{
					document.getElementById("someSwitchOptionPrimary").checked = false;
				}
			}
		};
		xmlhttp.open("POST", "getFlagLanaAngolo.php?", true);
		xmlhttp.send();
	}