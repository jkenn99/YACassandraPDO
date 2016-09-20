#!/bin/bash

sudo rm -rf /var/lib/cassandra/*
wget http://www.us.apache.org/dist/cassandra/2.1.15/apache-cassandra-2.1.15-bin.tar.gz && tar -xvzf apache-cassandra-2.1.15-bin.tar.gz && sudo sh apache-cassandra-2.1.15/bin/cassandra
wget https://github.com/apache/thrift/archive/0.9.1.tar.gz
tar xf 0.9.1.tar.gz
cd thrift-0.9.1 && ./bootstrap.sh && ./configure --without-php --without-java --without-ruby --without-erlang --without-nodejs --without-lua --without-go --without-python --with-cpp --without-c_glib && make && sudo make install
