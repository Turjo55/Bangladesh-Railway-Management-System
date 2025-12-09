const express = require('express');
const router = express.Router();
const User = require('../models/User');

// GET Login Page
router.get('/login', (req, res) => {
    res.render('login', { error: null });
});

// POST Login Logic
router.post('/login', async (req, res) => {
    const { email, password } = req.body;
    try {
        const user = await User.findOne({ email });
        if (user && (await user.matchPassword(password))) {
            req.session.user = {
                id: user._id,
                name: user.name,
                email: user.email,
                role: user.role
            };
            return res.redirect('/');
        }
        res.render('login', { error: 'Invalid email or password' });
    } catch (err) {
        console.error(err);
        res.render('login', { error: 'Server error occurred' });
    }
});

// GET Register Page
router.get('/register', (req, res) => {
    res.render('login'); // Reusing login page as it likely has toggle
});

// POST Register Logic
router.post('/register', async (req, res) => {
    const { name, email, phone, password } = req.body;
    try {
        const existingUser = await User.findOne({ email });
        if (existingUser) {
            return res.render('login', { error: 'User already exists' });
        }
        await User.create({ name, email, phone, password });
        res.render('login', { success: 'Registration successful! Please login.' });
    } catch (err) {
        res.render('login', { error: 'Registration failed' });
    }
});

// Logout
router.get('/logout', (req, res) => {
    req.session.destroy(() => {
        res.redirect('/');
    });
});

module.exports = router;
