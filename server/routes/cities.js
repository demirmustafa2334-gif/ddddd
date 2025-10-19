const express = require('express');
const City = require('../models/City');
const District = require('../models/District');

const router = express.Router();

// Get all cities
router.get('/', async (req, res) => {
  try {
    const cities = await City.find({ isActive: true })
      .populate('districts', 'name slug')
      .sort({ name: 1 });

    res.json(cities);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get city by slug
router.get('/:slug', async (req, res) => {
  try {
    const city = await City.findOne({ 
      slug: req.params.slug, 
      isActive: true 
    }).populate({
      path: 'districts',
      match: { isActive: true },
      select: 'name slug description image touristAttractions localCuisine'
    });

    if (!city) {
      return res.status(404).json({ 
        message: 'Şehir bulunamadı' 
      });
    }

    res.json(city);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get districts of a city
router.get('/:slug/districts', async (req, res) => {
  try {
    const city = await City.findOne({ 
      slug: req.params.slug, 
      isActive: true 
    });

    if (!city) {
      return res.status(404).json({ 
        message: 'Şehir bulunamadı' 
      });
    }

    const districts = await District.find({ 
      city: city._id, 
      isActive: true 
    }).select('name slug description image touristAttractions localCuisine specialFlavors');

    res.json(districts);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Search cities
router.get('/search/:query', async (req, res) => {
  try {
    const query = req.params.query;
    const cities = await City.find({
      $and: [
        { isActive: true },
        {
          $or: [
            { name: { $regex: query, $options: 'i' } },
            { seoKeywords: { $regex: query, $options: 'i' } }
          ]
        }
      ]
    }).select('name slug description image').limit(10);

    res.json(cities);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

module.exports = router;