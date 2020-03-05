FROM node:13.8-alpine3.10 AS node

WORKDIR /work-logger

COPY package.json yarn.lock /work-logger/
RUN yarn install --production --pure-lock-file
COPY . /work-logger/
RUN yarn production


FROM nginx:1.17.9-alpine

RUN apk --no-cache add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata

COPY --from=node /work-logger/public/js /work-logger/public/js
COPY --from=node /work-logger/public/css /work-logger/public/css
COPY --from=node /work-logger/public/images /work-logger/public/images
COPY --from=node /work-logger/public/mix-manifest.json /work-logger/public/

COPY ./docker/web/etc /etc
