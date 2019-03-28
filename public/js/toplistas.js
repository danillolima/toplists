
"use strict";

/*\
|*|
|*|  :: XMLHttpRequest.prototype.sendAsBinary() Polifyll ::
|*|
|*|  https://developer.mozilla.org/en-US/docs/DOM/XMLHttpRequest#sendAsBinary()
\*/

if (!XMLHttpRequest.prototype.sendAsBinary) {
  XMLHttpRequest.prototype.sendAsBinary = function (sData) {
    var nBytes = sData.length, ui8Data = new Uint8Array(nBytes);
    for (var nIdx = 0; nIdx < nBytes; nIdx++) {
      ui8Data[nIdx] = sData.charCodeAt(nIdx) & 0xff;
    }
    /* send as ArrayBufferView...: */
    this.send(ui8Data);
    /* ...or as ArrayBuffer (legacy)...: this.send(ui8Data.buffer); */
  };
}

/*\
|*|
|*|  :: AJAX Form Submit Framework ::
|*|
|*|  https://developer.mozilla.org/en-US/docs/DOM/XMLHttpRequest/Using_XMLHttpRequest
|*|
|*|  This framework is released under the GNU Public License, version 3 or later.
|*|  http://www.gnu.org/licenses/gpl-3.0-standalone.html
|*|
|*|  Syntax:
|*|
|*|   AJAXSubmit(HTMLFormElement);
\*/

var AJAXSubmit = (function () {

  function ajaxSuccess () {
		/* console.log("AJAXSubmit - Success!"); */
		let result = document.querySelector('#response');
		result.text = this.responseText;
		//alert(this.responseText);
    /* you can get the serialized data through the "submittedData" custom property: */
    /* alert(JSON.stringify(this.submittedData)); */
  }

  function submitData (oData) {
    /* the AJAX request... */
    var oAjaxReq = new XMLHttpRequest();
    oAjaxReq.submittedData = oData;
    oAjaxReq.onload = ajaxSuccess;
    if (oData.technique === 0) {
      /* method is GET */
      oAjaxReq.open("get", oData.receiver.replace(/(?:\?.*)?$/, oData.segments.length > 0 ? "?" + oData.segments.join("&") : ""), true);
      oAjaxReq.send(null);
    } else {
      /* method is POST */
      oAjaxReq.open("post", oData.receiver, true);
      if (oData.technique === 3) {
        /* enctype is multipart/form-data */
        var sBoundary = "---------------------------" + Date.now().toString(16);
        oAjaxReq.setRequestHeader("Content-Type", "multipart\/form-data; boundary=" + sBoundary);
        oAjaxReq.sendAsBinary("--" + sBoundary + "\r\n" + oData.segments.join("--" + sBoundary + "\r\n") + "--" + sBoundary + "--\r\n");
      } else {
        /* enctype is application/x-www-form-urlencoded or text/plain */
        oAjaxReq.setRequestHeader("Content-Type", oData.contentType);
        oAjaxReq.send(oData.segments.join(oData.technique === 2 ? "\r\n" : "&"));
      }
    }
  }

  function processStatus (oData) {
    if (oData.status > 0) { return; }
    /* the form is now totally serialized! do something before sending it to the server... */
    /* doSomething(oData); */
    /* console.log("AJAXSubmit - The form is now serialized. Submitting..."); */
    submitData (oData);
  }

  function pushSegment (oFREvt) {
    this.owner.segments[this.segmentIdx] += oFREvt.target.result + "\r\n";
    this.owner.status--;
    processStatus(this.owner);
  }

  function plainEscape (sText) {
    /* how should I treat a text/plain form encoding? what characters are not allowed? this is what I suppose...: */
    /* "4\3\7 - Einstein said E=mc2" ----> "4\\3\\7\ -\ Einstein\ said\ E\=mc2" */
    return sText.replace(/[\s\=\\]/g, "\\$&");
  }

  function SubmitRequest (oTarget) {
    var nFile, sFieldType, oField, oSegmReq, oFile, bIsPost = oTarget.method.toLowerCase() === "post";
    /* console.log("AJAXSubmit - Serializing form..."); */
    this.contentType = bIsPost && oTarget.enctype ? oTarget.enctype : "application\/x-www-form-urlencoded";
    this.technique = bIsPost ? this.contentType === "multipart\/form-data" ? 3 : this.contentType === "text\/plain" ? 2 : 1 : 0;
    this.receiver = oTarget.action;
    this.status = 0;
    this.segments = [];
    var fFilter = this.technique === 2 ? plainEscape : escape;
    for (var nItem = 0; nItem < oTarget.elements.length; nItem++) {
      oField = oTarget.elements[nItem];
      if (!oField.hasAttribute("name")) { continue; }
      sFieldType = oField.nodeName.toUpperCase() === "INPUT" ? oField.getAttribute("type").toUpperCase() : "TEXT";
      if (sFieldType === "FILE" && oField.files.length > 0) {
        if (this.technique === 3) {
          /* enctype is multipart/form-data */
          for (nFile = 0; nFile < oField.files.length; nFile++) {
            oFile = oField.files[nFile];
            oSegmReq = new FileReader();
            /* (custom properties:) */
            oSegmReq.segmentIdx = this.segments.length;
            oSegmReq.owner = this;
            /* (end of custom properties) */
            oSegmReq.onload = pushSegment;
            this.segments.push("Content-Disposition: form-data; name=\"" + oField.name + "\"; filename=\""+ oFile.name + "\"\r\nContent-Type: " + oFile.type + "\r\n\r\n");
            this.status++;
            oSegmReq.readAsBinaryString(oFile);
          }
        } else {
          /* enctype is application/x-www-form-urlencoded or text/plain or method is GET: files will not be sent! */
          for (nFile = 0; nFile < oField.files.length; this.segments.push(fFilter(oField.name) + "=" + fFilter(oField.files[nFile++].name)));
        }
      } else if ((sFieldType !== "RADIO" && sFieldType !== "CHECKBOX") || oField.checked) {
        /* field type is not FILE or is FILE but is empty */
        this.segments.push(
          this.technique === 3 ? /* enctype is multipart/form-data */
            "Content-Disposition: form-data; name=\"" + oField.name + "\"\r\n\r\n" + oField.value + "\r\n"
          : /* enctype is application/x-www-form-urlencoded or text/plain or method is GET */
            fFilter(oField.name) + "=" + fFilter(oField.value)
        );
      }
    }
    processStatus(this);
  }

  return function (oFormElement) {
    if (!oFormElement.action) { return; }
    new SubmitRequest(oFormElement);
  };

})();


function toggleClass(el){
	console.log(el);
	el = document.querySelector(el);
	var className = 'hidden';
	if (el.classList) {
		el.classList.toggle(className);
	} else {
		var classes = el.className.split(' ');
		var existingIndex = classes.indexOf(className);
	
		if (existingIndex >= 0)
			classes.splice(existingIndex, 1);
		else
			classes.push(className);
	
		el.className = classes.join(' ');
	}
}
/*

$('form#form-item').submit(function (ev) {

	ev.preventDefault();
	var data = new FormData(this);

	//	if(blob !== undefined )
		data.append("img-form", blob);

		$.ajax({
			type: "POST",
            enctype: 'multipart/form-data',
            url: "/l/insertInList",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
						success: function (data) {
								$("#response").addClass("alert alert-success");
                $("#response").text(data);
                console.log("SUCCESS : ", data);
            },
            error: function (ev) {
								$("#response").addClass("alert alert-danger");
                $("#response").text(ev.responseText);
                console.log("ERROR : ", ev);
            }
		});
	});

*/

function voteOn(vote_in, value, username){
	var httpRequest;
	if (window.XMLHttpRequest) {
	    httpRequest = new XMLHttpRequest();
	} else if (window.ActiveXObject) { 
	    httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = '/l/vote/';
	var data = 'votein=' + vote_in + '&votevalue=' + value + '&user=' + username;
	httpRequest.open('POST', url, true);
	httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest ');
	httpRequest.send(data);
	
	httpRequest.onreadystatechange = function(){
		if (httpRequest.readyState === 4) {
				//alert(httpRequest.responseText);
			var bUp = document.querySelector('#' + JSON.parse(httpRequest.responseText).id + ' .upbutton')
			var bDown = document.querySelector('#' + JSON.parse(httpRequest.responseText).id + ' .downbutton')

				if(value == 1){
					/*
					document.querySelector('#' + JSON.parse(httpRequest.responseText).id + ' .downbutton').popover("hide");
					document.querySelector('#' + JSON.parse(httpRequest.responseText).id + ' .upbutton').popover({content: JSON.parse(httpRequest.responseText).message}, 'show');
					document.querySelector('#' + JSON.parse(httpRequest.responseText).id + ' .upbutton').popover("toggle");
				*/
				bUp.setAttribute('aria-label', JSON.parse(httpRequest.responseText).message);
			}
				if(value == -1){
					bDown.setAttribute('aria-label', JSON.parse(httpRequest.responseText).message);
					/*
					$('#' + JSON.parse(httpRequest.responseText).id + ' .upbutton').popover("hide");
					$('#' + JSON.parse(httpRequest.responseText).id + ' .downbutton').popover({content: JSON.parse(httpRequest.responseText).message}, 'show');
					$('#' + JSON.parse(httpRequest.responseText).id + ' .downbutton').popover("toggle");
				*/}
				$('#' + JSON.parse(httpRequest.responseText).id + ' .numero-votos').text('('+JSON.parse(httpRequest.responseText).numero_votos + ' Votos)');
				//$('#modal-conteudo').text(JSON.parse(httpRequest.responseText).message);
				console.log('#' + JSON.parse(httpRequest.responseText).id + '> div.upbutton');
				//$('#modalAuxiliar').modal('show');
		}
	}
}
$(function(){
	$('#validationCustomUsername').keyup(
		function(){
			var username_value = this.value;
			var regex = /^[a-zA-Z0-9_]{5,}[a-zA-Z]+[0-9]*$/g;
			var regex_exp = new RegExp(regex);
			if (regex_exp.test(username_value)) {
				$('#username').addClass('sucessInput');
			}
			else{
				$('#username').addClass('errorInput');
			}
		//	alert('Great, you entered an E-Mail-address');
		}
	);
	});





	document.addEventListener('DOMContentLoaded', () => {
function demoUpload() {
	
	var blob
	let croppieEl = document.getElementById('croppieImg')
	let croppieImg;
	console.log('aa2')
	function readFile(input) {
		//precisamos de somente uma instancia do croppie
		if(croppieImg != undefined)
			croppieImg.destroy();

		console.log('aaaa1')

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				let imageUp = new Image();
				imageUp.src = e.target.result;
				imageUp.onload = function(){
					addClass(croppieEl, 'ready');	
					if(imageUp.naturalWidth>imageUp.naturalHeight){
						// imagem retangular e manter rentagular
						croppieImg = new Croppie(croppieEl, {
							url: imageUp.src,
							viewport: {
								width: 640,
								height: 480,
								type: 'square'
							},
							boundary: {
								width: 640,
								height: 540
							},
						});
					}
					else{
						//imagem quadrada ou retangular em pé, manter quadrado
						croppieImg = new Croppie(croppieEl, {
						url: imageUp.src,
							viewport: {
								//360x360
								width: 360,
								height: 360,
								type: 'square'
								},
							boundary: {
								width: 540,
								height: 540
							},
						});
					}
					croppieEl.addEventListener('update', function(ev) { 
										croppieImg.result('base64').then(function(blob) {
											$('#img-preview').attr('src', blob);
									});
								});
			}			
				
		}
		reader.readAsDataURL(input.files[0]);
}
				else {
					swal("Seu navegador não suporta o FileReader API");
			}
	}
	

	croppieEl.addEventListener('update', function(ev) { 
		croppieImg.result('base64').then(function(blob) {
			$('#img-preview').attr('src', blob);
		});
	});
	document.querySelector('#up-img').addEventListener('change', function(){
		readFile(this)
	})
} 
demoUpload();
	})


function toggleVideo(){
	hideTL('#img-form')
	showTL('#url-video')
	showTL('#video-preview')
	hideTL('#img-preview')
	
	var el = document.querySelector('#url-video');
	
	el.addEventListener('change', function(){
		var youtube = document.querySelector('#video-youtube');
		var input = document.querySelector('#url-video').value;
	
		var embed = '//www.youtube.com/embed/' + 	getIdYoutube(input);
		youtube.src = embed;
	});
}

function getIdYoutube(url) {
	var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
	var match = url.match(regExp);

	if (match && match[2].length == 11) {
			return match[2];
	} else {
			return 'error';
	}
}

function toggleImg(){
	showTL('#img-form');
	hideTL('#url-video');
	hideTL('#video-preview');
	showTL('#img-preview');	
}

function hideTL(id){
	var el = document.querySelector(id);
	el.style.display = 'none';
}
function showTL(id){
	var el = document.querySelector(id);
	el.style.display = '';
}

function addClass(el, className){
	console.log('aa')
	if (el.classList)
		el.classList.add(className);
	else
		el.className += ' ' + className;
}

$(document).ready(function(){
		$("#txtName").keyup(function(){
				var name = $("#txtName").val();
				$("#title-preview").text(name);
		});

		$("#txtDescription").keyup(function(){
				var description = $("#txtDescription").val();
				$("#description-preview").text(description);
		});
});

document.addEventListener('DOMContentLoaded', () => {
	var list
	var autoComplete = new Awesomplete(document.querySelector("#autoname input"),{ list: list });
	var ajax = new XMLHttpRequest();
	txtName.addEventListener('keydown', () => {
		var params = 'busca=' + txtName.value;
		ajax.open("POST", "/item/search", true);
		ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajax.onload = function(e) {
			var list = JSON.parse(ajax.responseText).map(function(i) { return i.name; });
			if(list.length)
				console.log('achou algo');
				else 
				console.log('achou nada');
			autoComplete.list = list;
		};
		ajax.send(params);
	});
	var el = document.querySelector("#autoname");
			el.addEventListener('awesomplete-selectcomplete', function(){
				hideTL('#hideD')
				var params = 'busca=' + txtName.value;
				ajax.open("POST", "/item/searchComplete", true);
				ajax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				ajax.onload = function(e) {
					console.log(ajax.responseText);
				};
		ajax.send(params);
			});
});

