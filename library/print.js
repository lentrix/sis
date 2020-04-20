function printThisDiv(xframe, xdiv){
	try{
		var oIframe = document.getElementById(xframe);
		var oContent = document.getElementById(xdiv).innerHTML;
		var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
		if (oDoc.document) oDoc = oDoc.document;
		oDoc.write("<html><head><link rel='stylesheet' href='../css/print.css' type='text/css'><title>title</title>");
		oDoc.write("<style> * { font-size: 8pt; }</style>");
		oDoc.write("</head><body onload='this.focus(); this.print();'>");
		oDoc.write(oContent + "</body></html>");
		oDoc.close();
	}catch(e){
			alert(e);
		}
	}
}
