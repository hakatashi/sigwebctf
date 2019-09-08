const express = require('express');
const puppeteer = require('puppeteer');
const URL = require('url').URL;
const cookies = require('./cookies');

const app = express();
app.set('views', 'views');
app.set('view engine', 'pug');

app.get('/', async (req, res, next) => {
  if (typeof req.query.url !== 'undefined' && req.query.url.match(/^https?:\/\//)) {
    try {
      const browser = await puppeteer.launch({headless: true, args: ['--no-sandbox', '--disable-xss-auditor']});
      const page = await browser.newPage();
      page.setDefaultTimeout(10000);

      const origin = new URL(req.query.url).origin;
      if (cookies[origin]) {
        await page.setCookie(cookies[origin]);
      }

      const response = await page.goto(req.query.url);
      await page.waitFor(3000);
      await browser.close();
      res.render('index', { response: JSON.stringify(response.headers(), null, 2), url: req.query.url});
    } catch (e) {
      next(e)
    }
  } else {
    res.render('index', {url: ''});
  }
});

app.listen(8000, '0.0.0.0');