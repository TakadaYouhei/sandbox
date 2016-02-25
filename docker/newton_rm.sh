#!/usr/bin/bash

eval $(docker-machine env -u)

docker-machine rm newton

