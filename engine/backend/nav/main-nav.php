<?php 

// глобальные переменные

global $id;
global $_main;
global $_tasks;
global $_tasknew;
global $_awards;
global $_company;
global $_storage;
global $idc;
global $pdo;


// количество задач
$worker = DBOnce('COUNT(*)','tasks','worker='.$id);
$manager = DBOnce('COUNT(*)','tasks','manager='.$id);

// файловый прогресс бар
$uploadlimit = 100;
$totalSize = number_format((DBOnce('SUM(file_size) AS totalSize','uploads','company_id='.$idc . ' AND is_deleted = 0')) / (1024 * 1024),0,'',' ');
$pros = ($totalSize/$uploadlimit)*100;
if($pros > 90) { $bgpross = 'bg-danger'; } else { $bgpross = '';}