FROM rabbitmq:3.6-management

ENV container=docker

# install our erlang.cookie
RUN chmod 777 -R /var/lib/rabbitmq
RUN chown -R rabbitmq:rabbitmq /var/lib/rabbitmq

EXPOSE 15672 5672