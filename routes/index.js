const express = require('express');
const router = express.Router();
const Route = require('../models/Route');
const Train = require('../models/Train');

// Home Page
router.get('/', async (req, res) => {
    try {
        // Fetch unique stations from routes for the dropdown
        const routes = await Route.find({});
        const fromStations = [...new Set(routes.map(r => r.from_station))];
        const toStations = [...new Set(routes.map(r => r.to_station))];
        const stations = [...new Set([...fromStations, ...toStations])].sort();

        res.render('index', { stations });
    } catch (err) {
        console.error(err);
        res.render('index', { stations: [] });
    }
});

// Search Results
router.get('/search_results', async (req, res) => {
    const { from, to, date, class_type } = req.query;
    try {
        // Find routes matching From/To
        const routes = await Route.find({
            from_station: new RegExp(from, 'i'),
            to_station: new RegExp(to, 'i')
        });

        // Enrich with Train details
        const results = await Promise.all(routes.map(async (route) => {
            const train = await Train.findOne({ code: route.train_code });
            return {
                ...route.toObject(),
                train_name: train ? train.name : 'Unknown Train',
                train_type: train ? train.type : 'Intercity',
                available_seats: 50 // Mock availability
            };
        }));

        res.render('search_results', { results, searchParams: { from, to, date, class_type } });
    } catch (err) {
        console.error(err);
        res.redirect('/');
    }
});

// --- Static & Information Pages ---

router.get('/about', (req, res) => res.render('pages/about'));
router.get('/terms', (req, res) => res.render('pages/terms'));
router.get('/faq', (req, res) => res.render('pages/faq'));
router.get('/refund_policy', (req, res) => res.render('pages/refund_policy'));

// Dynamic Route Map
router.get('/route_map', async (req, res) => {
    try {
        const routes = await Route.find({});
        res.render('pages/route_map', { routes });
    } catch (err) {
        res.render('pages/route_map', { routes: [] });
    }
});

// History Page
router.get('/history', (req, res) => {
    res.render('pages/history');
});


// Contact Form
router.get('/contact', (req, res) => res.render('pages/contact', { success: null }));
router.post('/contact', (req, res) => {
    // Simulate sending email
    res.render('pages/contact', { success: 'Thank you! Your message has been sent to our support team.' });
});

module.exports = router;
