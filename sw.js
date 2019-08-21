'use strict';
importScripts('/sw-toolbox.js');
toolbox.precache(['/dashboard/', 'assets/css/custom.css']);
toolbox.router.get('/upload/avatar/*', toolbox.cacheFirst);
toolbox.router.get('/*', toolbox.networkFirst, {networkTimeoutSeconds: 5});