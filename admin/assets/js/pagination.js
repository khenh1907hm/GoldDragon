/**
 * Pagination Handler
 * Handles AJAX pagination and search functionality
 */
class PaginationHandler {
    constructor(options = {}) {
        this.options = {
            container: '.pagination-container',
            tableBody: 'tbody',
            searchForm: '.search-form',
            filterForm: '.filter-form',
            loadingClass: 'pagination-loading',
            ...options
        };
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.setupSearch();
        this.setupFilters();
    }
    
    bindEvents() {
        // Handle pagination clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.pagination .page-link')) {
                e.preventDefault();
                this.handlePaginationClick(e.target.closest('.pagination .page-link'));
            }
        });
        
        // Handle search form submission
        const searchForm = document.querySelector(this.options.searchForm);
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSearch();
            });
        }
        
        // Handle filter changes
        const filterForm = document.querySelector(this.options.filterForm);
        if (filterForm) {
            filterForm.addEventListener('change', (e) => {
                if (e.target.name === 'status' || e.target.name === 'filter') {
                    this.handleFilter();
                }
            });
        }
    }
    
    setupSearch() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.handleSearch();
                }, 500); // Debounce search
            });
        }
    }
    
    setupFilters() {
        const filterSelects = document.querySelectorAll('select[name="status"], select[name="filter"]');
        filterSelects.forEach(select => {
            select.addEventListener('change', () => {
                this.handleFilter();
            });
        });
    }
    
    handlePaginationClick(link) {
        const href = link.getAttribute('href');
        if (href && href !== '#') {
            this.loadPage(href);
        }
    }
    
    handleSearch() {
        const searchForm = document.querySelector(this.options.searchForm);
        if (searchForm) {
            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);
            const currentUrl = new URL(window.location);
            
            // Update URL with search parameters
            currentUrl.search = params.toString();
            
            this.loadPage(currentUrl.toString());
        }
    }
    
    handleFilter() {
        const filterForm = document.querySelector(this.options.filterForm);
        if (filterForm) {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            const currentUrl = new URL(window.location);
            
            // Preserve search parameters
            const searchParams = new URLSearchParams(window.location.search);
            searchParams.forEach((value, key) => {
                if (key !== 'status' && key !== 'filter') {
                    params.set(key, value);
                }
            });
            
            currentUrl.search = params.toString();
            
            this.loadPage(currentUrl.toString());
        }
    }
    
    async loadPage(url) {
        try {
            this.showLoading();
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const html = await response.text();
                this.updateContent(html);
                this.updateURL(url);
            } else {
                console.error('Failed to load page:', response.status);
                this.hideLoading();
            }
        } catch (error) {
            console.error('Error loading page:', error);
            this.hideLoading();
        }
    }
    
    updateContent(html) {
        // Create a temporary container to parse the HTML
        const temp = document.createElement('div');
        temp.innerHTML = html;
        
        // Update table body
        const newTableBody = temp.querySelector(this.options.tableBody);
        const currentTableBody = document.querySelector(this.options.tableBody);
        if (newTableBody && currentTableBody) {
            currentTableBody.innerHTML = newTableBody.innerHTML;
        }
        
        // Update pagination
        const newPagination = temp.querySelector(this.options.container);
        const currentPagination = document.querySelector(this.options.container);
        if (newPagination && currentPagination) {
            currentPagination.innerHTML = newPagination.innerHTML;
        }
        
        // Update search input value
        const newSearchInput = temp.querySelector('input[name="search"]');
        const currentSearchInput = document.querySelector('input[name="search"]');
        if (newSearchInput && currentSearchInput) {
            currentSearchInput.value = newSearchInput.value;
        }
        
        // Update filter values
        const newFilters = temp.querySelectorAll('select[name="status"], select[name="filter"]');
        const currentFilters = document.querySelectorAll('select[name="status"], select[name="filter"]');
        newFilters.forEach((newFilter, index) => {
            if (currentFilters[index]) {
                currentFilters[index].value = newFilter.value;
            }
        });
        
        this.hideLoading();
        this.scrollToTop();
    }
    
    updateURL(url) {
        // Update browser URL without page reload
        window.history.pushState({}, '', url);
    }
    
    showLoading() {
        const container = document.querySelector(this.options.container);
        if (container) {
            container.classList.add(this.options.loadingClass);
        }
        
        // Show loading spinner
        this.showLoadingSpinner();
    }
    
    hideLoading() {
        const container = document.querySelector(this.options.container);
        if (container) {
            container.classList.remove(this.options.loadingClass);
        }
        
        // Hide loading spinner
        this.hideLoadingSpinner();
    }
    
    showLoadingSpinner() {
        let spinner = document.querySelector('.pagination-spinner');
        if (!spinner) {
            spinner = document.createElement('div');
            spinner.className = 'pagination-spinner position-fixed top-50 start-50 translate-middle';
            spinner.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            document.body.appendChild(spinner);
        }
        spinner.style.display = 'block';
    }
    
    hideLoadingSpinner() {
        const spinner = document.querySelector('.pagination-spinner');
        if (spinner) {
            spinner.style.display = 'none';
        }
    }
    
    scrollToTop() {
        // Smooth scroll to top of the page
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Utility method to refresh current page
    refresh() {
        this.loadPage(window.location.href);
    }
    
    // Utility method to go to specific page
    goToPage(page) {
        const url = new URL(window.location);
        url.searchParams.set('page_num', page);
        this.loadPage(url.toString());
    }
}

/**
 * Initialize pagination handlers
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize pagination for posts page
    if (document.querySelector('.pagination')) {
        new PaginationHandler({
            container: '.pagination-container',
            tableBody: 'tbody',
            searchForm: 'form[method="GET"]',
            filterForm: 'form[method="GET"]'
        });
    }
    
    // Add keyboard navigation for pagination
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            const prevLink = document.querySelector('.pagination .page-item:not(.disabled) .page-link[aria-label="Previous"]');
            if (prevLink) {
                prevLink.click();
            }
        } else if (e.key === 'ArrowRight') {
            const nextLink = document.querySelector('.pagination .page-item:not(.disabled) .page-link[aria-label="Next"]');
            if (nextLink) {
                nextLink.click();
            }
        }
    });
});

/**
 * Utility functions for pagination
 */
window.PaginationUtils = {
    // Format number with commas
    formatNumber: function(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
    
    // Get current page number from URL
    getCurrentPage: function() {
        const urlParams = new URLSearchParams(window.location.search);
        return parseInt(urlParams.get('page_num')) || 1;
    },
    
    // Get search term from URL
    getSearchTerm: function() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('search') || '';
    },
    
    // Get filter value from URL
    getFilterValue: function(filterName) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(filterName) || '';
    },
    
    // Build URL with parameters
    buildUrl: function(params = {}) {
        const url = new URL(window.location);
        Object.keys(params).forEach(key => {
            if (params[key] !== null && params[key] !== undefined) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        return url.toString();
    }
}; 