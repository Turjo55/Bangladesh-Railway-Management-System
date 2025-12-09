const express = require('express');
const mongoose = require('mongoose');
const session = require('express-session');
const MongoStore = require('connect-mongo');
const path = require('path');
const dotenv = require('dotenv');

// Load env vars
dotenv.config();

const app = express();

// --- Configuration ---
const PORT = process.env.PORT || 3000;
const MONGO_URI = process.env.MONGO_URI || 'mongodb+srv://BdRail:tanvir123@bdrail.jlvfhwx.mongodb.net/?appName=BdRail';

// --- Database Connection ---
mongoose.connect(MONGO_URI)
    .then(() => console.log('MongoDB Atlas Connected'))
    .catch(err => console.error('MongoDB Connection Error:', err));

// --- Middleware ---
app.use(express.urlencoded({ extended: true })); // Parse form data
app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// --- Session Setup ---
app.use(session({
    secret: process.env.SESSION_SECRET || 'railway_secret_key',
    resave: false,
    saveUninitialized: false,
    store: MongoStore.create({ mongoUrl: MONGO_URI }),
    cookie: { maxAge: 1000 * 60 * 60 * 24 } // 1 day
}));

// --- View Engine ---
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// --- Global Variables for Views ---
app.use((req, res, next) => {
    res.locals.user = req.session.user || null;
    res.locals.pageTitle = 'Bangladesh Railway';

    // Update Last Active for Logged In Users
    if (req.session.user) {
        const User = require('./models/User');
        User.findByIdAndUpdate(req.session.user._id, { lastActive: new Date() }).catch(err => console.error('Error updating lastActive:', err));
    }

    next();
});

// --- Routes (Placeholders) ---
app.use('/', require('./routes/index'));
app.use('/auth', require('./routes/auth'));
app.use('/booking', require('./routes/booking'));
app.use('/admin', require('./routes/admin'));

// --- Start Server ---
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
