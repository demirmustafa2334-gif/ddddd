import React from 'react';
import { Helmet } from 'react-helmet-async';
import { Link } from 'react-router-dom';
import styled from 'styled-components';
import { useQuery } from 'react-query';
import { FaMapMarkerAlt, FaUtensils, FaCamera, FaBook, FaArrowRight } from 'react-icons/fa';
import { api } from '../utils/api';

const HomeContainer = styled.div`
  min-height: 100vh;
`;

const HeroSection = styled.section`
  background: linear-gradient(135deg, ${props => props.theme.colors.primary} 0%, ${props => props.theme.colors.secondary} 100%);
  color: white;
  padding: 6rem 0;
  text-align: center;
  position: relative;
  overflow: hidden;

  &::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
  }
`;

const HeroContent = styled.div`
  position: relative;
  z-index: 1;
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const HeroTitle = styled.h1`
  font-size: 3.5rem;
  margin-bottom: 1.5rem;
  font-weight: 700;
  line-height: 1.1;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2.5rem;
  }
`;

const HeroSubtitle = styled.p`
  font-size: 1.3rem;
  margin-bottom: 2rem;
  opacity: 0.9;
  line-height: 1.6;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 1.1rem;
  }
`;

const HeroButtons = styled.div`
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 2rem;
`;

const FeatureSection = styled.section`
  padding: 5rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const FeatureGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const FeatureCard = styled.div`
  background: white;
  padding: 2rem;
  border-radius: 12px;
  text-align: center;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover {
    transform: translateY(-5px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const FeatureIcon = styled.div`
  width: 80px;
  height: 80px;
  background: ${props => props.theme.colors.primary};
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  color: white;
  font-size: 2rem;
`;

const FeatureTitle = styled.h3`
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: ${props => props.theme.colors.primary};
`;

const FeatureDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  line-height: 1.6;
`;

const CitiesSection = styled.section`
  padding: 5rem 0;
`;

const SectionTitle = styled.h2`
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 3rem;
  color: ${props => props.theme.colors.primary};
`;

const CitiesGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const CityCard = styled(Link)`
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-decoration: none;
  color: inherit;

  &:hover {
    transform: translateY(-5px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const CityImage = styled.div`
  height: 200px;
  background: linear-gradient(45deg, ${props => props.theme.colors.primary}, ${props => props.theme.colors.secondary});
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 3rem;
`;

const CityContent = styled.div`
  padding: 1.5rem;
`;

const CityName = styled.h3`
  font-size: 1.3rem;
  margin-bottom: 0.5rem;
  color: ${props => props.theme.colors.primary};
`;

const CityDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
`;

const BlogSection = styled.section`
  padding: 5rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const BlogGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const BlogCard = styled(Link)`
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  text-decoration: none;
  color: inherit;

  &:hover {
    transform: translateY(-5px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const BlogImage = styled.div`
  height: 200px;
  background: linear-gradient(45deg, ${props => props.theme.colors.accent}, ${props => props.theme.colors.secondary});
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 2rem;
`;

const BlogContent = styled.div`
  padding: 1.5rem;
`;

const BlogTitle = styled.h3`
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
  color: ${props => props.theme.colors.primary};
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
`;

const BlogExcerpt = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
`;

const ViewAllButton = styled(Link)`
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 2rem;
  padding: 0.75rem 1.5rem;
  background: ${props => props.theme.colors.primary};
  color: white;
  text-decoration: none;
  border-radius: 25px;
  transition: all 0.3s ease;

  &:hover {
    background: ${props => props.theme.colors.secondary};
    transform: translateX(5px);
  }
`;

const Home = () => {
  const { data: cities, isLoading: citiesLoading } = useQuery(
    'cities',
    () => api.get('/cities').then(res => res.data),
    {
      staleTime: 10 * 60 * 1000,
    }
  );

  const { data: blogPosts, isLoading: blogLoading } = useQuery(
    'featured-blog-posts',
    () => api.get('/blog/featured/posts').then(res => res.data),
    {
      staleTime: 5 * 60 * 1000,
    }
  );

  return (
    <HomeContainer>
      <Helmet>
        <title>Yerel Tanıtım - Türkiye'nin Şehir ve İlçeleri | Ana Sayfa</title>
        <meta name="description" content="Türkiye'nin tüm şehir ve ilçelerini keşfedin. Turistik yerler, yerel mutfak, kültürel özellikler ve daha fazlası hakkında detaylı bilgiler." />
        <meta name="keywords" content="türkiye, şehirler, ilçeler, turizm, yerel mutfak, kültür, tarih, yereltanitim" />
      </Helmet>

      <HeroSection>
        <HeroContent>
          <HeroTitle>Türkiye'yi Keşfedin</HeroTitle>
          <HeroSubtitle>
            Ülkemizin tüm şehir ve ilçelerini keşfedin. Turistik yerler, 
            yerel mutfak, kültürel özellikler ve daha fazlası hakkında 
            detaylı bilgiler bulun.
          </HeroSubtitle>
          <HeroButtons>
            <Link to="/sehirler" className="btn btn-accent">
              Şehirleri Keşfet
            </Link>
            <Link to="/blog" className="btn btn-secondary">
              Blog'u İncele
            </Link>
          </HeroButtons>
        </HeroContent>
      </HeroSection>

      <FeatureSection>
        <div className="container">
          <SectionTitle>Neden Yerel Tanıtım?</SectionTitle>
          <FeatureGrid>
            <FeatureCard>
              <FeatureIcon>
                <FaMapMarkerAlt />
              </FeatureIcon>
              <FeatureTitle>Kapsamlı Rehber</FeatureTitle>
              <FeatureDescription>
                Türkiye'nin tüm şehir ve ilçeleri hakkında detaylı bilgiler, 
                turistik yerler ve pratik rehberlik.
              </FeatureDescription>
            </FeatureCard>

            <FeatureCard>
              <FeatureIcon>
                <FaUtensils />
              </FeatureIcon>
              <FeatureTitle>Yerel Mutfak</FeatureTitle>
              <FeatureDescription>
                Her bölgenin özel lezzetlerini, geleneksel yemeklerini ve 
                mutfak kültürünü keşfedin.
              </FeatureDescription>
            </FeatureCard>

            <FeatureCard>
              <FeatureIcon>
                <FaCamera />
              </FeatureIcon>
              <FeatureTitle>Görsel Zenginlik</FeatureTitle>
              <FeatureDescription>
                Şehirlerin ve ilçelerin en güzel fotoğrafları ile 
                görsel bir şölen yaşayın.
              </FeatureDescription>
            </FeatureCard>

            <FeatureCard>
              <FeatureIcon>
                <FaBook />
              </FeatureIcon>
              <FeatureTitle>Kültürel Bilgi</FeatureTitle>
              <FeatureDescription>
                Tarihi, kültürel özellikler ve geleneksel etkinlikler 
                hakkında kapsamlı bilgiler.
              </FeatureDescription>
            </FeatureCard>
          </FeatureGrid>
        </div>
      </FeatureSection>

      <CitiesSection>
        <div className="container">
          <SectionTitle>Popüler Şehirler</SectionTitle>
          <CitiesGrid>
            {citiesLoading ? (
              Array.from({ length: 6 }).map((_, index) => (
                <CityCard key={index}>
                  <CityImage>...</CityImage>
                  <CityContent>
                    <CityName>Yükleniyor...</CityName>
                    <CityDescription>İçerik yükleniyor...</CityDescription>
                  </CityContent>
                </CityCard>
              ))
            ) : (
              cities?.slice(0, 6).map(city => (
                <CityCard key={city._id} to={`/sehir/${city.slug}`}>
                  <CityImage>
                    <FaMapMarkerAlt />
                  </CityImage>
                  <CityContent>
                    <CityName>{city.name}</CityName>
                    <CityDescription>{city.description}</CityDescription>
                  </CityContent>
                </CityCard>
              ))
            )}
          </CitiesGrid>
          <div className="text-center">
            <ViewAllButton to="/sehirler">
              Tüm Şehirleri Gör <FaArrowRight />
            </ViewAllButton>
          </div>
        </div>
      </CitiesSection>

      <BlogSection>
        <div className="container">
          <SectionTitle>Son Blog Yazıları</SectionTitle>
          <BlogGrid>
            {blogLoading ? (
              Array.from({ length: 3 }).map((_, index) => (
                <BlogCard key={index}>
                  <BlogImage>...</BlogImage>
                  <BlogContent>
                    <BlogTitle>Yükleniyor...</BlogTitle>
                    <BlogExcerpt>İçerik yükleniyor...</BlogExcerpt>
                  </BlogContent>
                </BlogCard>
              ))
            ) : (
              blogPosts?.slice(0, 3).map(post => (
                <BlogCard key={post._id} to={`/blog/${post.slug}`}>
                  <BlogImage>
                    <FaBook />
                  </BlogImage>
                  <BlogContent>
                    <BlogTitle>{post.title}</BlogTitle>
                    <BlogExcerpt>{post.excerpt}</BlogExcerpt>
                  </BlogContent>
                </BlogCard>
              ))
            )}
          </BlogGrid>
          <div className="text-center">
            <ViewAllButton to="/blog">
              Tüm Yazıları Gör <FaArrowRight />
            </ViewAllButton>
          </div>
        </div>
      </BlogSection>
    </HomeContainer>
  );
};

export default Home;