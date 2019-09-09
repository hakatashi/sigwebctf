FROM ruby:2.6.4-alpine

COPY . /app
RUN cd /app && \
    gem install bundler && \
    bundle install

CMD cd /app && bundle exec ruby app.rb
