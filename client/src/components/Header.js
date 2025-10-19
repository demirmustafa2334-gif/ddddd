import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import styled from 'styled-components';
import { FaBars, FaTimes, FaSearch, FaUser, FaSignInAlt } from 'react-icons/fa';
import { useQuery } from 'react-query';
import { api } from '../utils/api';

const HeaderContainer = styled.header`
  background: white;
  box-shadow: ${props => props.theme.shadows.small};
  position: sticky;
  top: 0;
  z-index: 1000;
`;

const HeaderContent = styled.div`
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 70px;
`;

const Logo = styled(Link)`
  font-family: ${props => props.theme.fonts.heading};
  font-size: 1.8rem;
  font-weight: 600;
  color: ${props => props.theme.colors.primary};
  text-decoration: none;
  display: flex;
  align-items: center;

  &:hover {
    color: ${props => props.theme.colors.secondary};
  }
`;

const Nav = styled.nav`
  display: flex;
  align-items: center;
  gap: 2rem;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    display: ${props => props.isOpen ? 'flex' : 'none'};
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    flex-direction: column;
    padding: 1rem;
    box-shadow: ${props => props.theme.shadows.medium};
    gap: 1rem;
  }
`;

const NavLink = styled(Link)`
  color: ${props => props.theme.colors.text};
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: all 0.3s ease;
  position: relative;

  &:hover {
    color: ${props => props.theme.colors.primary};
    background-color: ${props => props.theme.colors.backgroundLight};
  }

  &.active {
    color: ${props => props.theme.colors.primary};
    background-color: ${props => props.theme.colors.backgroundLight};
  }
`;

const SearchContainer = styled.div`
  position: relative;
  display: flex;
  align-items: center;
`;

const SearchInput = styled.input`
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  border: 2px solid ${props => props.theme.colors.border};
  border-radius: 25px;
  width: 250px;
  font-size: 0.9rem;
  transition: all 0.3s ease;

  &:focus {
    outline: none;
    border-color: ${props => props.theme.colors.primary};
    width: 300px;
  }

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    width: 200px;
    
    &:focus {
      width: 250px;
    }
  }
`;

const SearchIcon = styled(FaSearch)`
  position: absolute;
  left: 0.75rem;
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
`;

const MobileMenuButton = styled.button`
  display: none;
  background: none;
  border: none;
  font-size: 1.5rem;
  color: ${props => props.theme.colors.text};
  cursor: pointer;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    display: block;
  }
`;

const AuthButtons = styled.div`
  display: flex;
  align-items: center;
  gap: 1rem;

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    margin-top: 1rem;
  }
`;

const CitiesDropdown = styled.div`
  position: relative;
`;

const DropdownButton = styled.button`
  background: none;
  border: none;
  color: ${props => props.theme.colors.text};
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;

  &:hover {
    color: ${props => props.theme.colors.primary};
    background-color: ${props => props.theme.colors.backgroundLight};
  }
`;

const DropdownContent = styled.div`
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border-radius: 8px;
  box-shadow: ${props => props.theme.shadows.medium};
  min-width: 300px;
  max-height: 400px;
  overflow-y: auto;
  z-index: 1000;
  display: ${props => props.isOpen ? 'block' : 'none'};
`;

const DropdownGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 0.5rem;
  padding: 1rem;
`;

const CityLink = styled(Link)`
  display: block;
  padding: 0.5rem;
  color: ${props => props.theme.colors.text};
  text-decoration: none;
  border-radius: 4px;
  transition: all 0.3s ease;
  font-size: 0.9rem;

  &:hover {
    background-color: ${props => props.theme.colors.backgroundLight};
    color: ${props => props.theme.colors.primary};
  }
`;

const Header = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isCitiesDropdownOpen, setIsCitiesDropdownOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const location = useLocation();

  const { data: cities, isLoading: citiesLoading } = useQuery(
    'cities',
    () => api.get('/cities').then(res => res.data),
    {
      staleTime: 10 * 60 * 1000, // 10 minutes
    }
  );

  useEffect(() => {
    setIsMenuOpen(false);
  }, [location]);

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      // Implement search functionality
      console.log('Searching for:', searchQuery);
    }
  };

  return (
    <HeaderContainer>
      <HeaderContent>
        <Logo to="/">
          Yerel Tanıtım
        </Logo>

        <Nav isOpen={isMenuOpen}>
          <NavLink 
            to="/" 
            className={location.pathname === '/' ? 'active' : ''}
          >
            Ana Sayfa
          </NavLink>
          
          <CitiesDropdown>
            <DropdownButton 
              onMouseEnter={() => setIsCitiesDropdownOpen(true)}
              onMouseLeave={() => setIsCitiesDropdownOpen(false)}
            >
              Şehirler
            </DropdownButton>
            <DropdownContent isOpen={isCitiesDropdownOpen}>
              {cities && (
                <DropdownGrid>
                  {cities.map(city => (
                    <CityLink 
                      key={city._id} 
                      to={`/sehir/${city.slug}`}
                      onClick={() => setIsCitiesDropdownOpen(false)}
                    >
                      {city.name}
                    </CityLink>
                  ))}
                </DropdownGrid>
              )}
            </DropdownContent>
          </CitiesDropdown>

          <NavLink 
            to="/blog" 
            className={location.pathname.startsWith('/blog') ? 'active' : ''}
          >
            Blog
          </NavLink>
          
          <NavLink 
            to="/iletisim" 
            className={location.pathname === '/iletisim' ? 'active' : ''}
          >
            İletişim
          </NavLink>

          <SearchContainer>
            <form onSubmit={handleSearch}>
              <SearchIcon />
              <SearchInput
                type="text"
                placeholder="Şehir, ilçe veya yer ara..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </form>
          </SearchContainer>

          <AuthButtons>
            <Link to="/admin" className="btn btn-secondary">
              <FaUser /> Admin
            </Link>
          </AuthButtons>
        </Nav>

        <MobileMenuButton onClick={() => setIsMenuOpen(!isMenuOpen)}>
          {isMenuOpen ? <FaTimes /> : <FaBars />}
        </MobileMenuButton>
      </HeaderContent>
    </HeaderContainer>
  );
};

export default Header;