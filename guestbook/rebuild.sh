#!/bin/bash

set -e

TAG=$@

if [ "$TAG" == "" ]; then
    echo "Usage: rebuild.sh <tag>"
    exit 1
fi

echo "Getting project..."
PROJECT=`gcloud config list | python config.py - core/project`

cd php-guestbook

docker build -t php-guestbook .
docker tag -f php-guestbook gcr.io/$PROJECT/php-guestbook:$TAG
gcloud preview docker push gcr.io/$PROJECT/php-guestbook:$TAG
