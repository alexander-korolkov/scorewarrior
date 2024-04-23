FROM node:20.11 as build-stage

WORKDIR /app

COPY package*.json ./
RUN npm install
RUN npm install -g @quasar/cli

COPY . .

RUN quasar build

RUN mkdir dist/spa/src && mkdir dist/spa/src/assets
COPY src/assets /dist/spa/src/assets

FROM nginx:stable-alpine as production-stage

COPY --from=build-stage /app/dist/spa /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
