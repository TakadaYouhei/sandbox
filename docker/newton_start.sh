#!/usr/bin/bash

docker-machine start newton
echo y | docker-machine regenerate-certs newton
echo eval \$\(docker-machine env newton\)

