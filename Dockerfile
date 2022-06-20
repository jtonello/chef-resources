FROM php:7.2-apache

ENV TZ=America/New_York
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt install -y -q libyaml-dev build-essential git && \
    pecl install yaml-2.0.0 && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/yaml.ini

WORKDIR /var/www/html

RUN mkdir css 

COPY ./css/style.css ./css
COPY *.php ./

RUN git clone https://github.com/chef/chef-web-docs && \
    mv chef-web-docs/data/infra/resources ./chef-resources && \
    rm -r chef-web-docs

RUN git clone https://github.com/inspec/inspec.git && \
    mv inspec/docs-chef-io/content/inspec/resources ./inspec-resources && \
    rm -r inspec

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer require rmccue/requests
