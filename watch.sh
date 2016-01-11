watch -n 1 --no-title " kubectl get pods -o=templatefile --template=pod.tmpl --selector='app=nextdemo' ;\
    kubectl get rc -o=templatefile --template=rc.tmpl --selector='app=nextdemo';\
    kubectl get services -o=templatefile --template=service.tmpl --selector='app=nextdemo'"
