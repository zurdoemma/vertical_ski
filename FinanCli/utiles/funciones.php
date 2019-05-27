<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once 'c:\wamp\www\pls_config.php';

function verificar_usuario($mysqli)
{
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start();
	
	//comprobar expiración sesión usuario
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $GLOBALS['time_expire_sesion'])) 
	{			
		header('Location:/FinanCli/acciones/salir.php?expired_session=200');
		return;
	}	
	else if(isset($_SESSION['username'])) $_SESSION['LAST_ACTIVITY'] = time(); 
	
	//comprobar la existencia del usuario
	if (login_check($mysqli))
	{
		return true;
	}
	else return false;
}

function verificar_permisos_admin()
{
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start(); 
	//comprobar la existencia del usuario
	if ($_SESSION["permisos"] == 1)
	{
		return true;
	}
	else return false;
}

function verificar_permisos_usuario()
{
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start();
	//comprobar la existencia del usuario
	if ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 2 || $_SESSION["permisos"] == 3)
	{
		return true;
	}
	else return false;
}

function verificar_permisos_supervisor()
{
	if(session_status() !== PHP_SESSION_ACTIVE) sec_session_start();
	//comprobar la existencia del usuario
	if ($_SESSION["permisos"] == 1 || $_SESSION["permisos"] == 2)
	{
		return true;
	}
	else return false;
}


function sec_session_start() 
{
    $session_name = 'sec_session_id';   
    $secure = SECURE;
    // Esto detiene que JavaScript sea capaz de acceder a la identificación de la sesión.
    $httponly = true;
	
	// Obliga a las sesiones a solo utilizar cookies.
	if (ini_set('session.use_only_cookies', 1) === FALSE) 
	{
		header("Location: ../sesionusuario.php");
		exit();
	}

	if(ini_set('session.use_strict_mode',1) === FALSE)
	{
		header("Location: ../sesionusuario.php");
		exit();		
	}
	
	if(phpversion() < '7.1.0')
	{
		if(ini_set('session.hash_function', sha512) === FALSE)
		{
			header("Location: ../sesionusuario.php");
			exit();		
		}
	}
	
	if(session_cache_limiter('nocache') === FALSE)
	{
		header("Location: ../sesionusuario.php");
		exit();
	}
	
    // Obtiene los params de los cookies actuales.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
	
    // Configura el nombre de sesión al configurado arriba.
    session_name($session_name);
    session_start();	
    session_regenerate_id();   
}

function checkbrute($user_id, $mysqli) {
    // Obtiene el timestamp del tiempo actual.
    $now = time();
 
	if ($stmt = $mysqli->prepare("SELECT id, valor FROM finan_cli.parametros WHERE id IN (1,2) ORDER BY id")) 
	{
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
 
		// Obtiene las variables del resultado.
		$stmt->bind_result($parameter_id, $parameter_value);
		
		while($stmt->fetch())
		{
			if($parameter_id == 1) $intentos_fallidos = $parameter_value;
			else if($parameter_id == 2) $cantidad_horas_bloqueo = $parameter_value;
		}
		
		if(empty($intentos_fallidos) || empty($cantidad_horas_bloqueo)) return true;
	}
	else return true;
 
    // Todos los intentos de inicio de sesión se cuentan desde las 2 horas anteriores.
    $valid_attempts = $now - ($cantidad_horas_bloqueo * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time 
								  FROM finan_cli.login_attempts 
								  WHERE usuario = ? AND time > '$valid_attempts'")) 
    {
        $stmt->bind_param('s', $user_id);
 
        // Ejecuta la consulta preparada.
        $stmt->execute();
        $stmt->store_result();
 
        // Si ha habido más de x intentos de inicio de sesión fallidos.
        if ($stmt->num_rows >= $intentos_fallidos) 
		{
            return true;
        } 
		else 
		{
            return false;
        }
    }
	else return true;
}

function login($usuario, $password, $mysqli) {
    
	$resultClave = -1;

	// Usar declaraciones preparadas significa que la inyección de SQL no será posible.
	if ($stmt = $mysqli->prepare("SELECT id, clave, salt, id_perfil, estado  FROM finan_cli.usuario WHERE id = ? LIMIT 1")) 
	{
		$stmt->bind_param('s', $usuario);  // Une $usuario al parámetro.
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
 
		// Obtiene las variables del resultado.
		$stmt->bind_result($user_id, $db_password, $salt, $permiso, $estado_user);
		$stmt->fetch();
 
		// Hace el hash de la contraseña con una sal única.
		$password = hash('sha512', $password . $salt);
		if ($stmt->num_rows == 1) 
		{
			// Si el usuario existe, revisa si la cuenta está bloqueada
			// por muchos intentos de conexión.
			if (checkbrute($user_id, $mysqli) == true) 
			{
				// La cuenta está bloqueada.
				// Envía un correo electrónico al usuario que le informa que su cuenta está bloqueada.
				return 3;
			} 
			else 
			{
				if(empty($estado_user) || $estado_user != translate('State_User',$GLOBALS['lang'])) return 13;
				//echo $password." -- ".$db_password;
				// Revisa que la contraseña en la base de datos coincida 
				// con la contraseña que el usuario envió.
				if ($db_password == $password) 
				{
					// ¡La contraseña es correcta!
					// Obtén el agente de usuario.
					sec_session_start();
					$user_browser = $_SERVER['HTTP_USER_AGENT'];
					// Protección XSS ya que podríamos imprimir este valor.
					//$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user_id);
					$username = $user_id;
					$_SESSION['username'] = $username;
					$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					$_SESSION['permisos'] = $permiso;
					$_SESSION['LAST_ACTIVITY'] = time();
					$date_registro = date("YmdHis");
					$date_registro2 = date("Y-m-d H:i:s");

					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.log_usuario(id_usuario,fecha,id_motivo,valor) VALUES (?,?,?,?)"))
					{
						printf("Error: %s\n", $mysqli->error);
					}
					else
					{
						$motivo = 1;
						$parComp2 = translate('Msg_Log_In',$GLOBALS['lang']).$date_registro2;
						$stmt10->bind_param('ssis', $username, $date_registro, $motivo, $parComp2);
						if(!$stmt10->execute())
						{
							printf("Error: %s\n", $mysqli->error);
						}
					}					
					
					// Inicio de sesión exitoso
					return 0;
				} 
				else 
				{
					// La contraseña no es correcta.
					// Se graba este intento en la base de datos.
					$now = time();
					$ip_con = $_SERVER['REMOTE_ADDR'];
					if(!$stmt10 = $mysqli->prepare("INSERT INTO finan_cli.login_attempts(usuario, time, ip_conexion) VALUES (?,?,?)"))
					{
						printf("Error: %s\n", $mysqli->error);
					}
					else
					{
						$stmt10->bind_param('sss', $usuario, $now, $ip_con);
						if(!$stmt10->execute())
						{
							printf("Error: %s\n", $mysqli->error);
						}
					}

					return 2;
				}
			}
		} 
		else 
		{
			// El usuario no existe.
			return 2;
		}
	}	
    return $resultClave;
}

function login_check($mysqli) {
    // Revisa si todas las variables de sesión están configuradas.
    
	if (isset($_SESSION['username'], $_SESSION['login_string'])) 
	{
		$login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Obtiene la cadena de agente de usuario del usuario.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 	
        if ($stmt = $mysqli->prepare("SELECT clave, estado FROM finan_cli.usuario WHERE id = ? LIMIT 1")) 
		{
            // Une $username al parámetro.
            $stmt->bind_param('s', $username);
            $stmt->execute();   // Ejecuta la consulta preparada.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) 
			{
                // Si el usuario existe, obtiene las variables del resultado.
                $stmt->bind_result($password, $estado_u);
                $stmt->fetch();
                
				if($estado_u != translate('State_User',$GLOBALS['lang']))
				{
					if(!empty($_SESSION['username']))	
					{	
						// Desconfigura todos los valores de sesión.
						$_SESSION = array();
					 
						// Obtiene los parámetros de sesión.
						$params = session_get_cookie_params();
					 
						// Borra el cookie actual.
						setcookie(session_name('sec_session_id'),
								'', time() - 42000, 
								$params["path"], 
								$params["domain"], 
								$params["secure"], 
								$params["httponly"]);
					 
						// Destruye sesión. 
						session_destroy();
					}
					return false;
				}
				$login_check = hash('sha512', $password . $user_browser);
				//echo 'PASOO:'.$login_check.' -- '.$login_string;
 
                if ($login_check == $login_string) 
				{
                    // ¡¡Conectado!! 
                    return true;
                } 
				else 
				{
                    // No conectado.
                    return false;
                }
            } 
			else 
			{
                // No conectado.
                return false;
            }
        } 
		else 
		{
            // No conectado.
            return false;
        }
    } 
	else 
	{
        // No conectado.
        return false;
    }
}

function esc_url($url) {
 
    if ('' == $url) 
	{
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) 
	{
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') 
	{
        // Solo nos interesan los enlaces relativos de  $_SERVER['PHP_SELF']
        return '';
    } 
	else 
	{
        return $url;
    }
}
?>