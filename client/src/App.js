import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { QueryClient, QueryClientProvider } from 'react-query';
import { HelmetProvider } from 'react-helmet-async';
import styled, { ThemeProvider, createGlobalStyle } from 'styled-components';

// Components
import Header from './components/Header';
import Footer from './components/Footer';
import ScrollToTop from './components/ScrollToTop';

// Pages
import Home from './pages/Home';
import Cities from './pages/Cities';
import CityDetail from './pages/CityDetail';
import DistrictDetail from './pages/DistrictDetail';
import Blog from './pages/Blog';
import BlogPost from './pages/BlogPost';
import Contact from './pages/Contact';
import Admin from './pages/Admin';
import NotFound from './pages/NotFound';

// Theme
const theme = {
  colors: {
    primary: '#2c5530',
    secondary: '#8B4513',
    accent: '#FFD700',
    text: '#333',
    textLight: '#666',
    background: '#fff',
    backgroundLight: '#f8f9fa',
    border: '#e9ecef',
    success: '#28a745',
    warning: '#ffc107',
    danger: '#dc3545',
    info: '#17a2b8'
  },
  fonts: {
    primary: "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
    heading: "'Playfair Display', Georgia, serif"
  },
  breakpoints: {
    mobile: '768px',
    tablet: '1024px',
    desktop: '1200px'
  },
  shadows: {
    small: '0 2px 4px rgba(0,0,0,0.1)',
    medium: '0 4px 8px rgba(0,0,0,0.12)',
    large: '0 8px 16px rgba(0,0,0,0.15)'
  }
};

const GlobalStyle = createGlobalStyle`
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: ${props => props.theme.fonts.primary};
    line-height: 1.6;
    color: ${props => props.theme.colors.text};
    background-color: ${props => props.theme.colors.background};
  }

  h1, h2, h3, h4, h5, h6 {
    font-family: ${props => props.theme.fonts.heading};
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: 1rem;
  }

  a {
    color: ${props => props.theme.colors.primary};
    text-decoration: none;
    transition: color 0.3s ease;

    &:hover {
      color: ${props => props.theme.colors.secondary};
    }
  }

  img {
    max-width: 100%;
    height: auto;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
  }

  .btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;

    &.btn-primary {
      background-color: ${props => props.theme.colors.primary};
      color: white;

      &:hover {
        background-color: ${props => props.theme.colors.secondary};
        transform: translateY(-2px);
      }
    }

    &.btn-secondary {
      background-color: transparent;
      color: ${props => props.theme.colors.primary};
      border: 2px solid ${props => props.theme.colors.primary};

      &:hover {
        background-color: ${props => props.theme.colors.primary};
        color: white;
      }
    }

    &.btn-accent {
      background-color: ${props => props.theme.colors.accent};
      color: ${props => props.theme.colors.text};

      &:hover {
        background-color: ${props => props.theme.colors.secondary};
        color: white;
      }
    }
  }

  .card {
    background: white;
    border-radius: 8px;
    box-shadow: ${props => props.theme.shadows.small};
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;

    &:hover {
      transform: translateY(-4px);
      box-shadow: ${props => props.theme.shadows.medium};
    }
  }

  .section {
    padding: 4rem 0;

    &.section-light {
      background-color: ${props => props.theme.colors.backgroundLight};
    }
  }

  .text-center {
    text-align: center;
  }

  .mb-1 { margin-bottom: 0.5rem; }
  .mb-2 { margin-bottom: 1rem; }
  .mb-3 { margin-bottom: 1.5rem; }
  .mb-4 { margin-bottom: 2rem; }
  .mb-5 { margin-bottom: 3rem; }

  .mt-1 { margin-top: 0.5rem; }
  .mt-2 { margin-top: 1rem; }
  .mt-3 { margin-top: 1.5rem; }
  .mt-4 { margin-top: 2rem; }
  .mt-5 { margin-top: 3rem; }

  @media (max-width: ${props => props.theme.breakpoints.mobile}) {
    .container {
      padding: 0 0.5rem;
    }

    .section {
      padding: 2rem 0;
    }
  }
`;

const AppContainer = styled.div`
  min-height: 100vh;
  display: flex;
  flex-direction: column;
`;

const MainContent = styled.main`
  flex: 1;
`;

// Create a client
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      retry: 1,
      staleTime: 5 * 60 * 1000, // 5 minutes
    },
  },
});

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <ThemeProvider theme={theme}>
        <HelmetProvider>
          <GlobalStyle />
          <Router>
            <AppContainer>
              <Header />
              <MainContent>
                <ScrollToTop />
                <Routes>
                  <Route path="/" element={<Home />} />
                  <Route path="/sehirler" element={<Cities />} />
                  <Route path="/sehir/:slug" element={<CityDetail />} />
                  <Route path="/sehir/:citySlug/:districtSlug" element={<DistrictDetail />} />
                  <Route path="/blog" element={<Blog />} />
                  <Route path="/blog/:slug" element={<BlogPost />} />
                  <Route path="/iletisim" element={<Contact />} />
                  <Route path="/admin/*" element={<Admin />} />
                  <Route path="*" element={<NotFound />} />
                </Routes>
              </MainContent>
              <Footer />
            </AppContainer>
          </Router>
        </HelmetProvider>
      </ThemeProvider>
    </QueryClientProvider>
  );
}

export default App;