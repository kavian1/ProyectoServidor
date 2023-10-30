<?php
function leer_config($nombre)
{
	$read = file_get_contents($nombre);
	if ($read === false) {
		throw new Exception("No se puede cargar la configuración de la BD.");
		return;
	}

	$conf = json_decode($read);
	if ($conf === null) {
		throw new Exception("La configuración de la BD tiene un formato desconocido.");
		return;
	}

	if (
		!property_exists($conf, "ip") || !property_exists($conf, "nombrebd")
		|| !property_exists($conf, "usuario") || !property_exists($conf, "clave")
	) {
		throw new Exception("La configuración de la BD no contiene todos los datos requeridos.");
		return;
	}
	$cad = sprintf("mysql:dbname=%s;host=%s", $conf->nombrebd, $conf->ip);
	$resul = [];
	$resul[] = $cad;
	$resul[] = $conf->usuario;
	$resul[] = $conf->clave;
	return $resul;
}

function comprobar_usuario($nombre, $clave)
{
	$res = leer_config(dirname(__FILE__) . "/configuracion.json", dirname(__FILE__) . "/configuracion.xsd");
	$bd = new PDO($res[0], $res[1], $res[2]);

	$query = "SELECT clave FROM usuarios where nombre = '$nombre'";
	$claveBD = $bd->query($query);

	//clave: 1234
	if ($claveBD->rowCount()) {
		foreach ($claveBD as $row) {

			if (password_verify($clave, $row['clave'])) {
				$ins = "select clave, nombre from usuarios where nombre = '$nombre'";
				$resul = $bd->query($ins);

				if ($resul->rowCount() === 1) {
					return $resul->fetch();
				} else {
					return FALSE;
				}
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}
