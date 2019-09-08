FROM ruby:2.6.3-alpine

COPY . /app
RUN apk add --no-cache build-base sqlite-dev sqlite-libs && \
        cd /app && \
        gem install bundler && \
        bundle install && \
        apk del --purge build-base sqlite-dev

CMD cd /app && bundle exec ruby app.rb
