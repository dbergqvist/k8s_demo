kubectl delete service mysql
kubectl delete service memcached
kubectl delete service frontend
kubectl stop rc frontend-rc-v1
kubectl stop rc frontend-rc-v2
kubectl stop rc frontend-rc-big
kubectl delete pod mysql
kubectl delete pod memcached
