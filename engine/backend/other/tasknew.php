<?php

global $id;
global $cometHash;
global $cometTrackChannelName;
$yandexToken = DBOnce('yandex_token', 'users', 'id=' . $id);

