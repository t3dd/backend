var express = require('express');
var app = express();

app.use(function staticsPlaceholder(req, res, next) {
  return next();
});
app.get('*', function(req, res) {
  res.sendFile(__dirname + '/app/index.html');
});
module.exports = app;