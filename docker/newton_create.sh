#!/usr/bin/bash

echo newton_create.sh begin

date

docker-machine create -d google \
    --google-project my-first-gce-project \
    --google-preemptible \
    --google-tags 'http-server,https-server' \
    --google-zone us-central1-f \
    --google-machine-type "n1-standard-1" \
    newton

date

docker-machine stop newton

date

echo y | gcloud compute instances set-machine-type newton --machine-type f1-micro

date

./newton_start.sh

date

# machine type
#  n1-standard-1 f1-micro n1-highcpu-2
#  gcloud compute machine-types list
