#! /bin/bash

NGINX_LOG = /data/nginx/log/texshow.cc/web/
YESTERDAY = `date -d yesterday +%Y-%m-%d`

mv $NGINX_LOG"access.log" $NGINX_LOG"access.log."$YESTERDAY
mv $NGINX_LOG"error.log" $NGINX_LOG"error.log."$YESTERDAY
