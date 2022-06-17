FROM php:7.2-apache

ENV TZ=America/New_York
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt install -y -q libyaml-dev build-essential git

RUN pecl install yaml-2.0.0
RUN echo "extension=yaml.so" > /usr/local/etc/php/conf.d/yaml.ini

WORKDIR /var/www/html

RUN mkdir css 

COPY ./css/style.css ./css
COPY *.php ./

RUN git clone https://github.com/chef/chef-web-docs
RUN mv chef-web-docs/data/infra/resources ./chef-resources
RUN rm -r chef-web-docs

RUN git clone https://github.com/inspec/inspec.git
RUN mv inspec/docs-chef-io/content/inspec/resources ./inspec-resources
RUN rm -r inspec

RUN composer require rmccue/requests
