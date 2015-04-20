<?php
require_once 'functions.php';

class AndroidCLient
{

	function Register($json)
	{
		$function = new Functions();
		$function->Connect();
		$json = stripslashes($json);
		$obj = json_decode($json);
		
		$user = mysql_real_escape_string($obj->{'user'});
		$pass = mysql_real_escape_string($obj->{'pass'});
		
		if ($user != null && $pass != null)
		{
			$pass = sha1($pass);
			
			$query = "INSERT INTO Usuarios (user, pass)
						VALUES ('$user', '$pass')";
			$resp = $function->Query($query);
			
			
			$query = "SELECT * FROM Usuarios
					WHERE user='$user' and pass='$pass'";
			
			$resp = $function->Query($query);
			$count = mysql_num_rows($resp);
			
			if ($count == 1)
			{
				$row = mysql_fetch_array($resp, MYSQL_ASSOC);
				
				$Usuario = array(	"id" => $row['id'],
									"user" => $row['user'],
									"pass" => $row['pass'] );
			}
		}
		echo json_encode($Usuario);
	}
	
	function AddActividad($userID, $json)
	{
		$function = new Functions();
		$function->Connect();
		$userID = stripslashes($userID);
		$json = stripslashes($json);
		$obj = json_decode($json);
		
		$day = $obj->{'dia'};
		$month = $obj->{'mes'};
		$year = $obj->{'ano'};
		$place = $obj->{'lugar'};
		$name = $obj->{'nombre'};
		$alarm = $obj->{'alarma'};
		
		$bloque = $obj->{'bloque'};
		$startHour = $bloque->{'starthour'};
		$startMinute = $bloque->{'startminute'};
		$endHour = $bloque->{'endhour'};
		$endMinute = $bloque->{'endminute'};
		
		if ($userID != null)
		{
			$query = "INSERT INTO Actividades (day, month, year, place, startHour, startMinute, endHour, endMinute, name, alarm)
						VALUES ('$day', '$month', '$year', '$place', '$startHour', '$startMinute', '$endHour', '$endMinute', '$name', '$alarm')";
			$resp = $function->Query($query);
			
			$query = "SELECT * FROM Actividades
					WHERE name='$name'
					ORDER BY id DESC
					LIMIT 1";
			
			$resp = $function->Query($query);
			$count = mysql_num_rows($resp);
			
			if ($count == 1)
			{
				$row = mysql_fetch_array($resp, MYSQL_ASSOC);
				$activityID = $row['id'];
			}
			
			$query = "INSERT INTO ActividadesPorUsuario (id_usuario, id_actividad)
						VALUES ('$userID', '$activityID')";
			$resp = $function->Query($query);
		}
	}
	
	function LoadActividad($userID)
	{
		$function = new Functions();
		$function->Connect();
		$userID = stripslashes($userID);
		
		if ($userID != null)
		{
			$query = "SELECT Actividades.id, Actividades.day, Actividades.month, Actividades.year, Actividades.place, Actividades.startHour, Actividades.startMinute, Actividades.endHour, Actividades.endMinute, Actividades.name, Actividades.alarm FROM Actividades
						INNER JOIN ActividadesPorUsuario ON (ActividadesPorUsuario.id_actividad = Actividades.id)
						WHERE ActividadesPorUsuario.id_usuario = '$userID'";
			$resp = $function->Query($query);
			
			$count = mysql_num_rows($resp);
			
			$contador = 0;
			
			if ($count != 0)
			{
				while ($row = mysql_fetch_array($resp, MYSQL_ASSOC))
				{
					$starthour = $row['startHour'] + 0;
					$startminute = $row['startMinute'] + 0;
					$endhour = $row['endHour'] + 0;
					$endminute = $row['endMinute'] + 0;
					
					$Bloque = array(	"id" => 0,
										"starthour" => $starthour,
										"startminute" => $startminute,
										"endhour" => $endhour,
										"endminute" => $endminute);
					
					$id = $row['id'] + 0;
					$dia = $row['day'] + 0;
					$mes = $row['month'] + 0;
					$ano = $row['year'] + 0;
					$alarm = $row['alarm'] + 0;
					
					$Actividades[$contador] = array(	"id" => $id,
														"idRamo" => 0,
														"tipo" => 0,
														"dia" => $dia,
														"mes" => $mes,
														"ano" => $ano,
														"lugar" => $row['place'],
														"bloque" => $Bloque,
														"nombre" => $row['name'],
														"alarma" => $alarm,
														"porcentaje" => 0,
														"nota" => 0 );
					$contador++;
				}
			}
		}
		$ActividadArray = array(	"count" => $contador,
						"actividades" => $Actividades );
		echo json_encode($ActividadArray);
	}
	
	function UpdateActividad($userID, $activityID, $json)
	{
		$function = new Functions();
		$function->Connect();
		$userID = stripslashes($userID);
		$activityID = stripslashes($activityID);
		$json = stripslashes($json);
		$obj = json_decode($json);
		
		$day = $obj->{'dia'};
		$month = $obj->{'mes'};
		$year = $obj->{'ano'};
		$place = $obj->{'lugar'};
		$name = $obj->{'nombre'};
		$alarm = $obj->{'alarma'};
		
		$bloque = $obj->{'bloque'};
		$startHour = $bloque->{'starthour'};
		$startMinute = $bloque->{'startminute'};
		$endHour = $bloque->{'endhour'};
		$endMinute = $bloque->{'endminute'};
		
		if ($userID != null)
		{
			$query = "UPDATE Actividades SET 
						day = '$day',
						month = '$month',
						year = '$year',
						place = '$place',
						startHour = '$startHour',
						startMinute = '$startMinute',
						endHour = '$endHour',
						endMinute = '$endMinute',
						name = '$name',
						alarm = '$alarm'
						WHERE id = '$activityID'";
			
			$resp = $function->Query($query);
		}
	}
	
	function DeleteActividad($userID, $activityID)
	{
		$function = new Functions();
		$function->Connect();
		$userID = stripslashes($userID);
		$activityID = stripslashes($activityID);
		
		if ($userID != null)
		{
			$query = "DELETE FROM ActividadesPorUsuario
						WHERE id_actividad = '$activityID'";
			
			$resp = $function->Query($query);
			
			$query = "DELETE FROM Actividades
						WHERE id = '$activityID'";
			
			$resp = $function->Query($query);
		}
	}
	
	function ShareActividad($userID, $activityID, $share)
	{
		$function = new Functions();
		$function->Connect();
		$userID = stripslashes($userID);
		$activityID = stripslashes($activityID);
		$share = stripslashes($share);
		
		if ($userID != null)
		{
			$query = "SELECT * FROM Usuarios
					WHERE user='$share'
					LIMIT 1";
			
			$resp = $function->Query($query);
			$count = mysql_num_rows($resp);
			
			if ($count == 1)
			{
				$row = mysql_fetch_array($resp, MYSQL_ASSOC);
				$shareID = $row['id'];
				
				$query = "INSERT INTO ActividadesPorUsuario (id_usuario, id_actividad)
						VALUES ('$shareID', '$activityID')";
				$resp = $function->Query($query);
			}
		}
	}
	
}
?>