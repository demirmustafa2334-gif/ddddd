import React from 'react';
import { Helmet } from 'react-helmet-async';
import { Link, useParams } from 'react-router-dom';
import styled from 'styled-components';
import { useQuery } from 'react-query';
import { FaMapMarkerAlt, FaUtensils, FaCamera, FaUsers, FaCalendarAlt, FaArrowRight } from 'react-icons/fa';
import { api } from '../utils/api';

const CityContainer = styled.div`
  min-height: 100vh;
`;

const HeroSection = styled.section`
  background: linear-gradient(135deg, ${props => props.theme.colors.primary} 0%, ${props => props.theme.colors.secondary} 100%);
  color: white;
  padding: 4rem 0;
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
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  text-align: center;
`;

const CityName = styled.h1`
  font-size: 3.5rem;
  margin-bottom: 1rem;
  font-weight: 700;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2.5rem;
  }
`;

const CityDescription = styled.p`
  font-size: 1.3rem;
  margin-bottom: 2rem;
  opacity: 0.9;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 1.1rem;
  }
`;

const CityStats = styled.div`
  display: flex;
  justify-content: center;
  gap: 3rem;
  flex-wrap: wrap;
  margin-top: 2rem;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    gap: 1.5rem;
  }
`;

const StatItem = styled.div`
  text-align: center;
`;

const StatValue = styled.div`
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
`;

const StatLabel = styled.div`
  font-size: 0.9rem;
  opacity: 0.8;
`;

const ContentSection = styled.section`
  padding: 4rem 0;
`;

const ContentGrid = styled.div`
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 3rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;

  @media (max-width: ${props => props.theme.breakpoints.tablet}) {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
`;

const MainContent = styled.div`
  h2 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 1.5rem;
    font-size: 2rem;
  }

  h3 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 1rem;
    font-size: 1.5rem;
  }

  p {
    line-height: 1.8;
    margin-bottom: 1.5rem;
    color: ${props => props.theme.colors.text};
  }
`;

const Sidebar = styled.div`
  h3 {
    color: ${props => props.theme.colors.primary};
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
  }
`;

const AttractionsGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
`;

const AttractionCard = styled.div`
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const AttractionName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
`;

const AttractionDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
`;

const AttractionType = styled.span`
  display: inline-block;
  background: ${props => props.theme.colors.primary};
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.8rem;
  margin-top: 0.5rem;
`;

const CuisineGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
`;

const CuisineCard = styled.div`
  background: white;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: ${props => props.theme.shadows.small};
  text-align: center;
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-2px);
  }
`;

const CuisineName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1rem;
`;

const CuisineDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.8rem;
  line-height: 1.4;
`;

const DistrictsSection = styled.section`
  padding: 4rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const DistrictsGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const DistrictCard = styled(Link)`
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: ${props => props.theme.shadows.small};
  text-decoration: none;
  color: inherit;
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover {
    transform: translateY(-3px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const DistrictName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
`;

const DistrictDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 1rem;
`;

const DistrictFeatures = styled.div`
  display: flex;
  gap: 1rem;
  font-size: 0.8rem;
  color: ${props => props.theme.colors.textLight};
`;

const FeatureItem = styled.span`
  display: flex;
  align-items: center;
  gap: 0.25rem;
`;

const LoadingState = styled.div`
  text-align: center;
  padding: 4rem 2rem;
  color: ${props => props.theme.colors.textLight};
  font-size: 1.1rem;
`;

const ErrorState = styled.div`
  text-align: center;
  padding: 4rem 2rem;
  color: ${props => props.theme.colors.danger};
`;

const CityDetail = () => {
  const { slug } = useParams();

  const { data: city, isLoading, error } = useQuery(
    ['city', slug],
    () => api.get(`/cities/${slug}`).then(res => res.data),
    {
      enabled: !!slug,
    }
  );

  const { data: districts } = useQuery(
    ['districts', slug],
    () => api.get(`/cities/${slug}/districts`).then(res => res.data),
    {
      enabled: !!slug,
    }
  );

  if (isLoading) {
    return (
      <CityContainer>
        <LoadingState>Şehir bilgileri yükleniyor...</LoadingState>
      </CityContainer>
    );
  }

  if (error || !city) {
    return (
      <CityContainer>
        <ErrorState>
          Şehir bulunamadı veya bir hata oluştu.
        </ErrorState>
      </CityContainer>
    );
  }

  return (
    <CityContainer>
      <Helmet>
        <title>{city.name} - Yerel Tanıtım</title>
        <meta name="description" content={city.metaDescription || city.description} />
        <meta name="keywords" content={`${city.name}, ${city.seoKeywords?.join(', ') || ''}, turizm, yerel mutfak, kültür`} />
      </Helmet>

      <HeroSection>
        <HeroContent>
          <CityName>{city.name}</CityName>
          <CityDescription>{city.description}</CityDescription>
          <CityStats>
            <StatItem>
              <StatValue>{city.districts?.length || 0}</StatValue>
              <StatLabel>İlçe</StatLabel>
            </StatItem>
            <StatItem>
              <StatValue>{city.population?.toLocaleString('tr-TR') || 'N/A'}</StatValue>
              <StatLabel>Nüfus</StatLabel>
            </StatItem>
            <StatItem>
              <StatValue>{city.area?.toLocaleString('tr-TR') || 'N/A'}</StatValue>
              <StatLabel>Alan (km²)</StatLabel>
            </StatItem>
            <StatItem>
              <StatValue>{city.establishedYear || 'N/A'}</StatValue>
              <StatLabel>Kuruluş Yılı</StatLabel>
            </StatItem>
          </CityStats>
        </HeroContent>
      </HeroSection>

      <ContentSection>
        <div className="container">
          <ContentGrid>
            <MainContent>
              <h2>Turistik Yerler</h2>
              {city.touristAttractions && city.touristAttractions.length > 0 ? (
                <AttractionsGrid>
                  {city.touristAttractions.map((attraction, index) => (
                    <AttractionCard key={index}>
                      <AttractionName>{attraction.name}</AttractionName>
                      <AttractionDescription>{attraction.description}</AttractionDescription>
                      <AttractionType>{attraction.type}</AttractionType>
                    </AttractionCard>
                  ))}
                </AttractionsGrid>
              ) : (
                <p>Bu şehir için turistik yer bilgisi bulunmamaktadır.</p>
              )}

              <h2>Yerel Mutfak</h2>
              {city.localCuisine && city.localCuisine.length > 0 ? (
                <CuisineGrid>
                  {city.localCuisine.map((dish, index) => (
                    <CuisineCard key={index}>
                      <CuisineName>{dish.name}</CuisineName>
                      <CuisineDescription>{dish.description}</CuisineDescription>
                    </CuisineCard>
                  ))}
                </CuisineGrid>
              ) : (
                <p>Bu şehir için yerel mutfak bilgisi bulunmamaktadır.</p>
              )}

              <h2>Özel Lezzetler</h2>
              {city.specialFlavors && city.specialFlavors.length > 0 ? (
                <CuisineGrid>
                  {city.specialFlavors.map((flavor, index) => (
                    <CuisineCard key={index}>
                      <CuisineName>{flavor.name}</CuisineName>
                      <CuisineDescription>{flavor.description}</CuisineDescription>
                    </CuisineCard>
                  ))}
                </CuisineGrid>
              ) : (
                <p>Bu şehir için özel lezzet bilgisi bulunmamaktadır.</p>
              )}
            </MainContent>

            <Sidebar>
              <h3>Hızlı Bilgiler</h3>
              <div className="card" style={{ padding: '1.5rem' }}>
                <p><strong>Nüfus:</strong> {city.population?.toLocaleString('tr-TR') || 'Bilinmiyor'}</p>
                <p><strong>Alan:</strong> {city.area?.toLocaleString('tr-TR') || 'Bilinmiyor'} km²</p>
                <p><strong>Kuruluş:</strong> {city.establishedYear || 'Bilinmiyor'}</p>
                <p><strong>İlçe Sayısı:</strong> {city.districts?.length || 0}</p>
              </div>

              <h3>İlçeler</h3>
              <div className="card" style={{ padding: '1.5rem' }}>
                {districts && districts.length > 0 ? (
                  <div>
                    {districts.slice(0, 5).map(district => (
                      <div key={district._id} style={{ marginBottom: '0.5rem' }}>
                        <Link to={`/sehir/${city.slug}/${district.slug}`}>
                          {district.name}
                        </Link>
                      </div>
                    ))}
                    {districts.length > 5 && (
                      <p style={{ marginTop: '1rem', fontSize: '0.9rem', color: '#666' }}>
                        ve {districts.length - 5} ilçe daha...
                      </p>
                    )}
                  </div>
                ) : (
                  <p>İlçe bilgisi bulunmamaktadır.</p>
                )}
              </div>
            </Sidebar>
          </ContentGrid>
        </div>
      </ContentSection>

      {districts && districts.length > 0 && (
        <DistrictsSection>
          <div className="container">
            <h2 className="text-center" style={{ marginBottom: '2rem' }}>İlçeler</h2>
            <DistrictsGrid>
              {districts.map(district => (
                <DistrictCard key={district._id} to={`/sehir/${city.slug}/${district.slug}`}>
                  <DistrictName>{district.name}</DistrictName>
                  <DistrictDescription>{district.description}</DistrictDescription>
                  <DistrictFeatures>
                    <FeatureItem>
                      <FaMapMarkerAlt />
                      {district.touristAttractions?.length || 0} Yer
                    </FeatureItem>
                    <FeatureItem>
                      <FaUtensils />
                      {district.localCuisine?.length || 0} Lezzet
                    </FeatureItem>
                  </DistrictFeatures>
                </DistrictCard>
              ))}
            </DistrictsGrid>
          </div>
        </DistrictsSection>
      )}
    </CityContainer>
  );
};

export default CityDetail;