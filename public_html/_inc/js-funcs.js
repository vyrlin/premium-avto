// FORMS
function inputFile(id) {
	var file = document.getElementById(id).value; 
	document.getElementById('file_'+id).innerHTML = file;
	document.getElementById('file_'+id).style.visibility = 'visible';
	document.getElementById('file_'+id).style.display = 'block';
	
}
function loadPic(formName) {
	document.getElementById(formName).submit();
}
function showHideDiv(divId) { 
	var v = document.getElementById(divId).style.display;
	if (v == 'block') {
		document.getElementById(divId).style.visibility = 'hidden';
		document.getElementById(divId).style.display = 'none';
	} else {
		document.getElementById(divId).style.visibility = 'visible';
		document.getElementById(divId).style.display = 'block';
	}
}

function showModal(title='', content='', buttons='') { 
	document.getElementById('modal-wrap').style.visibility = 'visible'; 
	document.getElementById('modal-wrap').style.display = 'block';
	document.getElementById('modal').style.display = 'inline-block';
	if((title!='') && (content!='')) {
		document.getElementById('modal').innerHTML = '<div id="modal-top"><span id="modal-title">'+title+'</span><span id="modal-close"><a href="#" id="a-modal-close" onClick="closeModal(); return false;">X</a></span></div>';
		document.getElementById('modal').innerHTML += '<div id="modal-content">'+content+'</div>';
		if(buttons!='') {
			document.getElementById('modal').innerHTML += '<div id="modal-buttons"><span>'+buttons+'</span></div>';
		} else {
			document.getElementById('modal').innerHTML += '<div id="modal-buttons"><input type="button" value="Ok" onClick="closeModal(); return false;"></div>';
		}
	}
	var x = window.scrollX; var y = window.scrollY;
    window.onscroll = function() { window.scrollTo(x, y); };
	document.addEventListener("keydown", function(e) { 
		if(e.keyCode===27) closeModal();
	});
}
function closeModal() {
	document.getElementById('modal-wrap').style.visibility = 'hide'; 
	document.getElementById('modal-wrap').style.display = 'none';
	document.getElementById('modal').style.display = 'none';
	document.getElementById('body-main').style.overflow = 'auto';
	window.onscroll = function() {};
}
function showModals(id, title='', content='') { 
	document.getElementById('modals-wrap_'+id).style.visibility = 'visible'; 
	document.getElementById('modals-wrap_'+id).style.display = 'block';
	document.getElementById('modal_'+id).style.display = 'inline-block';
	if (title != '') document.getElementById('modal-title_'+id).innerHTML = title;
	if (content != '') document.getElementById('modal-content_'+id).innerHTML = content;
	var x = window.scrollX; var y = window.scrollY;
    window.onscroll = function() { window.scrollTo(x, y); };
	document.addEventListener("keydown", function(e) { 
		if(e.keyCode===27) closeModals(id);
	});
}
function closeModals(id) {
	document.getElementById('modals-wrap_'+id).style.visibility = 'hide'; 
	document.getElementById('modals-wrap_'+id).style.display = 'none';
	document.getElementById('modal_'+id).style.display = 'none';
	document.getElementById('body-main').style.overflow = 'auto';
	window.onscroll = function() {};
}

// MAIL
function mailSend() {
	var name = document.getElementById('mail_name').value; 
	var contact = document.getElementById('mail_contact').value; 
	var txt = document.getElementById('mail_text').value; 
	if ((name != '') && (contact != '') && (txt != '')) {
		var data = new Map([['name', name], ['contact', contact], ['txt', txt]]);
		var postData = new FormData();
		data.forEach(function(value, key) {
			postData.append(key, value);
		});
		var request = new XMLHttpRequest();
		request.open('POST', "/_ajax/mailsend.php"); 
		request.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) { 
				//var res = JSON.parse(this.responseText);
				closeModal();
				//alert(res);
				alert('Спасибо, ваше сообщение отправлено.');
			}
		};
		request.send(postData);
	} else { alert('Не заполнено обязательное поле!'); }
}
// PHOTOGALLERY
function pgScroll(val) {
  document.querySelector('ul#phg').scrollBy({ 
    left: val,
    behavior: 'smooth' 
  });
}