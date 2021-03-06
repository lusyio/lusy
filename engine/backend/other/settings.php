<?php

global $id;
global $idc;
global $pdo;
global $roleu;

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/settings-functions.php';

$timeZones = [
    "Etc/GMT+12" => "(GMT-12:00) International Date Line West",
    "Pacific/Midway" => "(GMT-11:00) Midway Island, Samoa",
    "Pacific/Honolulu" => "(GMT-10:00) Hawaii",
    "US/Alaska" => "(GMT-09:00) Alaska",
    "America/Los_Angeles" => "(GMT-08:00) Pacific Time (US & Canada)",
    "America/Tijuana" => "(GMT-08:00) Tijuana, Baja California",
    "US/Arizona" => "(GMT-07:00) Arizona",
    "America/Chihuahua" => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
    "US/Mountain" => "(GMT-07:00) Mountain Time (US & Canada)",
    "America/Managua" => "(GMT-06:00) Central America",
    "US/Central" => "(GMT-06:00) Central Time (US & Canada)",
    "America/Mexico_City" => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
    "Canada/Saskatchewan" => "(GMT-06:00) Saskatchewan",
    "America/Bogota" => "(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
    "US/Eastern" => "(GMT-05:00) Eastern Time (US & Canada)",
    "US/East-Indiana" => "(GMT-05:00) Indiana (East)",
    "Canada/Atlantic" => "(GMT-04:00) Atlantic Time (Canada)",
    "America/Caracas" => "(GMT-04:00) Caracas, La Paz",
    "America/Manaus" => "(GMT-04:00) Manaus",
    "America/Santiago" => "(GMT-04:00) Santiago",
    "Canada/Newfoundland" => "(GMT-03:30) Newfoundland",
    "America/Sao_Paulo" => "(GMT-03:00) Brasilia",
    "America/Argentina/Buenos_Aires" => "(GMT-03:00) Buenos Aires, Georgetown",
    "America/Godthab" => "(GMT-03:00) Greenland",
    "America/Montevideo" => "(GMT-03:00) Montevideo",
    "America/Noronha" => "(GMT-02:00) Mid-Atlantic",
    "Atlantic/Cape_Verde" => "(GMT-01:00) Cape Verde Is.",
    "Atlantic/Azores" => "(GMT-01:00) Azores",
    "Africa/Casablanca" => "(GMT+00:00) Casablanca, Monrovia, Reykjavik",
    "Etc/Greenwich" => "(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London",
    "Europe/Amsterdam" => "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
    "Europe/Belgrade" => "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
    "Europe/Brussels" => "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
    "Europe/Sarajevo" => "(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb",
    "Africa/Lagos" => "(GMT+01:00) West Central Africa",
    "Asia/Amman" => "(GMT+02:00) Amman",
    "Europe/Athens" => "(GMT+02:00) Athens, Bucharest, Istanbul",
    "Asia/Beirut" => "(GMT+02:00) Beirut",
    "Africa/Cairo" => "(GMT+02:00) Cairo",
    "Africa/Harare" => "(GMT+02:00) Harare, Pretoria",
    "Europe/Helsinki" => "(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
    "Asia/Jerusalem" => "(GMT+02:00) Jerusalem",
    "Africa/Windhoek" => "(GMT+02:00) Windhoek",
    "Asia/Kuwait" => "(GMT+03:00) Kuwait, Riyadh, Baghdad",
    "Europe/Minsk" => "(GMT+03:00) Minsk",
    "Europe/Moscow" => "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
    "Africa/Nairobi" => "(GMT+03:00) Nairobi",
    "Asia/Tbilisi" => "(GMT+03:00) Tbilisi",
    "Asia/Tehran" => "(GMT+03:30) Tehran",
    "Asia/Muscat" => "(GMT+04:00) Abu Dhabi, Muscat",
    "Asia/Baku" => "(GMT+04:00) Baku",
    "Asia/Yerevan" => "(GMT+04:00) Yerevan",
    "Asia/Kabul" => "(GMT+04:30) Kabul",
    "Asia/Yekaterinburg" => "(GMT+05:00) Yekaterinburg",
    "Asia/Karachi" => "(GMT+05:00) Islamabad, Karachi, Tashkent",
    "Asia/Calcutta" => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
    //"Asia/Calcutta" => "(GMT+05:30) Sri Jayawardenapura",
    "Asia/Katmandu" => "(GMT+05:45) Kathmandu",
    "Asia/Almaty" => "(GMT+06:00) Almaty, Novosibirsk",
    "Asia/Dhaka" => "(GMT+06:00) Astana, Dhaka",
    "Asia/Rangoon" => "(GMT+06:30) Yangon (Rangoon)",
    "Asia/Bangkok" => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
    "Asia/Krasnoyarsk" => "(GMT+07:00) Krasnoyarsk",
    "Asia/Hong_Kong" => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
    "Asia/Kuala_Lumpur" => "(GMT+08:00) Kuala Lumpur, Singapore",
    "Asia/Irkutsk" => "(GMT+08:00) Irkutsk, Ulaan Bataar",
    "Australia/Perth" => "(GMT+08:00) Perth",
    "Asia/Taipei" => "(GMT+08:00) Taipei",
    "Asia/Tokyo" => "(GMT+09:00) Osaka, Sapporo, Tokyo",
    "Asia/Seoul" => "(GMT+09:00) Seoul",
    "Asia/Yakutsk" => "(GMT+09:00) Yakutsk",
    "Australia/Adelaide" => "(GMT+09:30) Adelaide",
    "Australia/Darwin" => "(GMT+09:30) Darwin",
    "Australia/Brisbane" => "(GMT+10:00) Brisbane",
    "Australia/Canberra" => "(GMT+10:00) Canberra, Melbourne, Sydney",
    "Australia/Hobart" => "(GMT+10:00) Hobart",
    "Pacific/Guam" => "(GMT+10:00) Guam, Port Moresby",
    "Asia/Vladivostok" => "(GMT+10:00) Vladivostok",
    "Asia/Magadan" => "(GMT+11:00) Magadan, Solomon Is., New Caledonia",
    "Pacific/Auckland" => "(GMT+12:00) Auckland, Wellington",
    "Pacific/Fiji" => "(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
    "Pacific/Tongatapu" => "(GMT+13:00) Nuku'alofa",
];

$userData = getUserData($id);
$companyData = getCompanyData();
$notifications = getNotificationSettings();
