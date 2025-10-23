"use strict";

require("dotenv").config();

const fs = require("fs");
const express = require("express");
const http = require("http");
const bodyParser = require("body-parser");
const { Server } = require("socket.io");

const wa = require("./server/whatsapp");
const dbs = require("./server/database/index");
const specs = require("./server/lib/specs");
const lib = require("./server/lib");

global.log = lib.log;

/**
 * EXPRESS SETUP
 */
const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: "*",
  },
  pingInterval: 25000,
  pingTimeout: 10000,
});

const port = process.env.PORT_NODE || 3000;

/**
 * MIDDLEWARES
 */
app.use((req, res, next) => {
  res.set("Cache-Control", "no-store");
  req.io = io;
  next();
});

app.use(
  bodyParser.urlencoded({
    extended: true,
    limit: "50mb",
    parameterLimit: 100000,
  })
);
app.use(bodyParser.json());

app.use(express.static("src/public"));
app.use(require("./server/router"));

/**
 * SOCKET.IO HANDLERS
 */
io.on("connection", (socket) => {
  console.log("🔌 Novo cliente conectado:", socket.id);

  specs.init(socket);

  socket.on("StartConnection", (data) => wa.connectToWhatsApp(data, io));
  socket.on("ConnectViaCode", (data) => wa.connectToWhatsApp(data, io, true));
  socket.on("LogoutDevice", (device) => wa.deleteCredentials(device, io));

  socket.on("disconnect", () => console.log("❌ Cliente desconectado:", socket.id));
});

/**
 * START SERVER
 */
server.listen(port, "0.0.0.0", () => {
  console.log(`🚀 Servidor rodando e ouvindo na porta ${port}`);
});

/**
 * RECONNECT DEVICES ON START
 */
dbs.db.query("SELECT * FROM devices WHERE status = 'Connected'", (err, results) => {
  if (err) {
    return console.error("Erro ao executar query:", err);
  }
  results.forEach((row) => {
    const number = row.body;
    if (/^\d+$/.test(number)) {
      console.log(`🔁 Reconectando dispositivo ${number}...`);
      wa.connectToWhatsApp(number);
    }
  });
});
