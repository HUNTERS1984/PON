FROM nginx

ENV container=docker

COPY nginx.conf /etc/nginx/conf.d/pon.conf

ADD start.sh /start.sh
RUN chmod a+x /start.sh

RUN echo "daemon off;" >> /etc/nginx/nginx.conf

EXPOSE 80 443

WORKDIR /var/www/pon

ENTRYPOINT ["/start.sh"]
CMD ["/bin/bash"]
