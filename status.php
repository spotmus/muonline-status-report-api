<?php

// Set Content Header Type
header('Content-type: application/json');

//==================================
//       CONFIGURATION
//==================================

# Show or hide errors
const DEBUG_MODE = true; # true = Show erros | false = Hide errors 

# Time Zone
const TIME_ZONE = 'America/Sao_Paulo'; 

# Your Server Tag on Spot Mu's
const SERVER_TAG = '@neweramu';

# Data Base Configuration
const DB_HOST = 'IP';
const DB_PORT = '1433';
const DB_NAME = 'MuOnline';
const DB_USER = 'sa';
const DB_PASS = 'pass';

# Connect Server Configuration
const CS_HOST = 'IP';
const CS_PORT = '44405';

//==================================

// Hide or Display Errors
if(!DEBUG_MODE) error_reporting(0);

// Set Default Timezone
date_default_timezone_set(TIME_ZONE);

// Create Report Object
$report = new Stdclass();

// PDO Connection Start
$pdo = new PDO('sqlsrv:server=' . DB_HOST . ',' . DB_PORT . ';Database=' . DB_NAME , DB_USER, DB_PASS);

// Server Name
$report->server = SERVER_TAG;

// Number of Characters
$report->characters = $pdo->query('SELECT COUNT(*) FROM ' . DB_NAME . '.dbo.Character')->fetchColumn();

// Number of Accounts
$report->accounts = $pdo->query('SELECT COUNT(*) FROM ' . DB_NAME . '.dbo.MEMB_INFO')->fetchColumn();

// Number of Guilds
$report->guilds = $pdo->query('SELECT COUNT(*) FROM ' . DB_NAME . '.dbo.Guild')->fetchColumn();

// Number of Players Online
$report->players = $pdo->query('SELECT COUNT(*) FROM ' . DB_NAME . '.dbo.MEMB_STAT WHERE ConnectStat = 1')->fetchColumn();

// Check Connect Server Status (Online | Offline)
$report->status = (@fsockopen('tcp://'. CS_HOST, CS_PORT, $errno, $errstr, 0.5))? true : false;

// Current Time
$report->updated_at = time();
    
// Print Report Data
echo json_encode($report);

