const express = require('express');
const BlogPost = require('../models/BlogPost');
const City = require('../models/City');
const District = require('../models/District');
const { auth, adminAuth } = require('../middleware/auth');

const router = express.Router();

// Get all published blog posts
router.get('/', async (req, res) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const category = req.query.category;
    const city = req.query.city;

    let query = { isPublished: true };
    
    if (category) {
      query.category = category;
    }
    
    if (city) {
      const cityDoc = await City.findOne({ slug: city });
      if (cityDoc) {
        query.city = cityDoc._id;
      }
    }

    const posts = await BlogPost.find(query)
      .populate('author', 'firstName lastName')
      .populate('city', 'name slug')
      .populate('district', 'name slug')
      .populate('relatedDistricts', 'name slug')
      .sort({ publishedAt: -1 })
      .skip((page - 1) * limit)
      .limit(limit);

    const total = await BlogPost.countDocuments(query);

    res.json({
      posts,
      pagination: {
        current: page,
        pages: Math.ceil(total / limit),
        total
      }
    });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get blog post by slug
router.get('/:slug', async (req, res) => {
  try {
    const post = await BlogPost.findOne({ 
      slug: req.params.slug, 
      isPublished: true 
    })
      .populate('author', 'firstName lastName')
      .populate('city', 'name slug')
      .populate('district', 'name slug')
      .populate('relatedDistricts', 'name slug description image');

    if (!post) {
      return res.status(404).json({ 
        message: 'Blog yazısı bulunamadı' 
      });
    }

    // Increment view count
    post.viewCount += 1;
    await post.save();

    res.json(post);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get featured posts
router.get('/featured/posts', async (req, res) => {
  try {
    const posts = await BlogPost.find({ 
      isPublished: true, 
      isFeatured: true 
    })
      .populate('author', 'firstName lastName')
      .populate('city', 'name slug')
      .populate('district', 'name slug')
      .sort({ publishedAt: -1 })
      .limit(5);

    res.json(posts);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get posts by category
router.get('/category/:category', async (req, res) => {
  try {
    const posts = await BlogPost.find({ 
      category: req.params.category,
      isPublished: true 
    })
      .populate('author', 'firstName lastName')
      .populate('city', 'name slug')
      .populate('district', 'name slug')
      .sort({ publishedAt: -1 })
      .limit(10);

    res.json(posts);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Admin routes
// Create blog post
router.post('/', auth, async (req, res) => {
  try {
    const post = new BlogPost({
      ...req.body,
      author: req.user._id
    });

    await post.save();
    await post.populate('author', 'firstName lastName');
    await post.populate('city', 'name slug');
    await post.populate('district', 'name slug');

    res.status(201).json(post);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Update blog post
router.put('/:id', auth, async (req, res) => {
  try {
    const post = await BlogPost.findById(req.params.id);
    
    if (!post) {
      return res.status(404).json({ 
        message: 'Blog yazısı bulunamadı' 
      });
    }

    // Check if user is author or admin
    if (post.author.toString() !== req.user._id.toString() && req.user.role !== 'admin') {
      return res.status(403).json({ 
        message: 'Bu işlem için yetkiniz yok' 
      });
    }

    Object.assign(post, req.body);
    await post.save();
    await post.populate('author', 'firstName lastName');
    await post.populate('city', 'name slug');
    await post.populate('district', 'name slug');

    res.json(post);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Delete blog post
router.delete('/:id', auth, async (req, res) => {
  try {
    const post = await BlogPost.findById(req.params.id);
    
    if (!post) {
      return res.status(404).json({ 
        message: 'Blog yazısı bulunamadı' 
      });
    }

    // Check if user is author or admin
    if (post.author.toString() !== req.user._id.toString() && req.user.role !== 'admin') {
      return res.status(403).json({ 
        message: 'Bu işlem için yetkiniz yok' 
      });
    }

    await BlogPost.findByIdAndDelete(req.params.id);
    res.json({ message: 'Blog yazısı silindi' });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get all posts for admin
router.get('/admin/all', adminAuth, async (req, res) => {
  try {
    const posts = await BlogPost.find()
      .populate('author', 'firstName lastName')
      .populate('city', 'name slug')
      .populate('district', 'name slug')
      .sort({ createdAt: -1 });

    res.json(posts);
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

module.exports = router;