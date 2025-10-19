import React from 'react';
import { Link } from 'react-router-dom';
import styled from 'styled-components';
import { FaMapMarkerAlt, FaPhone, FaEnvelope, FaFacebook, FaTwitter, FaInstagram, FaYoutube } from 'react-icons/fa';

const FooterContainer = styled.footer`
  background: ${props => props.theme.colors.primary};
  color: white;
  padding: 3rem 0 1rem;
  margin-top: auto;
`;

const FooterContent = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const FooterGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
`;

const FooterSection = styled.div`
  h3 {
    color: ${props => props.theme.colors.accent};
    margin-bottom: 1rem;
    font-size: 1.2rem;
  }

  p {
    line-height: 1.6;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.9);
  }
`;

const FooterLinks = styled.div`
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
`;

const FooterLink = styled(Link)`
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: color 0.3s ease;
  font-size: 0.9rem;

  &:hover {
    color: ${props => props.theme.colors.accent};
  }
`;

const ContactInfo = styled.div`
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
`;

const SocialLinks = styled.div`
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
`;

const SocialLink = styled.a`
  color: rgba(255, 255, 255, 0.8);
  font-size: 1.5rem;
  transition: color 0.3s ease;

  &:hover {
    color: ${props => props.theme.colors.accent};
  }
`;

const CitiesGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 0.5rem;
  max-height: 200px;
  overflow-y: auto;
`;

const CityTag = styled.span`
  background: rgba(255, 255, 255, 0.1);
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.8rem;
  color: rgba(255, 255, 255, 0.9);
  text-align: center;
  transition: background 0.3s ease;

  &:hover {
    background: rgba(255, 255, 255, 0.2);
  }
`;

const FooterBottom = styled.div`
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  padding-top: 1rem;
  text-align: center;
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.9rem;
`;

const Footer = () => {
  // Turkish cities for SEO
  const turkishCities = [
    'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin',
    'Aydın', 'Balıkesir', 'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa',
    'Çanakkale', 'Çankırı', 'Çorum', 'Denizli', 'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan',
    'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkâri', 'Hatay', 'Isparta',
    'Mersin', 'İstanbul', 'İzmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir',
    'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla',
    'Muş', 'Nevşehir', 'Niğde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt',
    'Sinop', 'Sivas', 'Tekirdağ', 'Tokat', 'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak',
    'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman', 'Kırıkkale', 'Batman',
    'Şırnak', 'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük', 'Kilis', 'Osmaniye', 'Düzce'
  ];

  return (
    <FooterContainer>
      <FooterContent>
        <FooterGrid>
          <FooterSection>
            <h3>Yerel Tanıtım</h3>
            <p>
              Türkiye'nin tüm şehir ve ilçelerini keşfedin. Turistik yerler, 
              yerel mutfak, kültürel özellikler ve daha fazlası için kapsamlı rehber.
            </p>
            <SocialLinks>
              <SocialLink href="#" aria-label="Facebook">
                <FaFacebook />
              </SocialLink>
              <SocialLink href="#" aria-label="Twitter">
                <FaTwitter />
              </SocialLink>
              <SocialLink href="#" aria-label="Instagram">
                <FaInstagram />
              </SocialLink>
              <SocialLink href="#" aria-label="YouTube">
                <FaYoutube />
              </SocialLink>
            </SocialLinks>
          </FooterSection>

          <FooterSection>
            <h3>Hızlı Linkler</h3>
            <FooterLinks>
              <FooterLink to="/">Ana Sayfa</FooterLink>
              <FooterLink to="/sehirler">Tüm Şehirler</FooterLink>
              <FooterLink to="/blog">Blog</FooterLink>
              <FooterLink to="/iletisim">İletişim</FooterLink>
              <FooterLink to="/admin">Admin Panel</FooterLink>
            </FooterLinks>
          </FooterSection>

          <FooterSection>
            <h3>İletişim Bilgileri</h3>
            <ContactInfo>
              <FaMapMarkerAlt />
              <span>İstanbul, Türkiye</span>
            </ContactInfo>
            <ContactInfo>
              <FaPhone />
              <span>+90 (212) 123 45 67</span>
            </ContactInfo>
            <ContactInfo>
              <FaEnvelope />
              <span>info@yereltanitim.com</span>
            </ContactInfo>
          </FooterSection>

          <FooterSection>
            <h3>Popüler Şehirler</h3>
            <FooterLinks>
              <FooterLink to="/sehir/istanbul">İstanbul</FooterLink>
              <FooterLink to="/sehir/ankara">Ankara</FooterLink>
              <FooterLink to="/sehir/izmir">İzmir</FooterLink>
              <FooterLink to="/sehir/antalya">Antalya</FooterLink>
              <FooterLink to="/sehir/bursa">Bursa</FooterLink>
              <FooterLink to="/sehir/konya">Konya</FooterLink>
            </FooterLinks>
          </FooterSection>
        </FooterGrid>

        <FooterSection>
          <h3>Türkiye Şehirleri (SEO)</h3>
          <CitiesGrid>
            {turkishCities.map(city => (
              <CityTag key={city}>{city}</CityTag>
            ))}
          </CitiesGrid>
        </FooterSection>

        <FooterBottom>
          <p>
            © 2024 Yerel Tanıtım. Tüm hakları saklıdır. | 
            <FooterLink to="/gizlilik"> Gizlilik Politikası</FooterLink> | 
            <FooterLink to="/kullanim-kosullari"> Kullanım Koşulları</FooterLink>
          </p>
        </FooterBottom>
      </FooterContent>
    </FooterContainer>
  );
};

export default Footer;