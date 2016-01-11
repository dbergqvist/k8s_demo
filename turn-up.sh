kubectl create -f mysql-server/mysql.yaml
kubectl create -f mysql-server/mysql-service.yaml
kubectl create -f memcached-server/memcached.yaml
kubectl create -f memcached-server/memcached-service.yaml
kubectl create -f frontend-rc-v1.yaml
kubectl create -f frontend-service.yaml
