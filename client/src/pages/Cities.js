import React, { useState } from 'react';
import { Helmet } from 'react-helmet-async';
import { Link } from 'react-router-dom';
import styled from 'styled-components';
import { useQuery } from 'react-query';
import { FaSearch, FaMapMarkerAlt, FaFilter } from 'react-icons/fa';
import { api } from '../utils/api';

const CitiesContainer = styled.div`
  min-height: 100vh;
  padding: 2rem 0;
`;

const HeaderSection = styled.section`
  background: linear-gradient(135deg, ${props => props.theme.colors.primary} 0%, ${props => props.theme.colors.secondary} 100%);
  color: white;
  padding: 4rem 0;
  text-align: center;
`;

const HeaderTitle = styled.h1`
  font-size: 3rem;
  margin-bottom: 1rem;
  font-weight: 700;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2rem;
  }
`;

const HeaderSubtitle = styled.p`
  font-size: 1.2rem;
  opacity: 0.9;
  max-width: 600px;
  margin: 0 auto;
`;

const SearchSection = styled.section`
  padding: 2rem 0;
  background: ${props => props.theme.colors.backgroundLight};
`;

const SearchContainer = styled.div`
  max-width: 800px;
  margin: 0 auto;
  padding: 0 1rem;
`;

const SearchBox = styled.div`
  position: relative;
  margin-bottom: 2rem;
`;

const SearchInput = styled.input`
  width: 100%;
  padding: 1rem 1rem 1rem 3rem;
  border: 2px solid ${props => props.theme.colors.border};
  border-radius: 25px;
  font-size: 1.1rem;
  transition: all 0.3s ease;

  &:focus {
    outline: none;
    border-color: ${props => props.theme.colors.primary};
    box-shadow: 0 0 0 3px rgba(44, 85, 48, 0.1);
  }
`;

const SearchIcon = styled(FaSearch)`
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: ${props => props.theme.colors.textLight};
  font-size: 1.2rem;
`;

const FilterSection = styled.div`
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  justify-content: center;
`;

const FilterButton = styled.button`
  padding: 0.5rem 1rem;
  border: 2px solid ${props => props.theme.colors.border};
  background: ${props => props.active ? props.theme.colors.primary : 'white'};
  color: ${props => props.active ? 'white' : props.theme.colors.text};
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;

  &:hover {
    border-color: ${props => props.theme.colors.primary};
    background: ${props => props.active ? props.theme.colors.primary : props.theme.colors.backgroundLight};
  }
`;

const CitiesGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
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

const CityContent = styled.div`
  padding: 1.5rem;
`;

const CityName = styled.h3`
  font-size: 1.4rem;
  margin-bottom: 0.5rem;
  color: ${props => props.theme.colors.primary};
  font-weight: 600;
`;

const CityDescription = styled.p`
  color: ${props => props.theme.colors.textLight};
  line-height: 1.6;
  margin-bottom: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
`;

const CityStats = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.9rem;
  color: ${props => props.theme.colors.textLight};
`;

const DistrictCount = styled.span`
  display: flex;
  align-items: center;
  gap: 0.25rem;
`;

const LoadingCard = styled.div`
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: ${props => props.theme.shadows.small};
  height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: ${props => props.theme.colors.textLight};
  font-size: 1.1rem;
`;

const EmptyState = styled.div`
  text-align: center;
  padding: 4rem 2rem;
  color: ${props => props.theme.colors.textLight};
`;

const EmptyTitle = styled.h3`
  font-size: 1.5rem;
  margin-bottom: 1rem;
  color: ${props => props.theme.colors.text};
`;

const EmptyDescription = styled.p`
  font-size: 1.1rem;
  margin-bottom: 2rem;
`;

const Cities = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedRegion, setSelectedRegion] = useState('all');

  const { data: cities, isLoading, error } = useQuery(
    'cities',
    () => api.get('/cities').then(res => res.data),
    {
      staleTime: 10 * 60 * 1000,
    }
  );

  const regions = [
    { id: 'all', name: 'Tümü' },
    { id: 'marmara', name: 'Marmara' },
    { id: 'ege', name: 'Ege' },
    { id: 'akdeniz', name: 'Akdeniz' },
    { id: 'ic_anadolu', name: 'İç Anadolu' },
    { id: 'karadeniz', name: 'Karadeniz' },
    { id: 'dogu_anadolu', name: 'Doğu Anadolu' },
    { id: 'guneydogu_anadolu', name: 'Güneydoğu Anadolu' }
  ];

  const filteredCities = cities?.filter(city => {
    const matchesSearch = city.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         city.description.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesRegion = selectedRegion === 'all' || city.region === selectedRegion;
    return matchesSearch && matchesRegion;
  }) || [];

  return (
    <CitiesContainer>
      <Helmet>
        <title>Türkiye Şehirleri - Yerel Tanıtım</title>
        <meta name="description" content="Türkiye'nin tüm şehirlerini keşfedin. Her şehir hakkında detaylı bilgiler, turistik yerler ve yerel mutfak rehberi." />
        <meta name="keywords" content="türkiye şehirleri, şehir rehberi, turizm, yerel mutfak, kültür" />
      </Helmet>

      <HeaderSection>
        <div className="container">
          <HeaderTitle>Türkiye Şehirleri</HeaderTitle>
          <HeaderSubtitle>
            Ülkemizin 81 şehrini keşfedin. Her şehir hakkında detaylı bilgiler, 
            turistik yerler ve yerel mutfak rehberi.
          </HeaderSubtitle>
        </div>
      </HeaderSection>

      <SearchSection>
        <SearchContainer>
          <SearchBox>
            <SearchIcon />
            <SearchInput
              type="text"
              placeholder="Şehir ara..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </SearchBox>

          <FilterSection>
            {regions.map(region => (
              <FilterButton
                key={region.id}
                active={selectedRegion === region.id}
                onClick={() => setSelectedRegion(region.id)}
              >
                {region.name}
              </FilterButton>
            ))}
          </FilterSection>
        </SearchContainer>
      </SearchSection>

      <div className="container">
        {isLoading ? (
          <CitiesGrid>
            {Array.from({ length: 9 }).map((_, index) => (
              <LoadingCard key={index}>
                Yükleniyor...
              </LoadingCard>
            ))}
          </CitiesGrid>
        ) : error ? (
          <EmptyState>
            <EmptyTitle>Bir hata oluştu</EmptyTitle>
            <EmptyDescription>
              Şehirler yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.
            </EmptyDescription>
          </EmptyState>
        ) : filteredCities.length === 0 ? (
          <EmptyState>
            <EmptyTitle>Şehir bulunamadı</EmptyTitle>
            <EmptyDescription>
              Arama kriterlerinize uygun şehir bulunamadı. Farklı arama terimleri deneyin.
            </EmptyDescription>
          </EmptyState>
        ) : (
          <CitiesGrid>
            {filteredCities.map(city => (
              <CityCard key={city._id} to={`/sehir/${city.slug}`}>
                <CityImage>
                  <FaMapMarkerAlt />
                </CityImage>
                <CityContent>
                  <CityName>{city.name}</CityName>
                  <CityDescription>{city.description}</CityDescription>
                  <CityStats>
                    <DistrictCount>
                      <FaMapMarkerAlt />
                      {city.districts?.length || 0} İlçe
                    </DistrictCount>
                  </CityStats>
                </CityContent>
              </CityCard>
            ))}
          </CitiesGrid>
        )}
      </div>
    </CitiesContainer>
  );
};

export default Cities;