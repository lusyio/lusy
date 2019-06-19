<?php
if (ctype_digit($report)) {
    $datedone = date('d.m.Y', $report);
} else {
    $datedone = $report;
}
