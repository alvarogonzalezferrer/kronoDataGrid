<?php
//-----------------------------------------------------------------------------
// Simple UTF8 glue to connect to MySQL
//-----------------------------------------------------------------------------
// By Kronoman
// En memoria de mi querido padre
// Copyright (c) 2019-2020 Kronoman
// kronoman@gmail.com
//-----------------------------------------------------------------------------
// TODO - this should be converted to PDO, see https://phpbestpractices.org/#mysql
// TODO - better UTF8 support, UTF-8 in PHP sucks. Sorry. see https://phpbestpractices.org/#utf-8
//-----------------------------------------------------------------------------

// parameters: host, user, password, database to connect
function connectMySQL($db_h=NULL, $db_u=NULL, $db_p=NULL, $db_dbn=NULL)
{
	$mysqli = new mysqli($db_h, $db_u, $db_p, $db_dbn);

	if ($mysqli->connect_errno)
	{
		echo '<div class="alert alert-danger" role="alert">
		Database is not properly setup! Check db config and your MySQL server setup!
		</div>';

		die($mysqli->connect_error);
	}

	// setup UTF8 IN SPANISH!!
	$mysqli->query("SET lc_time_names = 'es_AR';") or die ($mysqli->error); // argentina timeS - DEBUG TODO better setup
	// view https://mathiasbynens.be/notes/mysql-utf8mb4
	$mysqli->set_charset("utf8") or die ($mysqli->error);
	$mysqli->query("SET NAMES 'utf8' COLLATE 'utf8_spanish_ci'") or die ($mysqli->error);

	return $mysqli;
}



?>
