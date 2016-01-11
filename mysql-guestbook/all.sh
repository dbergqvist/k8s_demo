kubectl config use-context gke_rabbit-skateboard-345_europe-west1-c_gcpnext-ams
kubectl run frontend --image=memcached --replicas=1 --labels="app=nextdemo,name=memcached"
kubectl create -f frontend-rc-v1.yaml
kubectl scale rc frontend-rc-v1 --replicas=6
kubectl scale rc frontend-rc-v1 --replicas=3
kubectl rolling-update frontend-rc-v1 --update-period=2s  -f frontend-rc-v2.yaml
