const express = require('express');
const District = require('../models/District');
const City = require('../models/City');

const router = express.Router();

// Get all districts
router.get('/', async (req, res) => {
  try {
    const districts = await District.find({ isActive: true })
      .populate('city', 'name slug')
      .sort({ name: 1 });

    res.json(districts);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get district by city and slug
router.get('/:citySlug/:slug', async (req, res) => {
  try {
    const city = await City.findOne({ 
      slug: req.params.citySlug, 
      isActive: true 
    });

    if (!city) {
      return res.status(404).json({ 
        message: 'Şehir bulunamadı' 
      });
    }

    const district = await District.findOne({
      slug: req.params.slug,
      city: city._id,
      isActive: true
    }).populate('city', 'name slug');

    if (!district) {
      return res.status(404).json({ 
        message: 'İlçe bulunamadı' 
      });
    }

    // Get related districts in the same city
    const relatedDistricts = await District.find({
      city: city._id,
      _id: { $ne: district._id },
      isActive: true
    }).select('name slug description image').limit(3);

    res.json({
      ...district.toObject(),
      relatedDistricts
    });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get districts by city
router.get('/city/:citySlug', async (req, res) => {
  try {
    const city = await City.findOne({ 
      slug: req.params.citySlug, 
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

// Search districts
router.get('/search/:query', async (req, res) => {
  try {
    const query = req.params.query;
    const districts = await District.find({
      $and: [
        { isActive: true },
        {
          $or: [
            { name: { $regex: query, $options: 'i' } },
            { seoKeywords: { $regex: query, $options: 'i' } }
          ]
        }
      ]
    })
    .populate('city', 'name slug')
    .select('name slug description image city')
    .limit(10);

    res.json(districts);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

module.exports = router;