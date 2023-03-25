FROM debian:bullseye as common

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update -q && \
    apt-get install -qqy --no-install-recommends apt-utils wget apt-transport-https lsb-release ca-certificates

# install PHP Repository
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

# install packages
RUN apt-get update -q && apt-get install -qqy --no-install-recommends --fix-missing \
    vim \
    less \
    unzip \
    php8.1-pcov \
    php8.1-cli \
    php8.1-fpm \
    php8.1-intl \
    php8.1-mysql \
    php8.1-mbstring \
    php8.1-curl \
    php8.1-xml \
    php8.1-zip \
    libxml2 \
    nginx \
    ssl-cert \
    git \
    patch \
    jq \
    htop \
    curl \
    net-tools \
    sudo \
    msmtp \
    procps \
    # Explicitly install libpcre2-8-0 to workaournd packaging bug
    # https://github.com/oerdnj/deb.sury.org/issues/1682
    libpcre2-8-0 \
    gettext \
    p7zip \
    openssh-client \
    locales \
    cron \
    && apt-get clean && rm -r /var/lib/apt/lists/*

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

RUN sed -i -e 's/# \(de_DE\.UTF-8 .*\)/\1/' /etc/locale.gen && locale-gen

# copy the Composer PHAR from the Composer image into the PHP image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN /usr/bin/composer config --global process-timeout 0

RUN useradd -m --shell /bin/bash baselinemonitoringdev && \
    echo "baselinemonitoringdev ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

RUN chown -R baselinemonitoringdev:users /home/baselinemonitoringdev
# configure php-fpm
RUN rm -f /etc/php/8.1/fpm/pool.d/www.conf && \
    mkdir -p /run/php && \
    mkdir -p /var/log/php8.1-fpm/baselinemonitoringdev && \
    ln -s /home/baselinemonitoringdev/current/config/php-fpm/pool-dev.conf /etc/php/8.1/fpm/pool.d/pool-dev.conf && \
    ln -s /home/baselinemonitoringdev/current/config/nginx/baselinemonitoringdev.conf /etc/nginx/sites-enabled/baselinemonitoringdev.conf && \
    rm -f /etc/nginx/sites-enabled/default && \
    ln -s /home/baselinemonitoringdev/current/config/docker/php/custom.ini /etc/php/8.1/cli/conf.d/custom.ini && \
    ln -s /home/baselinemonitoringdev/current/config/docker/php/custom.ini /etc/php/8.1/fpm/conf.d/custom.ini && \
	ln -s /home/baselinemonitoringdev/current/config/crontab/crontab /etc/cron.d/baseline_monitoring_crontab

RUN usermod -G baselinemonitoringdev www-data
RUN usermod -u 3502 baselinemonitoringdev

RUN echo "PS1='\[\033[1;33m\]\w\[\033[1;31m\] \u@\h > \[\033[0m\]'" >> /root/.bashrc && \
    echo "alias baselinemonitoringdev='cd /home/baselinemonitoringdev/current;su baselinemonitoringdev'" >> /root/.bashrc

#COPY ./../crontab/crontab /etc/cron.d/application_cron_file
#RUN chmod 0644 /etc/cron.d/application_cron_file
#RUN crontab /etc/cron.d/application_cron_file
#RUN touch /var/log/cron.log
#CMD ["cron", "-f"]

CMD sudo nginx && \
  sudo php-fpm8.1 && \
  sudo service cron start && \
  tail -f /dev/null

USER baselinemonitoringdev:baselinemonitoringdev
WORKDIR /home/baselinemonitoringdev/current
FROM common as dev

RUN echo "PS1='\[\033[1;33m\]\w\[\033[1;31m\] \u@\h > \[\033[0m\]'" >> /home/baselinemonitoringdev/.bashrc && \
    echo "crontab -u baselinemonitoringdev /etc/cron.d/baseline_monitoring_crontab" >> /home/baselinemonitoringdev/.bashrc

USER root

# install xdebug
RUN apt-get update -q && apt-get install -qqy --fix-missing --no-install-recommends \
    && apt-get clean && rm -r /var/lib/apt/lists/*

USER baselinemonitoringdev:baselinemonitoringdev

