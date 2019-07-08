function formhash2(form, password) {
    // Crea una entrada de elemento nuevo, esta será nuestro campo de contraseña con hash. 
    var p2 = document.createElement("input");
 
    // Agrega el elemento nuevo a nuestro formulario.
	
    form.appendChild(p2);
    p2.name = "p2";
    p2.type = "hidden";
	
	p2.value = hex_sha512(password.value);
	
	if(password.value == "") p2.value = "";
	
    // Asegúrate de que la contraseña en texto simple no se envíe. 
    password.value = "";
 
    // Finalmente envía el formulario. 
    form.submit();
}

function formhashchange(form, password, passwordn, passwordrn) {
    // Crea una entrada de elemento nuevo, esta será nuestro campo de contraseña con hash. 
    var p = document.createElement("input");
    var pn = document.createElement("input");
    var prn = document.createElement("input");
 
    // Agrega el elemento nuevo a nuestro formulario. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);

    form.appendChild(pn);
    pn.name = "pn";
    pn.type = "hidden";
    pn.value = hex_sha512(passwordn.value);

    form.appendChild(prn);
    prn.name = "prn";
    prn.type = "hidden";
    prn.value = hex_sha512(passwordrn.value);
 
    // Asegúrate de que la contraseña en texto simple no se envíe. 
    password.value = "";
    passwordn.value = "";
    passwordrn.value = "";
 
    // Finalmente envía el formulario. 
    form.submit();
}

function formhashchangeadmin(form, usuariorp, passwordn, passwordrn, checkrp) {
    // Crea una entrada de elemento nuevo, esta será nuestro campo de contraseña con hash. 
    var pn = document.createElement("input");
    var prn = document.createElement("input");
 
      // Verifica que cada campo tenga un valor
    if (usuariorp.value == '' && (passwordn.value == '' || passwordrn == '')) 
	{
 
        alert('Deberá ingresar un nombre de usuario para registrar pago o una nueva clave!!!');
        return false;
    }
	
	if (passwordn.value == '' || passwordrn == '') 
	{
		// Agrega el elemento nuevo a nuestro formulario. 
		form.appendChild(pn);
		pn.name = "pn";
		pn.type = "hidden";
		pn.value = "";

		form.appendChild(prn);
		prn.name = "prn";
		prn.type = "hidden";
		prn.value = "";
	 
		// Asegúrate de que la contraseña en texto simple no se envíe. 
		passwordn.value = "";
		passwordrn.value = "";		
	}
	else
	{
		// Agrega el elemento nuevo a nuestro formulario. 
		form.appendChild(pn);
		pn.name = "pn";
		pn.type = "hidden";
		pn.value = hex_sha512(passwordn.value);

		form.appendChild(prn);
		prn.name = "prn";
		prn.type = "hidden";
		prn.value = hex_sha512(passwordrn.value);
	 
		// Asegúrate de que la contraseña en texto simple no se envíe. 
		passwordn.value = "";
		passwordrn.value = "";
	}
 
    // Finalmente envía el formulario. 
    form.submit();
}
 
function regformhash(form, uid, email, password, conf, name, surname, documentid) {
     // Verifica que cada campo tenga un valor
    if (uid.value == ''         || 
          email.value == ''     || 
          password.value == ''  || 
          conf.value == ''      ||
          name.value == ''  	||		  
          surname.value == ''	|| 	  
		  documentid.value == '') 
	{
 
        alert('Deberá brindar toda la información solicitada. Por favor, intente de nuevo');
        return false;
    }
 
    // Verifica el nombre de usuario
 
    re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        alert("El nombre de usuario deberá contener solo letras, números y guiones bajos. Por favor, inténtelo de nuevo"); 
        form.username.focus();
        return false; 
    }
	
	
    // Verifica el DNI
 	 
    if(!Number.isInteger(parseInt(form.documentid.value))) { 
        alert("El numero de documento deberá ser un número entero. Por favor, inténtelo de nuevo"); 
        form.documentid.focus();
        return false; 
    }
	
    // Verifica el nombre
 
    re = /^\w+$/; 
    if(!re.test(form.name.value)) { 
        alert("El nombre deberá contener solo letras. Por favor, inténtelo de nuevo"); 
        form.name.focus();
        return false; 
    }

    // Verifica el apellido
 
    re = /^\w+$/; 
    if(!re.test(form.surname.value)) { 
        alert("El apellido deberá contener solo letras. Por favor, inténtelo de nuevo"); 
        form.surname.focus();
        return false; 
    }	
 
    // Verifica que la contraseña tenga la extensión correcta (mín. 6 caracteres)
    // La verificación se duplica a continuación, pero se incluye para que el
    // usuario tenga una guía más específica.
    if (password.value.length < 6) {
        alert('La contraseña deberá tener al menos 6 caracteres. Por favor, inténtelo de nuevo');
        form.password.focus();
        return false;
    }
 
    // Por lo menos un número, una letra minúscula y una mayúscula 
    // Al menos 6 caracteres
 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    if (!re.test(password.value)) {
        alert('Las contraseñas deberán contener al menos un número, una letra minúscula y una mayúscula. Por favor, inténtelo de nuevo');
        return false;
    }
 
    // Verifica que la contraseña y la confirmación sean iguales
    if (password.value != conf.value) {
        alert('La contraseña y la confirmación no coinciden. Por favor, inténtelo de nuevo');
        form.password.focus();
        return false;
    }
 
    // Crea una entrada de elemento nuevo, esta será nuestro campo de contraseña con hash. 
    var p = document.createElement("input");
 
    // Agrega el elemento nuevo a nuestro formulario. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Asegúrate de que la contraseña en texto simple no se envíe. 
    password.value = "";
    conf.value = "";
 
    // Finalmente envía el formulario. 
    form.submit();
    return true;
}

function caracteresCorreoValido(email){
    console.log(email);
    //var email = $(email).val();
    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

    if (caract.test(email) == false){
        return false;
    }else{
        return true;
    }
}

function mayusculasPrimeraLetraPalabras(palabras){
    palabras = palabras.replace(/\b\w/g, function(l){ return l.toUpperCase() });
	
	return palabras;
}

function separarNombreYApellido(nombreYApellido){
	var fullName = nombreYApellido || ""; 
	var result = {}; 
	if (fullName.length > 0) 
	{ 
		var nameTokens = fullName.match(/[A-ZÁ-ÚÑÜ][a-zá-úñü]+|([aeodlsz]+\s+)+[A-ZÁ-ÚÑÜ][a-zá-úñü]+/g) || []; 
		if (nameTokens.length > 3) 
		{ 
			result.name = nameTokens.slice(0, 2).join(' '); 
		} 
		else 
		{ 
			result.name = nameTokens.slice(0, 1).join(' '); 
		} 
		if (nameTokens.length > 2) 
		{ 
			result.lastName = nameTokens.slice(-2, -1).join(' '); 
			result.secondLastName = nameTokens.slice(-1).join(' '); 
		} 
		else 
		{ 
			result.lastName = nameTokens.slice(-1).join(' '); 
			result.secondLastName = ""; 
		} 
	} 
	
	return result; 
}

function separarCalleYNumero(calleYNumero){
	var fullAddress = calleYNumero || ""; 
	var result = {}; 
	if (fullAddress.length > 0) 
	{ 
		var dividCYN = fullAddress.split(" ");
		result.nameAddress = dividCYN.slice(0,-1).join(" ");
		result.numberAddress = dividCYN.slice(-1).join(" ");
	} 
	
	return result; 
}
	