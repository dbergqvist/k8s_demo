# GuestBook example

This example shows how to build a simple, multi-tier web application using Kubernetes and Docker.

The example consists of:
- A web frontend
- A MySQL server
- A Memcached server

The web front end is written in PHP and communicates with the MySQL and
Memcached servers via Kubernetes services.

## Development

The PHP app requires Docker images to be built for the app to run. These will
be created and pushed to Container Registry for use by the demo. So these steps
will not need to be used for the presentation itself but are needed to make
changes to the demo.

Build and tag the docker image. You give the rebuild.sh script a tag
for a version number (e.g. v1). This script will build tag and
push the new Docker image.

    $ cd guestbook
    $ ./rebuild.sh <tag>

If you need to change the app, run rebuild and give it a new tag (e.g. v2).
After that you can change the replication controller to use the new version of
the image and do a rolling update of the app.

    $ kubectl rollingupdate frontend-v1 -f frontend-rc.json --update-period=1s

## Running the Demo

The demo is a simple PHP web app. For the Next demo we will run the app in a
Container Engine cluster.

### Step Zero: Prerequisites

This example requires a kubernetes cluster and we will use Container Engine for the demo.

First make sure your project and zone are set:

    $ gcloud config set project <project-id>
    $ gcloud config set compute/zone <zone>

After that create a Container Engine cluster and set the cluster name (you may need to install alpha components).

    $ gcloud alpha container clusters create <cluster-name> --machine-type n1-standard-2 --num-nodes=3
    ...
    $ gcloud config set container/cluster <cluster-name>
    $ kubectl config use-context gke_<project-id>_<zone>_<cluster-name>

### Step One: Fire up the MySQL Server

MySQL is run by creating a single pod for the MySQL server instance. We don't
have a replication controller so the pod could die because there is nothing to
restart it. Also, we can't resize it because there is no controller managing
the pod.

First create the volume for the mysql server.

    $ gcloud compute disks create --size=500GB mysql-disk

Next create the mysql pods:

    $ kubectl create -f mysql-server/mysql.yaml

Next create the mysql service:

    $ kubectl create -f mysql-server/mysql-service.yaml

### Step Two: Fire up the Memcache Server

Memcached is currently managed in a single pod. I would like to make memcached
into a cluster that can be managed by a replication controller and service but
kube-proxy does not know enough about memcached protocol to handle consistent
hashing of memcached keys. Twemproxy can do this but requires that the server
list be set in a config file and updated when the cluster is modified (pod dies
and is restarted) or resized.

Create the memcached pod.

    $ kubectl create -f memcached-server/memcached.yaml

Create the memcached service.

    $ kubectl create -f memcached-server/memcached-service.yaml

### Step Three: Start the Web Front End

Create the guestbook app replication controller:

    $ kubectl create -f frontend-rc.json

Create the guestbook app service:

    $ kubectl create -f frontend-service.json

The service will create a network load balancer on Compute Engine.
You can view the IP that it's given at Compute -> Network load balancing in the developers console.

TODO: Add instructions on creating a firewall rule.
