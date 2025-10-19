const mongoose = require('mongoose');
const City = require('../models/City');
const District = require('../models/District');
const User = require('../models/User');
require('dotenv').config();

// Turkish cities data
const citiesData = [
  {
    name: 'Adana',
    slug: 'adana',
    description: 'Türkiye\'nin güneyinde yer alan Adana, tarım ve sanayi şehri olarak bilinir. Zengin mutfağı ve sıcak iklimi ile tanınır.',
    image: '/images/cities/adana.jpg',
    coordinates: { lat: 37.0000, lng: 35.3213 },
    population: 2200000,
    area: 14030,
    establishedYear: 1923,
    seoKeywords: ['adana', 'çukurova', 'kebap', 'tarım', 'sanayi'],
    metaDescription: 'Adana şehri hakkında detaylı bilgiler, turistik yerler, yerel mutfak ve kültürel özellikler.',
    touristAttractions: [
      {
        name: 'Merkez Park',
        description: 'Şehrin kalbinde yer alan büyük park alanı.',
        type: 'modern'
      },
      {
        name: 'Seyhan Barajı',
        description: 'Şehrin su ihtiyacını karşılayan büyük baraj.',
        type: 'modern'
      }
    ],
    localCuisine: [
      {
        name: 'Adana Kebabı',
        description: 'Acılı kıyma ile yapılan ünlü kebap türü.',
        ingredients: ['kuzu eti', 'kırmızı biber', 'tuz', 'karabiber'],
        preparation: 'Acılı kıyma şişe geçirilerek mangalda pişirilir.'
      },
      {
        name: 'Şırdan',
        description: 'Kuzu işkembesi ile yapılan geleneksel yemek.',
        ingredients: ['kuzu işkembesi', 'pirinç', 'baharatlar'],
        preparation: 'İşkembe temizlenip içine pilav doldurularak pişirilir.'
      }
    ],
    specialFlavors: [
      {
        name: 'Şalgam Suyu',
        description: 'Turp ve havuçtan yapılan geleneksel içecek.',
        type: 'drink'
      }
    ]
  },
  {
    name: 'Ankara',
    slug: 'ankara',
    description: 'Türkiye\'nin başkenti Ankara, modern bir şehir olarak tarihi ve kültürel zenginlikleri barındırır.',
    image: '/images/cities/ankara.jpg',
    coordinates: { lat: 39.9334, lng: 32.8597 },
    population: 5500000,
    area: 25632,
    establishedYear: 1923,
    seoKeywords: ['ankara', 'başkent', 'atatürk', 'anıtkabir', 'kültür'],
    metaDescription: 'Ankara başkenti hakkında detaylı bilgiler, tarihi yerler, müzeler ve kültürel etkinlikler.',
    touristAttractions: [
      {
        name: 'Anıtkabir',
        description: 'Mustafa Kemal Atatürk\'ün anıt mezarı.',
        type: 'historical'
      },
      {
        name: 'Ankara Kalesi',
        description: 'Şehrin tarihi kalesi ve çevresindeki tarihi doku.',
        type: 'historical'
      }
    ],
    localCuisine: [
      {
        name: 'Ankara Tava',
        description: 'Kuzu eti ve sebzelerle yapılan geleneksel yemek.',
        ingredients: ['kuzu eti', 'patlıcan', 'biber', 'domates'],
        preparation: 'Et ve sebzeler tava içinde pişirilir.'
      }
    ],
    specialFlavors: [
      {
        name: 'Ankara Armudu',
        description: 'Yerel armut çeşidi.',
        type: 'dessert'
      }
    ]
  },
  {
    name: 'Antalya',
    slug: 'antalya',
    description: 'Türkiye\'nin en popüler turizm merkezlerinden biri olan Antalya, Akdeniz kıyısında yer alır.',
    image: '/images/cities/antalya.jpg',
    coordinates: { lat: 36.8969, lng: 30.7133 },
    population: 2500000,
    area: 20177,
    establishedYear: 133,
    seoKeywords: ['antalya', 'turizm', 'akdeniz', 'plaj', 'tarih'],
    metaDescription: 'Antalya turizm merkezi hakkında detaylı bilgiler, plajlar, tarihi yerler ve doğal güzellikler.',
    touristAttractions: [
      {
        name: 'Kaleiçi',
        description: 'Tarihi şehir merkezi ve eski evler.',
        type: 'historical'
      },
      {
        name: 'Düden Şelalesi',
        description: 'Şehir merkezinde yer alan doğal şelale.',
        type: 'natural'
      }
    ],
    localCuisine: [
      {
        name: 'Antalya Piyazı',
        description: 'Nohut ve maydanozla yapılan salata.',
        ingredients: ['nohut', 'maydanoz', 'soğan', 'zeytinyağı'],
        preparation: 'Haşlanmış nohut ve doğranmış sebzeler karıştırılır.'
      }
    ],
    specialFlavors: [
      {
        name: 'Portakal Çiçeği Reçeli',
        description: 'Portakal çiçeklerinden yapılan özel reçel.',
        type: 'dessert'
      }
    ]
  },
  {
    name: 'İstanbul',
    slug: 'istanbul',
    description: 'Türkiye\'nin en büyük şehri ve kültürel başkenti İstanbul, iki kıtayı birleştiren eşsiz bir şehir.',
    image: '/images/cities/istanbul.jpg',
    coordinates: { lat: 41.0082, lng: 28.9784 },
    population: 15500000,
    area: 5461,
    establishedYear: 660,
    seoKeywords: ['istanbul', 'boğaz', 'tarih', 'kültür', 'turizm'],
    metaDescription: 'İstanbul şehri hakkında detaylı bilgiler, tarihi yerler, kültürel etkinlikler ve turistik mekanlar.',
    touristAttractions: [
      {
        name: 'Ayasofya',
        description: 'Bizans döneminden kalma tarihi kilise.',
        type: 'historical'
      },
      {
        name: 'Sultanahmet Camii',
        description: 'Mavi Cami olarak da bilinen tarihi cami.',
        type: 'religious'
      },
      {
        name: 'Kapalıçarşı',
        description: 'Dünyanın en büyük kapalı çarşılarından biri.',
        type: 'cultural'
      }
    ],
    localCuisine: [
      {
        name: 'İstanbul Böreği',
        description: 'Yufka ve peynirle yapılan geleneksel börek.',
        ingredients: ['yufka', 'peynir', 'yumurta', 'süt'],
        preparation: 'Yufka katları arasına peynir konarak fırında pişirilir.'
      }
    ],
    specialFlavors: [
      {
        name: 'Türk Kahvesi',
        description: 'Geleneksel Türk kahvesi.',
        type: 'drink'
      }
    ]
  },
  {
    name: 'İzmir',
    slug: 'izmir',
    description: 'Ege Denizi kıyısında yer alan İzmir, modern bir şehir olarak tarihi ve doğal güzellikleri bir arada sunar.',
    image: '/images/cities/izmir.jpg',
    coordinates: { lat: 38.4192, lng: 27.1287 },
    population: 4400000,
    area: 11811,
    establishedYear: 3000,
    seoKeywords: ['izmir', 'ege', 'efes', 'çeşme', 'kültür'],
    metaDescription: 'İzmir şehri hakkında detaylı bilgiler, Ege mutfağı, tarihi yerler ve doğal güzellikler.',
    touristAttractions: [
      {
        name: 'Efes Antik Kenti',
        description: 'Antik dönemden kalma önemli arkeolojik alan.',
        type: 'historical'
      },
      {
        name: 'Çeşme',
        description: 'Ege Denizi kıyısında popüler tatil beldesi.',
        type: 'natural'
      }
    ],
    localCuisine: [
      {
        name: 'İzmir Köfte',
        description: 'Özel baharatlarla yapılan köfte türü.',
        ingredients: ['kıyma', 'soğan', 'ekmek', 'baharatlar'],
        preparation: 'Malzemeler karıştırılıp köfte şeklinde pişirilir.'
      }
    ],
    specialFlavors: [
      {
        name: 'İncir',
        description: 'Ege bölgesinin ünlü meyvesi.',
        type: 'dessert'
      }
    ]
  }
];

// Sample districts data for each city
const districtsData = {
  'adana': [
    {
      name: 'Seyhan',
      slug: 'seyhan',
      description: 'Adana\'nın merkez ilçesi olan Seyhan, şehrin kalbi konumundadır.',
      image: '/images/districts/seyhan.jpg',
      population: 800000,
      area: 420,
      seoKeywords: ['seyhan', 'adana merkez', 'çukurova'],
      metaDescription: 'Seyhan ilçesi hakkında detaylı bilgiler ve turistik yerler.',
      touristAttractions: [
        {
          name: 'Seyhan Barajı',
          description: 'Şehrin su ihtiyacını karşılayan büyük baraj.',
          type: 'modern',
          address: 'Seyhan Barajı, Adana',
          openingHours: '24 saat açık',
          entryFee: 'Ücretsiz'
        }
      ],
      localCuisine: [
        {
          name: 'Adana Kebabı',
          description: 'Acılı kıyma ile yapılan ünlü kebap.',
          ingredients: ['kuzu eti', 'kırmızı biber', 'tuz'],
          preparation: 'Acılı kıyma şişe geçirilerek mangalda pişirilir.',
          restaurantRecommendations: ['Kebapçı İskender', 'Zeynel Usta']
        }
      ],
      specialFlavors: [
        {
          name: 'Şalgam Suyu',
          description: 'Turp ve havuçtan yapılan geleneksel içecek.',
          type: 'drink',
          whereToFind: ['Lokantalarda', 'Sokak satıcılarında']
        }
      ],
      culturalHighlights: [
        {
          title: 'Adana Film Festivali',
          description: 'Yıllık düzenlenen uluslararası film festivali.',
          type: 'festival'
        }
      ],
      accommodation: [
        {
          name: 'Seyhan Otel',
          type: 'hotel',
          description: 'Merkezi konumda modern otel.',
          priceRange: '200-400 TL',
          contact: '+90 322 123 45 67'
        }
      ],
      transportation: {
        howToReach: 'Adana Şakirpaşa Havalimanı\'ndan ulaşım sağlanabilir.',
        localTransport: 'Otobüs, dolmuş ve taksi ile ulaşım.',
        carRental: 'Havalimanında araç kiralama hizmetleri mevcuttur.'
      }
    }
  ],
  'ankara': [
    {
      name: 'Çankaya',
      slug: 'cankaya',
      description: 'Ankara\'nın en prestijli ilçesi olan Çankaya, devlet kurumlarının merkezidir.',
      image: '/images/districts/cankaya.jpg',
      population: 950000,
      area: 268,
      seoKeywords: ['çankaya', 'ankara merkez', 'devlet'],
      metaDescription: 'Çankaya ilçesi hakkında detaylı bilgiler ve turistik yerler.',
      touristAttractions: [
        {
          name: 'Anıtkabir',
          description: 'Mustafa Kemal Atatürk\'ün anıt mezarı.',
          type: 'historical',
          address: 'Anıtkabir, Çankaya',
          openingHours: '09:00-17:00',
          entryFee: 'Ücretsiz'
        }
      ],
      localCuisine: [
        {
          name: 'Ankara Tava',
          description: 'Kuzu eti ve sebzelerle yapılan geleneksel yemek.',
          ingredients: ['kuzu eti', 'patlıcan', 'biber'],
          preparation: 'Et ve sebzeler tava içinde pişirilir.',
          restaurantRecommendations: ['Tarihi Ankara Lokantası']
        }
      ],
      specialFlavors: [
        {
          name: 'Ankara Armudu',
          description: 'Yerel armut çeşidi.',
          type: 'dessert',
          whereToFind: ['Meyve bahçelerinde', 'Pazarlarda']
        }
      ],
      culturalHighlights: [
        {
          title: 'Ankara Müzik Festivali',
          description: 'Yıllık düzenlenen klasik müzik festivali.',
          type: 'music'
        }
      ],
      accommodation: [
        {
          name: 'Çankaya Otel',
          type: 'hotel',
          description: 'Merkezi konumda lüks otel.',
          priceRange: '300-600 TL',
          contact: '+90 312 123 45 67'
        }
      ],
      transportation: {
        howToReach: 'Esenboğa Havalimanı\'ndan ulaşım sağlanabilir.',
        localTransport: 'Metro, otobüs ve taksi ile ulaşım.',
        carRental: 'Havalimanında ve şehir merkezinde araç kiralama.'
      }
    }
  ]
};

const connectDB = async () => {
  try {
    await mongoose.connect(process.env.MONGODB_URI || 'mongodb://localhost:27017/yereltanitim');
    console.log('MongoDB Connected');
  } catch (error) {
    console.error('Database connection error:', error);
    process.exit(1);
  }
};

const seedData = async () => {
  try {
    await connectDB();

    // Clear existing data
    await City.deleteMany({});
    await District.deleteMany({});
    console.log('Existing data cleared');

    // Create admin user
    const adminUser = await User.findOne({ email: 'admin@yereltanitim.com' });
    if (!adminUser) {
      const newAdmin = new User({
        username: 'admin',
        email: 'admin@yereltanitim.com',
        password: 'admin123',
        role: 'admin',
        firstName: 'Admin',
        lastName: 'User'
      });
      await newAdmin.save();
      console.log('Admin user created');
    }

    // Create cities
    const createdCities = [];
    for (const cityData of citiesData) {
      const city = new City(cityData);
      await city.save();
      createdCities.push(city);
      console.log(`City created: ${city.name}`);
    }

    // Create districts
    for (const city of createdCities) {
      const cityDistricts = districtsData[city.slug] || [];
      for (const districtData of cityDistricts) {
        const district = new District({
          ...districtData,
          city: city._id
        });
        await district.save();
        
        // Add district to city
        city.districts.push(district._id);
        await city.save();
        
        console.log(`District created: ${district.name}, ${city.name}`);
      }
    }

    console.log('Data seeding completed successfully!');
    process.exit(0);
  } catch (error) {
    console.error('Error seeding data:', error);
    process.exit(1);
  }
};

// Run seeding if this file is executed directly
if (require.main === module) {
  seedData();
}

module.exports = { seedData, citiesData, districtsData };