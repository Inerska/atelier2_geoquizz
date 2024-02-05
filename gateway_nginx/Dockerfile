FROM nginx:latest

COPY ./reverse.conf /etc/nginx/conf.d/default.conf

CMD ["nginx", "-g", "daemon off;"]