<?php
global $id;
global $_overdue;
global $_pending;
global $_inprogress;

$worker = DBOnce('COUNT(*)','tasks','worker='.$id);
$manager = DBOnce('COUNT(*)','tasks','manager='.$id);
$all = $worker + $manager;
$inwork = DBOnce('COUNT(*) as count','tasks','(status="new" or status="inwork" or status="returned") and worker='.$id);
$pending = DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="pending"');
$overdue = DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="overdue"');