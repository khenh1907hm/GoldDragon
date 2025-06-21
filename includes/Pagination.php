<?php

class Pagination {
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;
    private $offset;
    private $urlPattern;

    public function __construct($totalItems, $itemsPerPage = 10, $currentPage = 1, $urlPattern = '') {
        $this->totalItems = (int)$totalItems;
        $this->itemsPerPage = (int)$itemsPerPage;
        $this->currentPage = max(1, (int)$currentPage);
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        $this->offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $this->urlPattern = $urlPattern;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getLimit() {
        return $this->itemsPerPage;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getTotalPages() {
        return $this->totalPages;
    }

    public function getTotalItems() {
        return $this->totalItems;
    }

    public function hasNextPage() {
        return $this->currentPage < $this->totalPages;
    }

    public function hasPreviousPage() {
        return $this->currentPage > 1;
    }

    public function getNextPage() {
        return $this->hasNextPage() ? $this->currentPage + 1 : null;
    }

    public function getPreviousPage() {
        return $this->hasPreviousPage() ? $this->currentPage - 1 : null;
    }

    public function getPageNumbers($maxVisible = 5) {
        $pages = [];
        
        if ($this->totalPages <= $maxVisible) {
            // Hiển thị tất cả các trang nếu tổng số trang <= maxVisible
            for ($i = 1; $i <= $this->totalPages; $i++) {
                $pages[] = $i;
            }
        } else {
            // Logic hiển thị trang với ellipsis
            $start = max(1, $this->currentPage - floor($maxVisible / 2));
            $end = min($this->totalPages, $start + $maxVisible - 1);
            
            // Điều chỉnh start nếu end quá gần cuối
            if ($end - $start + 1 < $maxVisible) {
                $start = max(1, $end - $maxVisible + 1);
            }
            
            // Thêm trang đầu nếu cần
            if ($start > 1) {
                $pages[] = 1;
                if ($start > 2) {
                    $pages[] = '...';
                }
            }
            
            // Thêm các trang chính
            for ($i = $start; $i <= $end; $i++) {
                $pages[] = $i;
            }
            
            // Thêm trang cuối nếu cần
            if ($end < $this->totalPages) {
                if ($end < $this->totalPages - 1) {
                    $pages[] = '...';
                }
                $pages[] = $this->totalPages;
            }
        }
        
        return $pages;
    }

    public function render($options = []) {
        $defaults = [
            'showInfo' => true,
            'showFirstLast' => true,
            'showPrevNext' => true,
            'maxVisible' => 5,
            'class' => 'pagination',
            'size' => '', // 'sm', 'lg'
            'alignment' => 'center' // 'left', 'center', 'right'
        ];
        
        $options = array_merge($defaults, $options);
        
        if ($this->totalPages <= 1) {
            return '';
        }
        
        $html = '<nav aria-label="Pagination navigation">';
        
        // Thông tin phân trang
        if ($options['showInfo']) {
            $start = $this->offset + 1;
            $end = min($this->offset + $this->itemsPerPage, $this->totalItems);
            $html .= '<div class="d-flex justify-content-between align-items-center mb-3">';
            $html .= '<small class="text-muted">';
            $html .= "Showing {$start} to {$end} of {$this->totalItems} entries";
            $html .= '</small>';
            $html .= '</div>';
        }
        
        // Pagination controls
        $alignmentClass = 'justify-content-' . $options['alignment'];
        $sizeClass = $options['size'] ? 'pagination-' . $options['size'] : '';
        
        $html .= '<ul class="' . $options['class'] . ' ' . $sizeClass . ' ' . $alignmentClass . '">';
        
        // First page
        if ($options['showFirstLast'] && $this->currentPage > 1) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->buildUrl(1) . '" aria-label="First">';
            $html .= '<span aria-hidden="true">&laquo;&laquo;</span>';
            $html .= '</a></li>';
        }
        
        // Previous page
        if ($options['showPrevNext'] && $this->hasPreviousPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->buildUrl($this->getPreviousPage()) . '" aria-label="Previous">';
            $html .= '<span aria-hidden="true">&laquo;</span>';
            $html .= '</a></li>';
        }
        
        // Page numbers
        $pageNumbers = $this->getPageNumbers($options['maxVisible']);
        foreach ($pageNumbers as $page) {
            if ($page === '...') {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            } else {
                $isActive = $page == $this->currentPage;
                $html .= '<li class="page-item' . ($isActive ? ' active' : '') . '">';
                $html .= '<a class="page-link" href="' . $this->buildUrl($page) . '">' . $page . '</a>';
                $html .= '</li>';
            }
        }
        
        // Next page
        if ($options['showPrevNext'] && $this->hasNextPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->buildUrl($this->getNextPage()) . '" aria-label="Next">';
            $html .= '<span aria-hidden="true">&raquo;</span>';
            $html .= '</a></li>';
        }
        
        // Last page
        if ($options['showFirstLast'] && $this->currentPage < $this->totalPages) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->buildUrl($this->totalPages) . '" aria-label="Last">';
            $html .= '<span aria-hidden="true">&raquo;&raquo;</span>';
            $html .= '</a></li>';
        }
        
        $html .= '</ul></nav>';
        
        return $html;
    }

    private function buildUrl($page) {
        if (empty($this->urlPattern)) {
            // Tạo URL từ query string hiện tại
            $query = $_GET;
            $query['page_num'] = $page;
            return '?' . http_build_query($query);
        }
        
        return str_replace('{page}', $page, $this->urlPattern);
    }

    // Phương thức tĩnh để tạo pagination nhanh chóng
    public static function create($totalItems, $itemsPerPage = 10, $currentPage = 1, $urlPattern = '') {
        return new self($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    }
} 