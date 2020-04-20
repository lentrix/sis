var xmlHttp = createXmlHttpRequestObject();

function createXmlHttpRequestObject()
{
	var xmlHttp;
	try{
		xmlHttp = new XMLHttpRequest();
	}catch(e){
		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
										"MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP");
		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++){
			try{
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}catch (e) {}
		}
	}
	if (!xmlHttp)
		alert("Error creating the XMLHttpRequest object.");
	else
		return xmlHttp;
}

//--------------------------------------------------------------------------------------------------------------------

function request(target, recipientID)
{
	if (xmlHttp){
		try{
			params = target + "&recipientID=" + recipientID;
			xmlHttp.open("GET", params, true);
			xmlHttp.onreadystatechange = handleRequest;
			xmlHttp.send(null);
		}catch (e){
			alert("Can't connect to server:\n" + e.toString() + ": " + idbooking);
		}
	}
}

function handleRequest(){
	if (xmlHttp.readyState == 4){
		if (xmlHttp.status == 200){
			try{
				response = xmlHttp.responseText;
				idbooking = response.substring(response.indexOf("[")+1, response.indexOf("]"));
				myDiv = document.getElementById(idbooking);
				myDiv.innerHTML=response;
				
			}catch(e){
				alert("Error reading the response: " + e.toString() + " recipientID=" + idbooking);
			}
		}else{
			alert("[handleRequest] There was a problem retrieving the data:\n" + xmlHttp.statusText);
		}
	}
}
