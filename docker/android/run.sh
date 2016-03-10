#!/usr/bin/bash

PATH_VOL_SRCHOST=/home/dockervolume
PATH_VOL_CONTAINER=/home/dockervolume

docker run --rm -it -v $PATH_VOL_SRCHOST:$PATH_VOL_CONTAINER -w $PATH_VOL_CONTAINER yoheitakada/android /bin/bash

