#!/bin/bash
cd /www/wwwroot/tao.lailu.live
/usr/bin/php console.php tiktok:getVideo
chmod -R 777 ./Application/Runtime