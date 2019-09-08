require 'webrick'
require 'mimemagic'
require 'fileutils'
require 'securerandom'

server = WEBrick::HTTPServer.new :Port => 9292

server.mount_proc '/' do |req, res|
  path = req.path[1..-1]

  if req.path.end_with? '/'
    if path.include? '.'
      res.status = 400
      res.body = 'You stupid'
    else
      files = Dir.glob(".#{req.path}*")
      items = files.map do |file|
        "<li><a href='/#{file}'>#{file}</a></li>"
      end
      res.body = "<ul>#{items.join("\n")}</ul>"
      res['Content-Type'] = 'text/html'
    end
  else
    if File.file? path
      file = File.open(path, 'rb')
      res.body = file.read
    else
      res['Content-Type'] = MimeMagic.by_path(req.path)
      res.status = 404
      res.body = 'Not found'
    end
  end
end

trap 'INT' do server.shutdown end

server.start

FileUtils.mkdir_p('/tmp/flags')
File.write("/tmp/flags/#{SecureRandom.hex(10)}.txt", ENV['FLAG'])