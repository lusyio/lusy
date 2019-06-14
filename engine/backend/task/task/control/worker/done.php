<?php
if (is_int($report)) {
    $datedone = date('d.m.Y', $report);
} else {
    $datedone = $report;
}
