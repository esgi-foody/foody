FROM debian:jessie-slim

RUN apt-get update && apt-get install -y nginx

COPY sites/foody.test /etc/nginx/sites-enabled/

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
