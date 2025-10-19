import React from 'react';
import { Helmet } from 'react-helmet-async';
import { Link } from 'react-router-dom';
import styled from 'styled-components';
import { FaHome, FaArrowLeft, FaSearch } from 'react-icons/fa';

const NotFoundContainer = styled.div`
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, ${props => props.theme.colors.primary} 0%, ${props => props.theme.colors.secondary} 100%);
  color: white;
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

const NotFoundContent = styled.div`
  position: relative;
  z-index: 1;
  max-width: 600px;
  padding: 0 1rem;
`;

const ErrorCode = styled.h1`
  font-size: 8rem;
  font-weight: 700;
  margin-bottom: 1rem;
  opacity: 0.8;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 6rem;
  }
`;

const ErrorTitle = styled.h2`
  font-size: 2.5rem;
  margin-bottom: 1rem;
  font-weight: 600;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    font-size: 2rem;
  }
`;

const ErrorDescription = styled.p`
  font-size: 1.2rem;
  margin-bottom: 2rem;
  opacity: 0.9;
  line-height: 1.6;
`;

const ActionButtons = styled.div`
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
`;

const ActionButton = styled(Link)`
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 2rem;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 600;
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.3);

  &:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
  }
`;

const SearchSuggestion = styled.div`
  margin-top: 3rem;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
`;

const SuggestionTitle = styled.h3`
  font-size: 1.5rem;
  margin-bottom: 1rem;
  font-weight: 600;
`;

const SuggestionList = styled.div`
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  justify-content: center;
`;

const SuggestionLink = styled(Link)`
  display: inline-block;
  padding: 0.5rem 1rem;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  text-decoration: none;
  border-radius: 20px;
  font-size: 0.9rem;
  transition: all 0.3s ease;

  &:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
  }
`;

const NotFound = () => {
  const popularPages = [
    { name: 'Ana Sayfa', path: '/' },
    { name: 'Şehirler', path: '/sehirler' },
    { name: 'Blog', path: '/blog' },
    { name: 'İletişim', path: '/iletisim' },
    { name: 'İstanbul', path: '/sehir/istanbul' },
    { name: 'Ankara', path: '/sehir/ankara' },
    { name: 'İzmir', path: '/sehir/izmir' },
    { name: 'Antalya', path: '/sehir/antalya' }
  ];

  return (
    <NotFoundContainer>
      <Helmet>
        <title>Sayfa Bulunamadı - 404 | Yerel Tanıtım</title>
        <meta name="description" content="Aradığınız sayfa bulunamadı. Ana sayfaya dönmek veya popüler sayfalarımızı ziyaret etmek için linkleri kullanabilirsiniz." />
        <meta name="robots" content="noindex, nofollow" />
      </Helmet>

      <NotFoundContent>
        <ErrorCode>404</ErrorCode>
        <ErrorTitle>Sayfa Bulunamadı</ErrorTitle>
        <ErrorDescription>
          Üzgünüz, aradığınız sayfa mevcut değil veya taşınmış olabilir. 
          Ana sayfaya dönmek veya aşağıdaki popüler sayfalarımızı 
          ziyaret edebilirsiniz.
        </ErrorDescription>

        <ActionButtons>
          <ActionButton to="/">
            <FaHome />
            Ana Sayfa
          </ActionButton>
          <ActionButton to="javascript:history.back()">
            <FaArrowLeft />
            Geri Dön
          </ActionButton>
          <ActionButton to="/sehirler">
            <FaSearch />
            Şehirleri Keşfet
          </ActionButton>
        </ActionButtons>

        <SearchSuggestion>
          <SuggestionTitle>Popüler Sayfalar</SuggestionTitle>
          <SuggestionList>
            {popularPages.map((page, index) => (
              <SuggestionLink key={index} to={page.path}>
                {page.name}
              </SuggestionLink>
            ))}
          </SuggestionList>
        </SearchSuggestion>
      </NotFoundContent>
    </NotFoundContainer>
  );
};

export default NotFound;