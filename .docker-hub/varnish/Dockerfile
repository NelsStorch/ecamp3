ARG VERSION=7.7.0

FROM varnish:${VERSION}

USER root

RUN set -e; \
    apt-get update; \
    apt-get -y install prometheus-varnish-exporter;

RUN rm -rf /var/lib/apt/lists/*;

USER varnish