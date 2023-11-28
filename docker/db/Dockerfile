FROM postgres:16

# Custom initialization scripts
COPY ./create_user.sh   /docker-entrypoint-initdb.d/10-create_user.sh
