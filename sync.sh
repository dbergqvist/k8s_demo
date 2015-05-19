#!/bin/bash

INSTANCE=$@

if [ "$INSTANCE" == "" ]; then
    echo "Usage: sync.sh <instance-name>"
    exit 1
fi

echo "Getting project..."
PROJECT=`gcloud config list | python guestbook/config.py - core/project`
echo "Getting zone..."
ZONE=`gcloud config list | python guestbook/config.py - compute/zone`

echo "Getting ssh config..."
CONFIGFILE=`mktemp -t next-k8s-demo`
gcloud compute config-ssh --ssh-config-file=$CONFIGFILE

HOST=$@.$ZONE.$PROJECT
rsync -a --delete-after -e "ssh -F $CONFIGFILE" --progress -r guestbook/ $HOST:guestbook/
