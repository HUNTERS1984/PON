FROM nginx

ENV container=docker

# Install nginx and link access and error logs to stdout
RUN mkdir -p /var/lib/nginx && chown -R nginx:www-data /var/lib/nginx && \
    ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

COPY nginx.conf /etc/nginx/nginx.conf
COPY ./conf/* /etc/nginx/conf.d/

# Expose HTTP/HTTPS ports
EXPOSE 80 443

ENTRYPOINT ["/usr/sbin/nginx"]
