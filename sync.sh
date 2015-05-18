#!/bin/sh

if [ "$@" == "" ]; then
    echo "Usage: sync.sh <instance-name>"
    exit 1
fi

echo "Getting project..."
PROJECT=`gcloud config list | python config.py - core/project`
echo "Getting zone..."
ZONE=`gcloud config list | python config.py - compute/zone`

echo "Getting ssh config..."
CONFIGFILE=`mktemp -t next-k8s-demo`
gcloud compute config-ssh --ssh-config-file=$CONFIGFILE

HOST=$@.$ZONE.$PROJECT
rsync -e "ssh -F $CONFIGFILE" --progress -r guestbook/ $HOST:guestbook/
