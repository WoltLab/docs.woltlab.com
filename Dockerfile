FROM ruby

RUN	apt-get update \
&&	apt-get install -y --no-install-recommends \
	nodejs \
	python-pygments \
&&	rm -rf /var/lib/apt/lists/*

WORKDIR /tmp

COPY Gemfile* /tmp/

RUN bundle install

VOLUME /src
EXPOSE 4000

WORKDIR /src
ENTRYPOINT ["jekyll"]

