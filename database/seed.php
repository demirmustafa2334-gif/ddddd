<?php
require_once '../config/database.php';
require_once '../config/config.php';

// Turkish cities data
$cities_data = [
    ['name' => 'Adana', 'description' => 'Çukurova\'nın merkezi Adana, zengin tarım toprakları ve sıcak iklimi ile ünlüdür.', 'population' => 2220125, 'area' => 13870.00, 'established_year' => 1877],
    ['name' => 'Adıyaman', 'description' => 'Nemrut Dağı ve Kommagene Krallığı kalıntıları ile tarihi öneme sahip şehir.', 'population' => 632148, 'area' => 7337.00, 'established_year' => 1954],
    ['name' => 'Afyonkarahisar', 'description' => 'Termal kaplıcaları ve mermer üretimi ile tanınan şehir.', 'population' => 729483, 'area' => 14230.00, 'established_year' => 1923],
    ['name' => 'Ağrı', 'description' => 'Türkiye\'nin en yüksek dağı Ağrı Dağı\'na ev sahipliği yapan şehir.', 'population' => 535420, 'area' => 11376.00, 'established_year' => 1927],
    ['name' => 'Amasya', 'description' => 'Yeşilırmak kıyısında kurulu, Osmanlı şehzadelerinin eğitim gördüğü tarihi şehir.', 'population' => 335494, 'area' => 5520.00, 'established_year' => 1923],
    ['name' => 'Ankara', 'description' => 'Türkiye\'nin başkenti ve ikinci büyük şehri. Modern yapısı ve önemli kurumları ile ünlü.', 'population' => 5663322, 'area' => 25632.00, 'established_year' => 1923],
    ['name' => 'Antalya', 'description' => 'Akdeniz\'in incisi, dünyaca ünlü tatil destinasyonu ve turizm merkezi.', 'population' => 2511813, 'area' => 20177.00, 'established_year' => 1921],
    ['name' => 'Artvin', 'description' => 'Karadeniz\'in doğusunda, doğal güzellikleri ve yeşil ormanları ile ünlü şehir.', 'population' => 169501, 'area' => 7449.00, 'established_year' => 1921],
    ['name' => 'Aydın', 'description' => 'Ege\'nin bereketli topraklarında, antik kentleri ve incir üretimi ile tanınan şehir.', 'population' => 1119086, 'area' => 8007.00, 'established_year' => 1923],
    ['name' => 'Balıkesir', 'description' => 'Marmara ve Ege\'nin buluştuğu noktada, zeytin ve peynir üretimi ile ünlü şehir.', 'population' => 1240136, 'area' => 14433.00, 'established_year' => 1923],
    ['name' => 'Bilecik', 'description' => 'Osmanlı İmparatorluğu\'nun kurulduğu topraklar, tarihi önemi büyük şehir.', 'population' => 223448, 'area' => 4308.00, 'established_year' => 1923],
    ['name' => 'Bingöl', 'description' => 'Doğu Anadolu\'da, doğal güzellikleri ve termal kaynakları ile tanınan şehir.', 'population' => 281205, 'area' => 8125.00, 'established_year' => 1946],
    ['name' => 'Bitlis', 'description' => 'Van Gölü kıyısında, tarihi yapıları ve doğal güzellikleri ile ünlü şehir.', 'population' => 350994, 'area' => 8088.00, 'established_year' => 1929],
    ['name' => 'Bolu', 'description' => 'Karadeniz\'in güneyinde, doğal güzellikleri ve termal kaynakları ile tanınan şehir.', 'population' => 311810, 'area' => 8416.00, 'established_year' => 1923],
    ['name' => 'Burdur', 'description' => 'Göller Yöresi\'nde, doğal güzellikleri ve tarihi eserleri ile ünlü şehir.', 'population' => 273716, 'area' => 6887.00, 'established_year' => 1923],
    ['name' => 'Bursa', 'description' => 'Osmanlı İmparatorluğu\'nun ilk başkenti, tarihi çarşıları ve doğal güzellikleri ile ünlü.', 'population' => 3056121, 'area' => 10363.00, 'established_year' => 1923],
    ['name' => 'Çanakkale', 'description' => 'Gelibolu Yarımadası ve Truva antik kenti ile tarihi öneme sahip şehir.', 'population' => 557097, 'area' => 9737.00, 'established_year' => 1923],
    ['name' => 'Çankırı', 'description' => 'İç Anadolu\'da, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 195789, 'area' => 7512.00, 'established_year' => 1923],
    ['name' => 'Çorum', 'description' => 'Hitit medeniyetinin merkezi, tarihi önemi büyük şehir.', 'population' => 530864, 'area' => 12820.00, 'established_year' => 1923],
    ['name' => 'Denizli', 'description' => 'Pamukkale travertenleri ve antik kentleri ile dünyaca ünlü şehir.', 'population' => 1058752, 'area' => 11868.00, 'established_year' => 1923],
    ['name' => 'Diyarbakır', 'description' => 'Güneydoğu Anadolu\'nun merkezi, tarihi surları ve kültürel zenginliği ile ünlü.', 'population' => 1802988, 'area' => 15355.00, 'established_year' => 1923],
    ['name' => 'Edirne', 'description' => 'Osmanlı İmparatorluğu\'nun eski başkenti, tarihi yapıları ile ünlü şehir.', 'population' => 411528, 'area' => 6276.00, 'established_year' => 1923],
    ['name' => 'Elazığ', 'description' => 'Doğu Anadolu\'da, doğal güzellikleri ve kültürel zenginliği ile tanınan şehir.', 'population' => 591098, 'area' => 9153.00, 'established_year' => 1923],
    ['name' => 'Erzincan', 'description' => 'Doğu Anadolu\'da, doğal güzellikleri ve tarihi yapıları ile ünlü şehir.', 'population' => 234747, 'area' => 11974.00, 'established_year' => 1923],
    ['name' => 'Erzurum', 'description' => 'Doğu Anadolu\'nun merkezi, tarihi yapıları ve kış sporları ile ünlü şehir.', 'population' => 762062, 'area' => 25066.00, 'established_year' => 1923],
    ['name' => 'Eskişehir', 'description' => 'Anadolu\'nun merkezinde, modern yapısı ve kültürel zenginliği ile tanınan şehir.', 'population' => 898369, 'area' => 13952.00, 'established_year' => 1923],
    ['name' => 'Gaziantep', 'description' => 'Güneydoğu Anadolu\'nun büyük şehri, mutfağı ve tarihi yapıları ile ünlü.', 'population' => 2139810, 'area' => 7202.00, 'established_year' => 1923],
    ['name' => 'Giresun', 'description' => 'Karadeniz kıyısında, fındık üretimi ve doğal güzellikleri ile tanınan şehir.', 'population' => 453912, 'area' => 6934.00, 'established_year' => 1923],
    ['name' => 'Gümüşhane', 'description' => 'Karadeniz\'in doğusunda, doğal güzellikleri ve maden yatakları ile ünlü şehir.', 'population' => 141702, 'area' => 6934.00, 'established_year' => 1923],
    ['name' => 'Hakkâri', 'description' => 'Doğu Anadolu\'nun güneydoğusunda, doğal güzellikleri ile tanınan şehir.', 'population' => 280991, 'area' => 7121.00, 'established_year' => 1936],
    ['name' => 'Hatay', 'description' => 'Akdeniz kıyısında, çok kültürlü yapısı ve tarihi zenginliği ile ünlü şehir.', 'population' => 1630000, 'area' => 5403.00, 'established_year' => 1939],
    ['name' => 'Isparta', 'description' => 'Göller Yöresi\'nde, gül üretimi ve doğal güzellikleri ile tanınan şehir.', 'population' => 445678, 'area' => 8933.00, 'established_year' => 1923],
    ['name' => 'Mersin', 'description' => 'Akdeniz kıyısında, liman şehri ve modern yapısı ile tanınan şehir.', 'population' => 1844533, 'area' => 15853.00, 'established_year' => 1923],
    ['name' => 'İstanbul', 'description' => 'Türkiye\'nin en büyük şehri ve kültürel başkenti. Tarihi yarımada, Boğaz manzarası ve zengin kültürel mirası ile dünyaca ünlü bir metropol.', 'population' => 15519267, 'area' => 5461.00, 'established_year' => 1453],
    ['name' => 'İzmir', 'description' => 'Ege\'nin incisi İzmir, antik çağlardan beri önemli bir liman şehri. Modern yapısı, deniz manzarası ve zengin tarihi ile ünlü.', 'population' => 4425789, 'area' => 11891.00, 'established_year' => 1923],
    ['name' => 'Kars', 'description' => 'Doğu Anadolu\'da, tarihi yapıları ve kış turizmi ile tanınan şehir.', 'population' => 292660, 'area' => 10139.00, 'established_year' => 1923],
    ['name' => 'Kastamonu', 'description' => 'Karadeniz\'in iç kesimlerinde, tarihi yapıları ve doğal güzellikleri ile ünlü şehir.', 'population' => 376377, 'area' => 13108.00, 'established_year' => 1923],
    ['name' => 'Kayseri', 'description' => 'İç Anadolu\'da, tarihi yapıları ve ticaret merkezi olması ile tanınan şehir.', 'population' => 1404276, 'area' => 16917.00, 'established_year' => 1923],
    ['name' => 'Kırklareli', 'description' => 'Trakya\'da, doğal güzellikleri ve tarihi yapıları ile tanınan şehir.', 'population' => 366363, 'area' => 6550.00, 'established_year' => 1923],
    ['name' => 'Kırşehir', 'description' => 'İç Anadolu\'da, tarihi yapıları ve kültürel zenginliği ile tanınan şehir.', 'population' => 242944, 'area' => 6585.00, 'established_year' => 1923],
    ['name' => 'Kocaeli', 'description' => 'Marmara Bölgesi\'nde, sanayi merkezi ve liman şehri olması ile tanınan şehir.', 'population' => 1993231, 'area' => 3624.00, 'established_year' => 1923],
    ['name' => 'Konya', 'description' => 'Mevlana\'nın şehri. Tarihi önemi, mistik atmosferi ve geleneksel kültürü ile ünlü bir şehir.', 'population' => 2288450, 'area' => 38373.00, 'established_year' => 1923],
    ['name' => 'Kütahya', 'description' => 'İç Anadolu\'da, çini sanatı ve termal kaynakları ile tanınan şehir.', 'population' => 579257, 'area' => 11875.00, 'established_year' => 1923],
    ['name' => 'Malatya', 'description' => 'Doğu Anadolu\'da, kayısı üretimi ve tarihi yapıları ile ünlü şehir.', 'population' => 806156, 'area' => 12313.00, 'established_year' => 1923],
    ['name' => 'Manisa', 'description' => 'Ege\'de, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 1465730, 'area' => 13120.00, 'established_year' => 1923],
    ['name' => 'Kahramanmaraş', 'description' => 'Güneydoğu Anadolu\'da, dondurması ve tarihi yapıları ile ünlü şehir.', 'population' => 1161634, 'area' => 14427.00, 'established_year' => 1973],
    ['name' => 'Mardin', 'description' => 'Güneydoğu Anadolu\'da, tarihi yapıları ve kültürel zenginliği ile ünlü şehir.', 'population' => 854716, 'area' => 8891.00, 'established_year' => 1923],
    ['name' => 'Muğla', 'description' => 'Ege ve Akdeniz kıyılarında, tatil beldeleri ve doğal güzellikleri ile ünlü şehir.', 'population' => 1024100, 'area' => 12654.00, 'established_year' => 1923],
    ['name' => 'Muş', 'description' => 'Doğu Anadolu\'da, doğal güzellikleri ve tarihi yapıları ile tanınan şehir.', 'population' => 411117, 'area' => 8196.00, 'established_year' => 1923],
    ['name' => 'Nevşehir', 'description' => 'Kapadokya\'nın merkezi, peri bacaları ve tarihi yapıları ile dünyaca ünlü şehir.', 'population' => 300836, 'area' => 5347.00, 'established_year' => 1954],
    ['name' => 'Niğde', 'description' => 'İç Anadolu\'da, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 364961, 'area' => 7312.00, 'established_year' => 1923],
    ['name' => 'Ordu', 'description' => 'Karadeniz kıyısında, fındık üretimi ve doğal güzellikleri ile tanınan şehir.', 'population' => 760041, 'area' => 5959.00, 'established_year' => 1923],
    ['name' => 'Rize', 'description' => 'Karadeniz kıyısında, çay üretimi ve doğal güzellikleri ile ünlü şehir.', 'population' => 344359, 'area' => 3920.00, 'established_year' => 1923],
    ['name' => 'Sakarya', 'description' => 'Marmara Bölgesi\'nde, sanayi merkezi ve doğal güzellikleri ile tanınan şehir.', 'population' => 1034201, 'area' => 4821.00, 'established_year' => 1954],
    ['name' => 'Samsun', 'description' => 'Karadeniz kıyısında, büyük şehir ve liman merkezi olması ile tanınan şehir.', 'population' => 1358400, 'area' => 9575.00, 'established_year' => 1923],
    ['name' => 'Siirt', 'description' => 'Güneydoğu Anadolu\'da, tarihi yapıları ve kültürel zenginliği ile tanınan şehir.', 'population' => 331980, 'area' => 5406.00, 'established_year' => 1923],
    ['name' => 'Sinop', 'description' => 'Karadeniz kıyısında, tarihi yapıları ve doğal güzellikleri ile ünlü şehir.', 'population' => 216460, 'area' => 5862.00, 'established_year' => 1923],
    ['name' => 'Sivas', 'description' => 'İç Anadolu\'da, tarihi yapıları ve kültürel zenginliği ile tanınan şehir.', 'population' => 636121, 'area' => 28488.00, 'established_year' => 1923],
    ['name' => 'Tekirdağ', 'description' => 'Marmara kıyısında, tarım ve sanayi merkezi olması ile tanınan şehir.', 'population' => 1111915, 'area' => 6218.00, 'established_year' => 1923],
    ['name' => 'Tokat', 'description' => 'Karadeniz\'in iç kesimlerinde, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 612646, 'area' => 10042.00, 'established_year' => 1923],
    ['name' => 'Trabzon', 'description' => 'Karadeniz kıyısında, tarihi yapıları ve doğal güzellikleri ile ünlü şehir.', 'population' => 808974, 'area' => 4665.00, 'established_year' => 1923],
    ['name' => 'Tunceli', 'description' => 'Doğu Anadolu\'da, doğal güzellikleri ile tanınan şehir.', 'population' => 83443, 'area' => 7774.00, 'established_year' => 1936],
    ['name' => 'Şanlıurfa', 'description' => 'Güneydoğu Anadolu\'da, tarihi yapıları ve kültürel zenginliği ile ünlü şehir.', 'population' => 2041008, 'area' => 18784.00, 'established_year' => 1923],
    ['name' => 'Uşak', 'description' => 'Ege\'de, halı üretimi ve tarihi yapıları ile tanınan şehir.', 'population' => 369433, 'area' => 5341.00, 'established_year' => 1953],
    ['name' => 'Van', 'description' => 'Doğu Anadolu\'da, Van Gölü ve tarihi yapıları ile ünlü şehir.', 'population' => 1128989, 'area' => 19069.00, 'established_year' => 1923],
    ['name' => 'Yozgat', 'description' => 'İç Anadolu\'da, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 424981, 'area' => 14123.00, 'established_year' => 1923],
    ['name' => 'Zonguldak', 'description' => 'Karadeniz kıyısında, kömür üretimi ve sanayi merkezi olması ile tanınan şehir.', 'population' => 596053, 'area' => 3331.00, 'established_year' => 1923],
    ['name' => 'Aksaray', 'description' => 'İç Anadolu\'da, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 429069, 'area' => 7999.00, 'established_year' => 1989],
    ['name' => 'Bayburt', 'description' => 'Doğu Anadolu\'da, tarihi yapıları ve doğal güzellikleri ile tanınan şehir.', 'population' => 81199, 'area' => 3652.00, 'established_year' => 1989],
    ['name' => 'Karaman', 'description' => 'İç Anadolu\'da, tarihi yapıları ve kültürel zenginliği ile tanınan şehir.', 'population' => 260838, 'area' => 9163.00, 'established_year' => 1989],
    ['name' => 'Kırıkkale', 'description' => 'İç Anadolu\'da, sanayi merkezi olması ile tanınan şehir.', 'population' => 283017, 'area' => 4365.00, 'established_year' => 1989],
    ['name' => 'Batman', 'description' => 'Güneydoğu Anadolu\'da, petrol üretimi ve sanayi merkezi olması ile tanınan şehir.', 'population' => 620278, 'area' => 4699.00, 'established_year' => 1990],
    ['name' => 'Şırnak', 'description' => 'Güneydoğu Anadolu\'da, doğal güzellikleri ile tanınan şehir.', 'population' => 546589, 'area' => 7291.00, 'established_year' => 1990],
    ['name' => 'Bartın', 'description' => 'Karadeniz kıyısında, doğal güzellikleri ve tarihi yapıları ile tanınan şehir.', 'population' => 201711, 'area' => 2140.00, 'established_year' => 1991],
    ['name' => 'Ardahan', 'description' => 'Doğu Anadolu\'nun kuzeydoğusunda, doğal güzellikleri ile tanınan şehir.', 'population' => 96139, 'area' => 4961.00, 'established_year' => 1992],
    ['name' => 'Iğdır', 'description' => 'Doğu Anadolu\'nun doğusunda, doğal güzellikleri ile tanınan şehir.', 'population' => 203159, 'area' => 3665.00, 'established_year' => 1992],
    ['name' => 'Yalova', 'description' => 'Marmara kıyısında, termal kaynakları ve doğal güzellikleri ile tanınan şehir.', 'population' => 296333, 'area' => 847.00, 'established_year' => 1995],
    ['name' => 'Karabük', 'description' => 'Karadeniz\'in iç kesimlerinde, sanayi merkezi olması ile tanınan şehir.', 'population' => 248014, 'area' => 4142.00, 'established_year' => 1995],
    ['name' => 'Kilis', 'description' => 'Güneydoğu Anadolu\'da, sınır şehri olması ile tanınan şehir.', 'population' => 142792, 'area' => 1427.00, 'established_year' => 1995],
    ['name' => 'Osmaniye', 'description' => 'Akdeniz kıyısında, doğal güzellikleri ile tanınan şehir.', 'population' => 548556, 'area' => 3324.00, 'established_year' => 1996],
    ['name' => 'Düzce', 'description' => 'Karadeniz kıyısında, doğal güzellikleri ile tanınan şehir.', 'population' => 400976, 'area' => 2594.00, 'established_year' => 1999]
];

echo "Türkiye şehirleri veritabanına ekleniyor...\n";

foreach ($cities_data as $city_data) {
    $slug = createSlug($city_data['name']);
    
    // Check if city already exists
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM cities WHERE slug = ?");
    $stmt->execute([$slug]);
    $exists = $stmt->fetch()['count'];
    
    if ($exists == 0) {
        $sql = "INSERT INTO cities (name, slug, description, population, area, established_year, seo_keywords, meta_description, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        $seo_keywords = $city_data['name'] . ', turizm, gezilecek yerler, yerel mutfak, ' . strtolower($city_data['name']);
        $meta_description = $city_data['description'] . ' ' . $city_data['name'] . ' hakkında detaylı bilgiler, turistik yerler ve yerel mutfak.';
        
        $stmt->execute([
            $city_data['name'],
            $slug,
            $city_data['description'],
            $city_data['population'],
            $city_data['area'],
            $city_data['established_year'],
            $seo_keywords,
            $meta_description,
            1
        ]);
        
        echo "✓ " . $city_data['name'] . " eklendi\n";
    } else {
        echo "- " . $city_data['name'] . " zaten mevcut\n";
    }
}

// Add some sample districts for major cities
$districts_data = [
    // İstanbul districts
    ['city' => 'istanbul', 'name' => 'Fatih', 'description' => 'İstanbul\'un tarihi yarımadasında yer alan Fatih, Bizans ve Osmanlı dönemlerinin en önemli eserlerini barındırır.'],
    ['city' => 'istanbul', 'name' => 'Beşiktaş', 'description' => 'Boğaz kıyısında yer alan Beşiktaş, modern yaşamın ve tarihi dokunun buluştuğu bir ilçedir.'],
    ['city' => 'istanbul', 'name' => 'Kadıköy', 'description' => 'Anadolu yakasının en popüler ilçesi Kadıköy, genç nüfusu ve canlı kültürel yaşamı ile ünlüdür.'],
    ['city' => 'istanbul', 'name' => 'Şişli', 'description' => 'Modern İstanbul\'un kalbi Şişli, iş merkezleri ve alışveriş mekanları ile ünlüdür.'],
    ['city' => 'istanbul', 'name' => 'Beyoğlu', 'description' => 'Tarihi Galata ve Pera bölgelerini kapsayan Beyoğlu, kültürel yaşamın merkezidir.'],
    
    // Ankara districts
    ['city' => 'ankara', 'name' => 'Çankaya', 'description' => 'Ankara\'nın merkezi ilçesi Çankaya, modern yapısı ve önemli kurumları ile ünlüdür.'],
    ['city' => 'ankara', 'name' => 'Altındağ', 'description' => 'Ankara\'nın tarihi ilçesi Altındağ, Roma döneminden kalma eserler ve geleneksel yapısı ile ünlüdür.'],
    ['city' => 'ankara', 'name' => 'Keçiören', 'description' => 'Ankara\'nın büyük ilçelerinden Keçiören, modern yerleşim alanları ile tanınır.'],
    
    // İzmir districts
    ['city' => 'izmir', 'name' => 'Konak', 'description' => 'İzmir\'in merkezi ilçesi Konak, tarihi yapıları ve modern yaşamın buluştuğu yerdir.'],
    ['city' => 'izmir', 'name' => 'Karşıyaka', 'description' => 'İzmir\'in kuzeyinde yer alan Karşıyaka, deniz manzarası ve yeşil alanları ile ünlüdür.'],
    ['city' => 'izmir', 'name' => 'Bornova', 'description' => 'İzmir\'in doğusunda yer alan Bornova, üniversite şehri olması ile tanınır.'],
    
    // Antalya districts
    ['city' => 'antalya', 'name' => 'Muratpaşa', 'description' => 'Antalya\'nın merkezi ilçesi Muratpaşa, tarihi Kaleiçi ve modern yaşamın buluştuğu yerdir.'],
    ['city' => 'antalya', 'name' => 'Konyaaltı', 'description' => 'Antalya\'nın batısında yer alan Konyaaltı, plajları ve doğal güzellikleri ile ünlüdür.'],
    ['city' => 'antalya', 'name' => 'Kepez', 'description' => 'Antalya\'nın kuzeyinde yer alan Kepez, modern yerleşim alanları ile tanınır.'],
    
    // Bursa districts
    ['city' => 'bursa', 'name' => 'Osmangazi', 'description' => 'Bursa\'nın merkezi ilçesi Osmangazi, tarihi yapıları ve geleneksel çarşıları ile ünlüdür.'],
    ['city' => 'bursa', 'name' => 'Nilüfer', 'description' => 'Bursa\'nın modern ilçesi Nilüfer, yeşil alanları ve modern yaşamı ile tanınır.'],
    ['city' => 'bursa', 'name' => 'Yıldırım', 'description' => 'Bursa\'nın doğusunda yer alan Yıldırım, tarihi yapıları ile ünlüdür.'],
    
    // Konya districts
    ['city' => 'konya', 'name' => 'Meram', 'description' => 'Konya\'nın merkezi ilçesi Meram, Mevlana Türbesi ve tarihi yapıları ile ünlüdür.'],
    ['city' => 'konya', 'name' => 'Karatay', 'description' => 'Konya\'nın tarihi ilçesi Karatay, Selçuklu dönemi eserleri ile tanınır.'],
    ['city' => 'konya', 'name' => 'Selçuklu', 'description' => 'Konya\'nın modern ilçesi Selçuklu, yeni yerleşim alanları ile ünlüdür.']
];

echo "\nİlçeler veritabanına ekleniyor...\n";

foreach ($districts_data as $district_data) {
    // Get city ID
    $stmt = $db->prepare("SELECT id FROM cities WHERE slug = ?");
    $stmt->execute([$district_data['city']]);
    $city = $stmt->fetch();
    
    if ($city) {
        $slug = createSlug($district_data['name']);
        
        // Check if district already exists
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM districts WHERE slug = ? AND city_id = ?");
        $stmt->execute([$slug, $city['id']]);
        $exists = $stmt->fetch()['count'];
        
        if ($exists == 0) {
            $sql = "INSERT INTO districts (city_id, name, slug, description, seo_keywords, meta_description, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            $seo_keywords = $district_data['name'] . ', ' . $district_data['city'] . ', turizm, gezilecek yerler, yerel mutfak';
            $meta_description = $district_data['description'] . ' ' . $district_data['name'] . ' hakkında detaylı bilgiler.';
            
            $stmt->execute([
                $city['id'],
                $district_data['name'],
                $slug,
                $district_data['description'],
                $seo_keywords,
                $meta_description,
                1
            ]);
            
            echo "✓ " . $district_data['name'] . " (" . $district_data['city'] . ") eklendi\n";
        } else {
            echo "- " . $district_data['name'] . " (" . $district_data['city'] . ") zaten mevcut\n";
        }
    }
}

// Add some sample blog posts
$blog_posts_data = [
    [
        'title' => 'İstanbul\'da Gezilecek En Güzel Yerler',
        'content' => '<h2>İstanbul\'un Tarihi Yarımadası</h2><p>İstanbul\'un kalbi olan tarihi yarımada, binlerce yıllık tarihi ile ziyaretçilerini büyülüyor. Sultanahmet Camii, Ayasofya, Topkapı Sarayı ve Kapalıçarşı gibi dünyaca ünlü yapılar bu bölgede yer alıyor.</p><h3>Sultanahmet Camii</h3><p>Mavi Camii olarak da bilinen Sultanahmet Camii, 17. yüzyılda inşa edilmiş muhteşem bir Osmanlı eseridir.</p>',
        'excerpt' => 'İstanbul\'un en güzel ve tarihi yerlerini keşfedin. Sultanahmet, Ayasofya, Topkapı Sarayı ve daha fazlası.',
        'category' => 'tourism',
        'city' => 'istanbul'
    ],
    [
        'title' => 'Antalya\'nın En Lezzetli Yemekleri',
        'content' => '<h2>Akdeniz Mutfağının Lezzetleri</h2><p>Antalya\'nın zengin mutfak kültürü, taze deniz ürünleri ve Akdeniz otları ile harmanlanmış eşsiz lezzetler sunuyor.</p><h3>Lahmacun</h3><p>İnce hamur üzerine kıyma, soğan ve baharatların serpiştirildiği geleneksel yemek.</p>',
        'excerpt' => 'Antalya\'nın en lezzetli yemeklerini keşfedin. Taze balık, sebze yemekleri ve geleneksel tatlar.',
        'category' => 'cuisine',
        'city' => 'antalya'
    ],
    [
        'title' => 'Ankara\'nın Tarihi Yerleri',
        'content' => '<h2>Başkentin Tarihi Mirası</h2><p>Ankara, sadece siyasi merkez değil, aynı zamanda zengin bir tarihi mirasa sahip şehirdir.</p><h3>Anıtkabir</h3><p>Mustafa Kemal Atatürk\'ün anıt mezarı, modern Türkiye\'nin simgesi olan önemli bir yapıdır.</p>',
        'excerpt' => 'Ankara\'nın tarihi yerlerini keşfedin. Anıtkabir, Roma Hamamı ve daha fazlası.',
        'category' => 'history',
        'city' => 'ankara'
    ],
    [
        'title' => 'İzmir\'in Kültürel Zenginlikleri',
        'content' => '<h2>Ege\'nin Kültür Başkenti</h2><p>İzmir, farklı medeniyetlerin buluştuğu, zengin kültürel mirasa sahip bir şehirdir.</p><h3>Kemeraltı Çarşısı</h3><p>Yüzyıllık çarşı, geleneksel el sanatları ve modern alışveriş imkanlarını bir arada sunuyor.</p>',
        'excerpt' => 'İzmir\'in kültürel zenginliklerini keşfedin. Müzeler, sanat galerileri ve kültürel etkinlikler.',
        'category' => 'culture',
        'city' => 'izmir'
    ],
    [
        'title' => 'Bursa\'nın Doğal Güzellikleri',
        'content' => '<h2>Yeşil Bursa</h2><p>Bursa, Uludağ\'ın eteklerinde kurulu, doğal güzellikleri ile ünlü bir şehirdir.</p><h3>Uludağ Milli Parkı</h3><p>Kış turizminin merkezi Uludağ, yaz aylarında da doğa yürüyüşleri için ideal.</p>',
        'excerpt' => 'Bursa\'nın doğal güzelliklerini keşfedin. Uludağ, göller ve yeşil alanlar.',
        'category' => 'nature',
        'city' => 'bursa'
    ]
];

echo "\nBlog yazıları veritabanına ekleniyor...\n";

foreach ($blog_posts_data as $post_data) {
    // Get city ID
    $stmt = $db->prepare("SELECT id FROM cities WHERE slug = ?");
    $stmt->execute([$post_data['city']]);
    $city = $stmt->fetch();
    
    if ($city) {
        $slug = createSlug($post_data['title']);
        
        // Check if post already exists
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM blog_posts WHERE slug = ?");
        $stmt->execute([$slug]);
        $exists = $stmt->fetch()['count'];
        
        if ($exists == 0) {
            $sql = "INSERT INTO blog_posts (title, slug, content, excerpt, featured_image, author_id, city_id, category, tags, seo_keywords, meta_description, is_published, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            
            $seo_keywords = $post_data['title'] . ', ' . $post_data['city'] . ', turizm, blog';
            $meta_description = $post_data['excerpt'];
            $tags = json_encode([$post_data['category'], $post_data['city'], 'turizm']);
            
            $stmt->execute([
                $post_data['title'],
                $slug,
                $post_data['content'],
                $post_data['excerpt'],
                'default-blog.jpg',
                1, // Admin user ID
                $city['id'],
                $post_data['category'],
                $tags,
                $seo_keywords,
                $meta_description,
                1,
                date('Y-m-d H:i:s')
            ]);
            
            echo "✓ " . $post_data['title'] . " eklendi\n";
        } else {
            echo "- " . $post_data['title'] . " zaten mevcut\n";
        }
    }
}

echo "\nVeritabanı başarıyla dolduruldu!\n";
echo "Toplam " . count($cities_data) . " şehir eklendi.\n";
echo "Toplam " . count($districts_data) . " ilçe eklendi.\n";
echo "Toplam " . count($blog_posts_data) . " blog yazısı eklendi.\n";
?>