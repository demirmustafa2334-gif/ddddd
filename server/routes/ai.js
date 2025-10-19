const express = require('express');
const OpenAI = require('openai');
const { auth } = require('../middleware/auth');
const City = require('../models/City');
const District = require('../models/District');
const BlogPost = require('../models/BlogPost');

const router = express.Router();

const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY
});

// Generate blog post content using ChatGPT
router.post('/generate-blog-post', auth, async (req, res) => {
  try {
    const { citySlug, districtSlug, topic, category = 'tourism' } = req.body;

    let city, district;
    
    if (citySlug) {
      city = await City.findOne({ slug: citySlug, isActive: true });
      if (!city) {
        return res.status(404).json({ message: 'Şehir bulunamadı' });
      }
    }

    if (districtSlug && city) {
      district = await District.findOne({ 
        slug: districtSlug, 
        city: city._id, 
        isActive: true 
      });
      if (!district) {
        return res.status(404).json({ message: 'İlçe bulunamadı' });
      }
    }

    const location = district ? `${district.name}, ${city.name}` : city.name;
    const locationType = district ? 'ilçe' : 'şehir';

    const prompt = `Türkiye'deki ${location} ${locationType}si hakkında "${topic}" konusunda detaylı bir blog yazısı yaz. 

Yazı şu özelliklere sahip olmalı:
- Tamamen Türkçe olmalı
- SEO dostu olmalı
- 800-1200 kelime arasında olmalı
- Yerel kültür, tarih, turistik yerler, yemek kültürü hakkında bilgiler içermeli
- Okuyucuyu bu yeri ziyaret etmeye teşvik eden bir ton kullanmalı
- Yerel lezzetler ve özel tatlar hakkında detaylı bilgi vermeli
- Turistik çekicilikler hakkında pratik bilgiler içermeli

Yazıyı şu formatta döndür:
{
  "title": "Başlık",
  "excerpt": "Kısa özet (150-200 kelime)",
  "content": "Tam içerik (HTML formatında)",
  "tags": ["etiket1", "etiket2", "etiket3"],
  "seoKeywords": ["anahtar1", "anahtar2", "anahtar3"]
}`;

    const completion = await openai.chat.completions.create({
      model: "gpt-4",
      messages: [
        {
          role: "system",
          content: "Sen Türkiye turizmi konusunda uzman bir içerik yazarısın. SEO dostu, bilgilendirici ve çekici blog yazıları yazıyorsun."
        },
        {
          role: "user",
          content: prompt
        }
      ],
      temperature: 0.7,
      max_tokens: 2000
    });

    const response = completion.choices[0].message.content;
    
    try {
      const generatedContent = JSON.parse(response);
      
      // Create slug from title
      const slug = generatedContent.title
        .toLowerCase()
        .replace(/[^a-z0-9ğüşıöçĞÜŞİÖÇ\s]/g, '')
        .replace(/\s+/g, '-')
        .trim();

      res.json({
        ...generatedContent,
        slug,
        city: city ? city._id : null,
        district: district ? district._id : null,
        category
      });
    } catch (parseError) {
      // If JSON parsing fails, return the raw content
      res.json({
        title: topic,
        excerpt: response.substring(0, 200) + '...',
        content: response,
        tags: [topic, location],
        seoKeywords: [location, topic, 'turizm'],
        slug: topic.toLowerCase().replace(/\s+/g, '-'),
        city: city ? city._id : null,
        district: district ? district._id : null,
        category
      });
    }
  } catch (error) {
    console.error('OpenAI API Error:', error);
    res.status(500).json({ 
      message: 'AI içerik oluşturma hatası', 
      error: error.message 
    });
  }
});

// Generate city description
router.post('/generate-city-description', auth, async (req, res) => {
  try {
    const { citySlug } = req.body;
    
    const city = await City.findOne({ slug: citySlug, isActive: true });
    if (!city) {
      return res.status(404).json({ message: 'Şehir bulunamadı' });
    }

    const prompt = `Türkiye'deki ${city.name} şehri hakkında kapsamlı bir açıklama yaz. 

Açıklama şunları içermeli:
- Şehrin tarihi ve kültürel önemi
- Turistik çekicilikler
- Yerel mutfak ve özel lezzetler
- İklim ve coğrafi özellikler
- Nüfus ve ekonomik durum
- Ulaşım bilgileri
- En iyi ziyaret zamanı

Türkçe yaz ve SEO dostu olsun.`;

    const completion = await openai.chat.completions.create({
      model: "gpt-4",
      messages: [
        {
          role: "system",
          content: "Sen Türkiye coğrafyası ve turizmi konusunda uzman bir rehbersin."
        },
        {
          role: "user",
          content: prompt
        }
      ],
      temperature: 0.7,
      max_tokens: 1000
    });

    const description = completion.choices[0].message.content;
    
    res.json({ description });
  } catch (error) {
    console.error('OpenAI API Error:', error);
    res.status(500).json({ 
      message: 'AI açıklama oluşturma hatası', 
      error: error.message 
    });
  }
});

// Generate district description
router.post('/generate-district-description', auth, async (req, res) => {
  try {
    const { citySlug, districtSlug } = req.body;
    
    const city = await City.findOne({ slug: citySlug, isActive: true });
    if (!city) {
      return res.status(404).json({ message: 'Şehir bulunamadı' });
    }

    const district = await District.findOne({ 
      slug: districtSlug, 
      city: city._id, 
      isActive: true 
    });
    if (!district) {
      return res.status(404).json({ message: 'İlçe bulunamadı' });
    }

    const prompt = `Türkiye'deki ${district.name}, ${city.name} ilçesi hakkında detaylı bir açıklama yaz. 

Açıklama şunları içermeli:
- İlçenin tarihi ve kültürel özellikleri
- Yerel turistik yerler ve çekicilikler
- Özel yemekler ve lezzetler
- Geleneksel el sanatları ve kültürel etkinlikler
- Doğal güzellikler
- Konaklama seçenekleri
- Ulaşım bilgileri

Türkçe yaz ve SEO dostu olsun.`;

    const completion = await openai.chat.completions.create({
      model: "gpt-4",
      messages: [
        {
          role: "system",
          content: "Sen Türkiye'nin yerel kültürleri ve turizmi konusunda uzman bir rehbersin."
        },
        {
          role: "user",
          content: prompt
        }
      ],
      temperature: 0.7,
      max_tokens: 1000
    });

    const description = completion.choices[0].message.content;
    
    res.json({ description });
  } catch (error) {
    console.error('OpenAI API Error:', error);
    res.status(500).json({ 
      message: 'AI açıklama oluşturma hatası', 
      error: error.message 
    });
  }
});

module.exports = router;