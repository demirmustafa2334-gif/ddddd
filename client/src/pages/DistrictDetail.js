import React from 'react';
import { Helmet } from 'react-helmet-async';
import { Link, useParams } from 'react-router-dom';
import styled from 'styled-components';
import { useQuery } from 'react-query';
import { FaMapMarkerAlt, FaUtensils, FaCamera, FaUsers, FaCalendarAlt, FaArrowRight, FaHome, FaCar } from 'react-icons/fa';
import { api } from '../utils/api';

const DistrictContainer = styled.div`
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

const Breadcrumb = styled.div`
  margin-bottom: 1rem;
  font-size: 0.9rem;
  opacity: 0.8;

  a {
    color: white;
    text-decoration: none;

    &:hover {
      text-decoration: underline;
    }
  }
`;

const DistrictName = styled.h1`
  font-size: 3rem;
  margin-bottom: 0.5rem;
  font-weight: 700;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2rem;
  }
`;

const CityName = styled.h2`
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  opacity: 0.9;
  font-weight: 400;
`;

const DistrictDescription = styled.p`
  font-size: 1.2rem;
  margin-bottom: 2rem;
  opacity: 0.9;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 1rem;
  }
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
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
  font-size: 1.2rem;
`;

const AttractionDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 0.5rem;
`;

const AttractionDetails = styled.div`
  font-size: 0.8rem;
  color: ${props => props.theme.colors.textLight};
  margin-top: 0.5rem;

  div {
    margin-bottom: 0.25rem;
  }
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
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
`;

const CuisineCard = styled.div`
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-2px);
  }
`;

const CuisineName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
`;

const CuisineDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
  margin-bottom: 0.5rem;
`;

const CuisineIngredients = styled.div`
  font-size: 0.8rem;
  color: ${props => props.theme.colors.textLight};
  margin-top: 0.5rem;

  strong {
    color: ${props => props.theme.colors.primary};
  }
`;

const CulturalGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
`;

const CulturalCard = styled.div`
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: ${props => props.theme.shadows.small};
  transition: transform 0.3s ease;

  &:hover {
    transform: translateY(-2px);
  }
`;

const CulturalTitle = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1.1rem;
`;

const CulturalDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
`;

const CulturalType = styled.span`
  display: inline-block;
  background: ${props => props.theme.colors.accent};
  color: ${props => props.theme.colors.text};
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.8rem;
  margin-top: 0.5rem;
`;

const AccommodationGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
`;

const AccommodationCard = styled.div`
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

const AccommodationName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1rem;
`;

const AccommodationType = styled.span`
  display: inline-block;
  background: ${props => props.theme.colors.primary};
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.8rem;
  margin-bottom: 0.5rem;
`;

const AccommodationDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.8rem;
  line-height: 1.4;
`;

const RelatedDistrictsSection = styled.section`
  padding: 4rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const RelatedDistrictsGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const RelatedDistrictCard = styled(Link)`
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

const RelatedDistrictName = styled.h4`
  color: ${props => props.theme.colors.primary};
  margin-bottom: 0.5rem;
  font-size: 1.2rem;
`;

const RelatedDistrictDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.5;
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

const DistrictDetail = () => {
  const { citySlug, districtSlug } = useParams();

  const { data: district, isLoading, error } = useQuery(
    ['district', citySlug, districtSlug],
    () => api.get(`/districts/${citySlug}/${districtSlug}`).then(res => res.data),
    {
      enabled: !!citySlug && !!districtSlug,
    }
  );

  if (isLoading) {
    return (
      <DistrictContainer>
        <LoadingState>İlçe bilgileri yükleniyor...</LoadingState>
      </DistrictContainer>
    );
  }

  if (error || !district) {
    return (
      <DistrictContainer>
        <ErrorState>
          İlçe bulunamadı veya bir hata oluştu.
        </ErrorState>
      </DistrictContainer>
    );
  }

  return (
    <DistrictContainer>
      <Helmet>
        <title>{district.name}, {district.city?.name} - Yerel Tanıtım</title>
        <meta name="description" content={district.metaDescription || district.description} />
        <meta name="keywords" content={`${district.name}, ${district.city?.name}, ${district.seoKeywords?.join(', ') || ''}, turizm, yerel mutfak, kültür`} />
      </Helmet>

      <HeroSection>
        <HeroContent>
          <Breadcrumb>
            <Link to="/">Ana Sayfa</Link> / 
            <Link to="/sehirler">Şehirler</Link> / 
            <Link to={`/sehir/${citySlug}`}>{district.city?.name}</Link> / 
            {district.name}
          </Breadcrumb>
          <DistrictName>{district.name}</DistrictName>
          <CityName>{district.city?.name} İli</CityName>
          <DistrictDescription>{district.description}</DistrictDescription>
        </HeroContent>
      </HeroSection>

      <ContentSection>
        <div className="container">
          <ContentGrid>
            <MainContent>
              <h2>Turistik Yerler</h2>
              {district.touristAttractions && district.touristAttractions.length > 0 ? (
                <AttractionsGrid>
                  {district.touristAttractions.map((attraction, index) => (
                    <AttractionCard key={index}>
                      <AttractionName>{attraction.name}</AttractionName>
                      <AttractionDescription>{attraction.description}</AttractionDescription>
                      <AttractionDetails>
                        {attraction.address && <div><strong>Adres:</strong> {attraction.address}</div>}
                        {attraction.openingHours && <div><strong>Çalışma Saatleri:</strong> {attraction.openingHours}</div>}
                        {attraction.entryFee && <div><strong>Giriş Ücreti:</strong> {attraction.entryFee}</div>}
                      </AttractionDetails>
                      <AttractionType>{attraction.type}</AttractionType>
                    </AttractionCard>
                  ))}
                </AttractionsGrid>
              ) : (
                <p>Bu ilçe için turistik yer bilgisi bulunmamaktadır.</p>
              )}

              <h2>Yerel Mutfak</h2>
              {district.localCuisine && district.localCuisine.length > 0 ? (
                <CuisineGrid>
                  {district.localCuisine.map((dish, index) => (
                    <CuisineCard key={index}>
                      <CuisineName>{dish.name}</CuisineName>
                      <CuisineDescription>{dish.description}</CuisineDescription>
                      {dish.ingredients && dish.ingredients.length > 0 && (
                        <CuisineIngredients>
                          <strong>Malzemeler:</strong> {dish.ingredients.join(', ')}
                        </CuisineIngredients>
                      )}
                      {dish.restaurantRecommendations && dish.restaurantRecommendations.length > 0 && (
                        <CuisineIngredients>
                          <strong>Önerilen Yerler:</strong> {dish.restaurantRecommendations.join(', ')}
                        </CuisineIngredients>
                      )}
                    </CuisineCard>
                  ))}
                </CuisineGrid>
              ) : (
                <p>Bu ilçe için yerel mutfak bilgisi bulunmamaktadır.</p>
              )}

              <h2>Özel Lezzetler</h2>
              {district.specialFlavors && district.specialFlavors.length > 0 ? (
                <CuisineGrid>
                  {district.specialFlavors.map((flavor, index) => (
                    <CuisineCard key={index}>
                      <CuisineName>{flavor.name}</CuisineName>
                      <CuisineDescription>{flavor.description}</CuisineDescription>
                      {flavor.whereToFind && flavor.whereToFind.length > 0 && (
                        <CuisineIngredients>
                          <strong>Nerede Bulunur:</strong> {flavor.whereToFind.join(', ')}
                        </CuisineIngredients>
                      )}
                    </CuisineCard>
                  ))}
                </CuisineGrid>
              ) : (
                <p>Bu ilçe için özel lezzet bilgisi bulunmamaktadır.</p>
              )}

              <h2>Kültürel Özellikler</h2>
              {district.culturalHighlights && district.culturalHighlights.length > 0 ? (
                <CulturalGrid>
                  {district.culturalHighlights.map((highlight, index) => (
                    <CulturalCard key={index}>
                      <CulturalTitle>{highlight.title}</CulturalTitle>
                      <CulturalDescription>{highlight.description}</CulturalDescription>
                      <CulturalType>{highlight.type}</CulturalType>
                    </CulturalCard>
                  ))}
                </CulturalGrid>
              ) : (
                <p>Bu ilçe için kültürel özellik bilgisi bulunmamaktadır.</p>
              )}

              <h2>Konaklama Seçenekleri</h2>
              {district.accommodation && district.accommodation.length > 0 ? (
                <AccommodationGrid>
                  {district.accommodation.map((place, index) => (
                    <AccommodationCard key={index}>
                      <AccommodationName>{place.name}</AccommodationName>
                      <AccommodationType>{place.type}</AccommodationType>
                      <AccommodationDescription>{place.description}</AccommodationDescription>
                      {place.priceRange && (
                        <div style={{ marginTop: '0.5rem', fontSize: '0.8rem', color: '#666' }}>
                          <strong>Fiyat Aralığı:</strong> {place.priceRange}
                        </div>
                      )}
                    </AccommodationCard>
                  ))}
                </AccommodationGrid>
              ) : (
                <p>Bu ilçe için konaklama bilgisi bulunmamaktadır.</p>
              )}

              <h2>Ulaşım</h2>
              {district.transportation && (
                <div className="card" style={{ padding: '1.5rem' }}>
                  {district.transportation.howToReach && (
                    <div style={{ marginBottom: '1rem' }}>
                      <h4 style={{ color: '#2c5530', marginBottom: '0.5rem' }}>Nasıl Gidilir</h4>
                      <p>{district.transportation.howToReach}</p>
                    </div>
                  )}
                  {district.transportation.localTransport && (
                    <div style={{ marginBottom: '1rem' }}>
                      <h4 style={{ color: '#2c5530', marginBottom: '0.5rem' }}>Yerel Ulaşım</h4>
                      <p>{district.transportation.localTransport}</p>
                    </div>
                  )}
                  {district.transportation.carRental && (
                    <div>
                      <h4 style={{ color: '#2c5530', marginBottom: '0.5rem' }}>Araç Kiralama</h4>
                      <p>{district.transportation.carRental}</p>
                    </div>
                  )}
                </div>
              )}
            </MainContent>

            <Sidebar>
              <h3>Hızlı Bilgiler</h3>
              <div className="card" style={{ padding: '1.5rem' }}>
                <p><strong>Nüfus:</strong> {district.population?.toLocaleString('tr-TR') || 'Bilinmiyor'}</p>
                <p><strong>Alan:</strong> {district.area?.toLocaleString('tr-TR') || 'Bilinmiyor'} km²</p>
                <p><strong>Şehir:</strong> {district.city?.name}</p>
              </div>

              <h3>İstatistikler</h3>
              <div className="card" style={{ padding: '1.5rem' }}>
                <p><strong>Turistik Yer:</strong> {district.touristAttractions?.length || 0}</p>
                <p><strong>Yerel Yemek:</strong> {district.localCuisine?.length || 0}</p>
                <p><strong>Özel Lezzet:</strong> {district.specialFlavors?.length || 0}</p>
                <p><strong>Kültürel Etkinlik:</strong> {district.culturalHighlights?.length || 0}</p>
              </div>
            </Sidebar>
          </ContentGrid>
        </div>
      </ContentSection>

      {district.relatedDistricts && district.relatedDistricts.length > 0 && (
        <RelatedDistrictsSection>
          <div className="container">
            <h2 className="text-center" style={{ marginBottom: '2rem' }}>
              Aynı Şehirdeki Diğer İlçeler
            </h2>
            <RelatedDistrictsGrid>
              {district.relatedDistricts.map(relatedDistrict => (
                <RelatedDistrictCard 
                  key={relatedDistrict._id} 
                  to={`/sehir/${citySlug}/${relatedDistrict.slug}`}
                >
                  <RelatedDistrictName>{relatedDistrict.name}</RelatedDistrictName>
                  <RelatedDistrictDescription>{relatedDistrict.description}</RelatedDistrictDescription>
                </RelatedDistrictCard>
              ))}
            </RelatedDistrictsGrid>
          </div>
        </RelatedDistrictsSection>
      )}
    </DistrictContainer>
  );
};

export default DistrictDetail;