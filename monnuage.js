window.downloadFile = function(sUrl) {

	//iOS devices do not support downloading. We have to inform user about this.
	if (/(iP)/g.test(navigator.userAgent)) {
		alert('Your device do not support files downloading. Please try again in desktop browser.');
		return false;
	}

	//If in Chrome or Safari - download via virtual link click
	if (window.downloadFile.isChrome || window.downloadFile.isSafari) {
		//Creating new link node.
		var link = document.createElement('a');
		link.href = sUrl;

		if (link.download !== undefined) {
			//Set HTML5 download attribute. This will prevent file from opening if supported.
			var fileName = sUrl.substring(sUrl.lastIndexOf('/') + 1, sUrl.length);
			link.download = fileName;
		}

		//Dispatching click event.
		if (document.createEvent) {
			var e = document.createEvent('MouseEvents');
			e.initEvent('click', true, true);
			link.dispatchEvent(e);
			return true;
		}
	}

	// Force file download (whether supported by server).
	var query = '?download';

	window.open(sUrl + query, '_self');
}

window.downloadFile.isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
window.downloadFile.isSafari = navigator.userAgent.toLowerCase().indexOf('safari') > -1;

$(document).ready(function() {

	/***************
		Â¨PARCOURIR LES REPERTOIRES 
		            ***************/

	$("body").on('click', '#linkdossier', function() {

		var link = $(this).attr("link");

		var dossier = $("body").find("[link ='" + link + "']");
		dossier.show();
		$(this).parent().parent().hide();
	});


	$("body").on('click', '.headRetour', function() {
		var link = $(this).attr("target");

		var dossier = $("body").find("[link ='" + link + "']");
		dossier.show();
		$(this).parent().hide();
	});

	/***************
		 Menu d'un fichier
		            ***************/



	$("body").on('mousedown', '#menuFile', function(e) {

		lien = $(this).attr("lien");
		fileName = $(this).children(".col-name").html();
		if (e.which === 3) {
			$("#telecharger").attr("lien", lien);
			$("#streaming").attr("lien", lien);
			$("#titlepop").html(fileName);
			$("#titlepop").attr("title", fileName);
			$("#pop").show()
				.css('left', e.pageX + 2)
				.css('top', e.pageY);
		} else {
			$("#pop").hide();
		}
	});

	$("#telecharger").on('click', function() {
		str = $(this).attr("lien");
		res = str.replace("&nbsp;", str)
		downloadFile(res);

	});

	$("#streaming").on('click', function(){
		str = $(this).attr("lien");
		window.open(str);
  		
	});

	$("body").on('click', function(e) {
		if (e.which != 3)
			$("#pop").hide();

	});



	$(document).on("contextmenu", function(e) {
		if (e.target.nodeName != "INPUT" && e.target.nodeName != "TEXTAREA") {

			e.preventDefault();
		}

	});

});