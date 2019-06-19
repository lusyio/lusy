<?php
if (ctype_digit($report)) {
    $dateCancel = date('d.m.Y', $report);
} else {
    $dateCancel = $report;
}
