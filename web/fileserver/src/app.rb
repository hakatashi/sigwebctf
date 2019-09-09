require 'webrick'
require 'mimemagic'
require 'fileutils'
require 'securerandom'

FileUtils.mkdir_p('/tmp/flags')
FileUtils.mv('flag.txt', "/tmp/flags/#{SecureRandom.hex(10)}.txt")

server = WEBrick::HTTPServer.new :Port => 9292

server.mount_proc '/' do |req, res|
  path = req.path.tr("*?[]{}", '#').gsub(/^\/+/, '')

  if req.path.end_with? '/'
    if path.include? '.'
      res.status = 400
      res.body = 'You stupid'
      next
    end

    files = Dir.glob(".#{req.path}*")
    items = files.map do |file|
      "<li><a href='/#{file}'>#{file}</a></li>"
    end

    res.body = "<ul>#{items.join("\n")}</ul>"
    res['Content-Type'] = 'text/html'

    next
  end

  matches = Dir.glob(path)

  if matches.empty?
    res.status = 404
    res.body = 'Not found'

    next
  end

  res['Content-Type'] = MimeMagic.by_path(req.path)
  file = File.open(matches.first, 'rb')
  res.body = file.read
end

trap 'INT' do server.shutdown end

server.start