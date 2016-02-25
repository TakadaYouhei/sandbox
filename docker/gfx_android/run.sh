#!/usr/bin/bash

sudo eval $(docker-machine env newton)

sudo docker run -it --rm -v /home/takadayouhei/proj/sandbox/docker/gfx_android:/mnt/workspace -w /mnt/workspace gfx2015/android

