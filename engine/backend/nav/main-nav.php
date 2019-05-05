<?php 

// глобальные переменные

global $id;
global $_main;
global $_tasks;
global $_tasknew;
global $_awards;
global $_company;
global $_storage;

// количество задач

$worker = DBOnce('COUNT(*)','tasks','worker='.$id);
$manager = DBOnce('COUNT(*)','tasks','manager='.$id);