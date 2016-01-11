# K8s_demo
Simple Kubernetes demo

## Configure your Google Cloud Platform project
Note: These instructions assume you have a Google Cloud Platform project with billing enabled and [Google Cloud SDK](https://cloud.google.com/sdk/) installed
* Point your browser at your [Google Developers Console](https://console.developers.google.com/).
* Navigate to: Projects->{your-project-name}->APIs & auth->APIs.
* Ensure the following APIs are enabled:
  * Google Cloud Storage
  * Google Cloud Container Engine API
  * Google Compute Engine

## Run the demo
* Update gcloud
* Create a cluster
* Create a 500MB blank PD called 'demo-mysql-disk' in the same zone as the cluster
* Pull down the credentials locally with gcloud container clusters get-credentials <cluster>
* git clone https://github.com/dbergqvist/k8s_demo.git
* cd k8s_demo/mysql-guestbook
* run: kubectl proxy --www=gcp-live-k8s-visualizer-v1/
* Connect to http://localhost:8001/static (from a new shell, the other is blocked by the proxy call)
* Deploy everything while monitoring the viz 
  * kubectl create -f mysql-server
  * kubectl create -f memcached
  * kubectl create -f frontend-service.yaml // takes while for Ingress IP to appear)
  * kubectl create -f frontend-rc-v1.yaml
* Connect to the app 
* Scale the fronted rc
  * kubectl scale rc frontend-rc-v1 --replicas=6
  * kubectl scale rc frontend-rc-v1 --replicas=3
* Do a rolling up date
  * kubectl rolling update frontend-rc-v1 -f frontend-rc-v2.yaml --update-period=2s
* Refresh the app to see the update

## Clean up
* kubectl delete -f mysql-server
* kubectl delete -f memcached-server/
* kubectl delete -f frontend-service.yaml
* kubectl delete -f frontend-rc-v2.yaml
* kubectl delete -f frontend-rc-v1.yaml
