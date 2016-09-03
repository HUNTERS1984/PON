FROM mariadb

ENV container=docker


ADD my.cnf /etc/mysql/my.cnf

RUN mkdir /data
VOLUME ["/data/mariadb"]

EXPOSE 3306

RUN chmod -R 777 /data/mariadb

ADD start.sh /opt/bin/start.sh
ADD mariadb-setup.sql /opt/bin/mariadb-setup.sql
RUN chmod u=rwx /opt/bin/start.sh
RUN chown mysql:mysql /opt/bin/start.sh /opt/bin/mariadb-setup.sql /data/mariadb

# run all subsequent commands as the mysql user
USER mysql

ENTRYPOINT ["/usr/bin/mysqld_safe"]
ENTRYPOINT ["/opt/bin/start.sh"]
CMD ["/bin/bash"]

#CMD ["/bin/bash", "/start.sh"]

#ENTRYPOINT ["/start.sh"]