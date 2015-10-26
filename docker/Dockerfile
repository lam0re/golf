FROM ubuntu
RUN useradd -u 1000 -U pg
RUN apt-get update; apt-get -y install libbsd-resource-perl libproc-background-perl php5-cli
ADD perlgolf /usr/bin/
ADD phpgolf /usr/bin/
