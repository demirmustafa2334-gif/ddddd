const express = require('express');
const ContactMessage = require('../models/ContactMessage');
const { adminAuth } = require('../middleware/auth');

const router = express.Router();

// Submit contact form
router.post('/', async (req, res) => {
  try {
    const { name, email, phone, subject, message, city, district } = req.body;

    const contactMessage = new ContactMessage({
      name,
      email,
      phone,
      subject,
      message,
      city,
      district
    });

    await contactMessage.save();

    res.status(201).json({ 
      message: 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.' 
    });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Get all contact messages (admin only)
router.get('/', adminAuth, async (req, res) => {
  try {
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 20;
    const isRead = req.query.isRead;

    let query = {};
    if (isRead !== undefined) {
      query.isRead = isRead === 'true';
    }

    const messages = await ContactMessage.find(query)
      .sort({ createdAt: -1 })
      .skip((page - 1) * limit)
      .limit(limit);

    const total = await ContactMessage.countDocuments(query);

    res.json({
      messages,
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

// Get unread messages count
router.get('/unread-count', adminAuth, async (req, res) => {
  try {
    const count = await ContactMessage.countDocuments({ isRead: false });
    res.json({ count });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Mark message as read
router.put('/:id/read', adminAuth, async (req, res) => {
  try {
    const message = await ContactMessage.findById(req.params.id);
    
    if (!message) {
      return res.status(404).json({ 
        message: 'Mesaj bulunamadı' 
      });
    }

    message.isRead = true;
    await message.save();

    res.json({ message: 'Mesaj okundu olarak işaretlendi' });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Reply to message
router.put('/:id/reply', adminAuth, async (req, res) => {
  try {
    const { replyMessage } = req.body;
    const message = await ContactMessage.findById(req.params.id);
    
    if (!message) {
      return res.status(404).json({ 
        message: 'Mesaj bulunamadı' 
      });
    }

    message.isReplied = true;
    message.replyMessage = replyMessage;
    message.repliedAt = new Date();
    message.repliedBy = req.user._id;
    await message.save();

    res.json({ message: 'Yanıt gönderildi' });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

// Delete message
router.delete('/:id', adminAuth, async (req, res) => {
  try {
    const message = await ContactMessage.findById(req.params.id);
    
    if (!message) {
      return res.status(404).json({ 
        message: 'Mesaj bulunamadı' 
      });
    }

    await ContactMessage.findByIdAndDelete(req.params.id);
    res.json({ message: 'Mesaj silindi' });
  } catch (error) {
    res.status(500).json({ 
      message: 'Sunucu hatası', 
      error: error.message 
    });
  }
});

module.exports = router;