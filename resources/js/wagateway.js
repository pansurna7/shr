const {
    default: makeWASocket,
    DisconnectReason,
    fetchLatestBaileysVersion,
    isJidBroadcast,
    makeInMemoryStore,
    useMultiFileAuthState
} = require("@adiwajshing/baileys");

const log = require("pino");
const { Boom } = require("@hapi/boom");
const path = require('path');
const fs = require('fs');
const express = require("express");
const fileUpload = require('express-fileupload');
const cors = require('cors');
const bodyParser = require("body-parser");
const qrcode = require("qrcode");

const app = express();
const server = require("http").createServer(app);
const io = require("socket.io")(server, {
    path: '/socket.io',
    transports: ['polling'], // Passenger lebih stabil dengan polling
    allowEIO3: true,
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});
const port = process.env.PORT || 3000;

// Middleware
app.use(fileUpload({ createParentPath: true }));
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// FIX PATH ASSETS: Gunakan path absolut agar tidak stuck di loader
app.use("/assets", express.static(path.join(__dirname, "client/assets")));

// Global Variables
let sock;
let qr;
let soket;
let lastStatus = "loading";
const store = makeInMemoryStore({ logger: log({ level: "silent" }).child({ level: "silent", stream: "store" }) });

// FIX: Fungsi isConnected harus ada agar API send-message tidak error
const isConnected = () => {
    return sock && sock.user && sock.user.id;
};

// Fungsi Update Status
const updateQR = (statusType) => {
    lastStatus = statusType;
    if (!soket) return;

    switch (statusType) {
        case "qr":
            if (qr) {
                qrcode.toDataURL(qr, (err, url) => {
                    soket.emit("qr", url);
                    soket.emit("log", "QR Code diterima, silakan scan!");
                    soket.emit("qrstatus", "/assets/loader.gif"); // Hilangkan titik di depan path
                });
            }
            break;
        case "connected":
            soket.emit("qrstatus", "/assets/check.svg");
            soket.emit("log", "WhatsApp terhubung!");
            soket.emit("qr", "");
            break;
        case "loading":
            soket.emit("qrstatus", "/assets/loader.gif");
            soket.emit("log", "Menghubungkan ke WhatsApp...");
            break;
        case "disconnected":
            soket.emit("qrstatus", "/assets/scan.svg");
            soket.emit("log", "Terputus! Menunggu QR Code...");
            break;
    }
};

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('baileys_auth_info');
    let { version } = await fetchLatestBaileysVersion();

    sock = makeWASocket({
        auth: state,
        version,
        printQRInTerminal: true,
        logger: log({ level: "silent" }),
        browser: ["Gemini-Bot", "Chrome", "1.0.0"]
    });

    store.bind(sock.ev);

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr: newQr } = update;

        if (newQr) {
            qr = newQr;
            updateQR("qr");
        }

        if (connection === 'close') {
            qr = null;
            let reason = new Boom(lastDisconnect?.error)?.output.statusCode;
            console.log("Koneksi ditutup:", reason);

            if (reason === DisconnectReason.loggedOut) {
                updateQR("disconnected");
                if (fs.existsSync('baileys_auth_info')) {
                    fs.rmSync('baileys_auth_info', { recursive: true, force: true });
                }
                setTimeout(() => connectToWhatsApp(), 3000);
            } else {
                updateQR("loading");
                connectToWhatsApp();
            }
        } else if (connection === 'open') {
            qr = null;
            console.log('WhatsApp Terhubung!');
            updateQR("connected");
        }
    });

    sock.ev.on("creds.update", saveCreds);

    // Filter pesan agar bot tidak menjawab pesan sendiri
    sock.ev.on("messages.upsert", async ({ messages, type }) => {
        if (type === "notify") {
            const m = messages[0];
            if (!m.key.fromMe) {
                const noWa = m.key.remoteJid;
                const pesan = m.message?.conversation || m.message?.extendedTextMessage?.text || "";
                if (pesan.toLowerCase() === "ping") {
                    await sock.sendMessage(noWa, { text: "Pong" }, { quoted: m });
                }
            }
        }
    });
}

// Socket.io Connection
io.on("connection", (socket) => {
    soket = socket;
    console.log("Browser Terhubung: " + socket.id);

    if (isConnected()) {
        updateQR("connected");
    } else if (qr) {
        updateQR("qr");
    } else {
        updateQR(lastStatus);
    }
});

// --- API ROUTES ---
app.get("/scan", (req, res) => {
    res.sendFile(path.join(__dirname, "client/server.html"));
});

app.get("/", (req, res) => {
    res.sendFile(path.join(__dirname, "client/index.html"));
});

app.post("/send-message", async (req, res) => {
    const { number, message } = req.body;

    if (!isConnected()) {
        return res.status(500).json({ status: false, response: "WhatsApp belum terhubung!" });
    }

    try {
        const numberWA = number.includes('@s.whatsapp.net') ? number : '62' + number.substring(1) + "@s.whatsapp.net";

        if (req.files) {
            let filesimpan = req.files.file_dikirim;
            let file_ubah_nama = new Date().getTime() + '_' + filesimpan.name;
            let path_simpan = path.join(__dirname, 'uploads', file_ubah_nama);

            if (!fs.existsSync('./uploads')) fs.mkdirSync('./uploads');

            await filesimpan.mv(path_simpan);
            let extensionName = path.extname(path_simpan).toLowerCase();

            let options = {};
            if (['.jpg', '.jpeg', '.png'].includes(extensionName)) {
                options = { image: { url: path_simpan }, caption: message };
            } else if (['.mp3', '.ogg'].includes(extensionName)) {
                options = { audio: { url: path_simpan }, mimetype: 'audio/mp4' };
            } else {
                options = { document: { url: path_simpan }, mimetype: filesimpan.mimetype, fileName: filesimpan.name, caption: message };
            }

            await sock.sendMessage(numberWA, options);
            fs.unlinkSync(path_simpan);
            res.json({ status: true, response: "File terkirim" });
        } else {
            const result = await sock.sendMessage(numberWA, { text: message });
            res.json({ status: true, response: result });
        }
    } catch (err) {
        res.status(500).json({ status: false, response: err.message });
    }
});

// --- START SERVER ---
connectToWhatsApp().catch(err => console.log("Error Start: " + err));

server.listen(port, () => {
    console.log("Server running...");
});
