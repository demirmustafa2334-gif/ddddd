import React, { useState } from 'react';
import { Helmet } from 'react-helmet-async';
import { Link } from 'react-router-dom';
import styled from 'styled-components';
import { useQuery } from 'react-query';
import { FaSearch, FaFilter, FaCalendarAlt, FaUser, FaEye, FaArrowRight } from 'react-icons/fa';
import { api } from '../utils/api';

const BlogContainer = styled.div`
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

const BlogGrid = styled.div`
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem 1rem;
`;

const BlogCard = styled(Link)`
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: ${props => props.theme.shadows.small};
  text-decoration: none;
  color: inherit;
  transition: transform 0.3s ease, box-shadow 0.3s ease;

  &:hover {
    transform: translateY(-5px);
    box-shadow: ${props => props.theme.shadows.medium};
  }
`;

const BlogImage = styled.div`
  height: 200px;
  background: linear-gradient(45deg, ${props => props.theme.colors.primary}, ${props => props.theme.colors.secondary});
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 2rem;
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

const BlogContent = styled.div`
  padding: 1.5rem;
`;

const BlogCategory = styled.span`
  display: inline-block;
  background: ${props => props.theme.colors.accent};
  color: ${props => props.theme.colors.text};
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.8rem;
  margin-bottom: 1rem;
  font-weight: 500;
`;

const BlogTitle = styled.h3`
  font-size: 1.3rem;
  margin-bottom: 0.5rem;
  color: ${props => props.theme.colors.primary};
  font-weight: 600;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
`;

const BlogExcerpt = styled.p`
  color: ${props => props.theme.colors.textLight};
  font-size: 0.9rem;
  line-height: 1.6;
  margin-bottom: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
`;

const BlogMeta = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  color: ${props => props.theme.colors.textLight};
  margin-bottom: 1rem;
`;

const BlogAuthor = styled.div`
  display: flex;
  align-items: center;
  gap: 0.5rem;
`;

const BlogStats = styled.div`
  display: flex;
  align-items: center;
  gap: 1rem;
`;

const BlogLocation = styled.div`
  font-size: 0.8rem;
  color: ${props => props.theme.colors.primary};
  margin-bottom: 1rem;
  font-weight: 500;
`;

const Pagination = styled.div`
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 3rem;
`;

const PageButton = styled.button`
  padding: 0.5rem 1rem;
  border: 2px solid ${props => props.theme.colors.border};
  background: ${props => props.active ? props.theme.colors.primary : 'white'};
  color: ${props => props.active ? 'white' : props.theme.colors.text};
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s ease;

  &:hover {
    border-color: ${props => props.theme.colors.primary};
    background: ${props => props.active ? props.theme.colors.primary : props.theme.colors.backgroundLight};
  }

  &:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
`;

const LoadingCard = styled.div`
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: ${props => props.theme.shadows.small};
  height: 400px;
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

const Blog = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [currentPage, setCurrentPage] = useState(1);
  const limit = 9;

  const categories = [
    { id: 'all', name: 'Tümü' },
    { id: 'tourism', name: 'Turizm' },
    { id: 'cuisine', name: 'Mutfak' },
    { id: 'culture', name: 'Kültür' },
    { id: 'history', name: 'Tarih' },
    { id: 'nature', name: 'Doğa' },
    { id: 'events', name: 'Etkinlikler' },
    { id: 'travel_tips', name: 'Seyahat İpuçları' }
  ];

  const { data: blogData, isLoading, error } = useQuery(
    ['blog-posts', currentPage, selectedCategory, searchQuery],
    () => {
      const params = new URLSearchParams({
        page: currentPage,
        limit: limit.toString(),
        ...(selectedCategory !== 'all' && { category: selectedCategory }),
        ...(searchQuery && { search: searchQuery })
      });
      
      return api.get(`/blog?${params}`).then(res => res.data);
    },
    {
      keepPreviousData: true,
    }
  );

  const handleSearch = (e) => {
    e.preventDefault();
    setCurrentPage(1);
  };

  const handleCategoryChange = (category) => {
    setSelectedCategory(category);
    setCurrentPage(1);
  };

  const totalPages = blogData?.pagination?.pages || 1;

  return (
    <BlogContainer>
      <Helmet>
        <title>Blog - Yerel Tanıtım</title>
        <meta name="description" content="Türkiye'nin şehir ve ilçeleri hakkında detaylı blog yazıları. Turizm, kültür, mutfak ve daha fazlası." />
        <meta name="keywords" content="türkiye blog, turizm yazıları, şehir rehberi, kültür, mutfak" />
      </Helmet>

      <HeaderSection>
        <div className="container">
          <HeaderTitle>Blog</HeaderTitle>
          <HeaderSubtitle>
            Türkiye'nin şehir ve ilçeleri hakkında detaylı yazılar, 
            turizm rehberleri ve kültürel içerikler.
          </HeaderSubtitle>
        </div>
      </HeaderSection>

      <SearchSection>
        <SearchContainer>
          <SearchBox>
            <form onSubmit={handleSearch}>
              <SearchIcon />
              <SearchInput
                type="text"
                placeholder="Blog yazısı ara..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </form>
          </SearchBox>

          <FilterSection>
            {categories.map(category => (
              <FilterButton
                key={category.id}
                active={selectedCategory === category.id}
                onClick={() => handleCategoryChange(category.id)}
              >
                {category.name}
              </FilterButton>
            ))}
          </FilterSection>
        </SearchContainer>
      </SearchSection>

      <div className="container">
        {isLoading ? (
          <BlogGrid>
            {Array.from({ length: limit }).map((_, index) => (
              <LoadingCard key={index}>
                Yükleniyor...
              </LoadingCard>
            ))}
          </BlogGrid>
        ) : error ? (
          <EmptyState>
            <EmptyTitle>Bir hata oluştu</EmptyTitle>
            <EmptyDescription>
              Blog yazıları yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.
            </EmptyDescription>
          </EmptyState>
        ) : !blogData?.posts || blogData.posts.length === 0 ? (
          <EmptyState>
            <EmptyTitle>Blog yazısı bulunamadı</EmptyTitle>
            <EmptyDescription>
              Arama kriterlerinize uygun blog yazısı bulunamadı. Farklı arama terimleri deneyin.
            </EmptyDescription>
          </EmptyState>
        ) : (
          <>
            <BlogGrid>
              {blogData.posts.map(post => (
                <BlogCard key={post._id} to={`/blog/${post.slug}`}>
                  <BlogImage>
                    📝
                  </BlogImage>
                  <BlogContent>
                    <BlogCategory>{post.category}</BlogCategory>
                    <BlogTitle>{post.title}</BlogTitle>
                    <BlogExcerpt>{post.excerpt}</BlogExcerpt>
                    
                    {post.city && (
                      <BlogLocation>
                        📍 {post.city.name}
                        {post.district && ` - ${post.district.name}`}
                      </BlogLocation>
                    )}

                    <BlogMeta>
                      <BlogAuthor>
                        <FaUser />
                        {post.author?.firstName} {post.author?.lastName}
                      </BlogAuthor>
                      <BlogStats>
                        <div>
                          <FaCalendarAlt />
                          {new Date(post.publishedAt).toLocaleDateString('tr-TR')}
                        </div>
                        <div>
                          <FaEye />
                          {post.viewCount}
                        </div>
                      </BlogStats>
                    </BlogMeta>
                  </BlogContent>
                </BlogCard>
              ))}
            </BlogGrid>

            {totalPages > 1 && (
              <Pagination>
                <PageButton
                  onClick={() => setCurrentPage(prev => Math.max(prev - 1, 1))}
                  disabled={currentPage === 1}
                >
                  Önceki
                </PageButton>
                
                {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                  const page = i + 1;
                  return (
                    <PageButton
                      key={page}
                      active={currentPage === page}
                      onClick={() => setCurrentPage(page)}
                    >
                      {page}
                    </PageButton>
                  );
                })}
                
                <PageButton
                  onClick={() => setCurrentPage(prev => Math.min(prev + 1, totalPages))}
                  disabled={currentPage === totalPages}
                >
                  Sonraki
                </PageButton>
              </Pagination>
            )}
          </>
        )}
      </div>
    </BlogContainer>
  );
};

export default Blog;