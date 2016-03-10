# Dockerfile for android build environment

FROM gfx2015/android:latest
MAINTAINER Yohei Takada <TakadaYouhei@users.noreply.github.com>

ENV DEBIAN_FRONTEND noninteractive

# install vim
RUN apt-get -y install vim vim-common 

# install ant
ENV ANT_DOWNLOAD_URL=http://archive.apache.org/dist/ant/binaries/apache-ant-1.9.6-bin.tar.gz
ENV ANT_HOME=/usr/local/ant
ENV PATH=$PATH:${ANT_HOME}/bin
RUN curl -L "${ANT_DOWNLOAD_URL}" | tar --no-same-owner -xz -C /usr/local
RUN mv /usr/local/apache-ant-1.9.6 ${ANT_HOME}

# eof

