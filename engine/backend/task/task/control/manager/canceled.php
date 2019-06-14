<?php
if (is_int($report)) {
    $dateCancel = date('d.m.Y', $report);
} else {
    $dateCancel = $report;
}
