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
function setAppointmentStatus(status, message, type) {
	status.textContent = message;
	status.className = 'appointment-form__status';
	if (type) status.className += ' appointment-form__status--' + type;
}

function submitAppointmentForm(form) {
	var status = document.getElementById('appointment-form-status');
	var submit = form.querySelector('button[type="submit"]');
	var errorMessage = 'Не удалось отправить заявку. Пожалуйста, позвоните нам по телефону.';

	if (form.dataset.submitting === 'true') return false;

	setAppointmentStatus(status, '', '');

	if (!form.checkValidity()) {
		form.reportValidity();
		setAppointmentStatus(status, 'Пожалуйста, заполните все обязательные поля.', 'error');
		return false;
	}

	form.dataset.submitting = 'true';
	submit.disabled = true;
	submit.setAttribute('aria-busy', 'true');
	setAppointmentStatus(status, 'Отправляем заявку…', 'pending');

	var controller = new AbortController();
	var timeout = window.setTimeout(function() { controller.abort(); }, 15000);

	fetch(form.action, {
		method: 'POST',
		body: new FormData(form),
		headers: {'X-Requested-With': 'XMLHttpRequest'},
		signal: controller.signal
	})
		.then(function(response) {
			return response.text().then(function(text) {
				var data;

				if (!text.trim()) throw new Error(errorMessage);

				try {
					data = JSON.parse(text);
				} catch (error) {
					throw new Error(errorMessage);
				}

				if (!response.ok || data.success !== true) {
					throw new Error(data.message || errorMessage);
				}

				return data;
			});
		})
		.then(function(data) {
			form.reset();
			setAppointmentStatus(status, data.message, 'success');
		})
		.catch(function(error) {
			setAppointmentStatus(status, error.message || errorMessage, 'error');
		})
		.finally(function() {
			window.clearTimeout(timeout);
			delete form.dataset.submitting;
			submit.disabled = false;
			submit.removeAttribute('aria-busy');
		});

	return false;
}

function mailSend(event) {
	if (event) event.preventDefault();

	var form = document.getElementById('appointment-form');
	if (form) submitAppointmentForm(form);

	return false;
}

function initAppointmentForm() {
	var form = document.getElementById('appointment-form');
	if (!form || form.dataset.submitHandler === 'ready') return;

	form.dataset.submitHandler = 'ready';
	form.addEventListener('submit', function(event) {
		event.preventDefault();
		submitAppointmentForm(form);
	});
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initAppointmentForm);
} else {
	initAppointmentForm();
}
// PHOTOGALLERY
function pgScroll(val) {
  document.querySelector('ul#phg').scrollBy({ 
    left: val,
    behavior: 'smooth' 
  });
}
