<?php
$student = new Student();
$limit = 10; // Number of records per page
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$offset = ($page - 1) * $limit;

// Get search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get paginated students with search
$result = $student->getPaginated($limit, $offset, $search);
$students = $result ? $result->fetchAll(PDO::FETCH_ASSOC) : [];

// Get total number of students for pagination
$totalStudents = $student->count($search);
$totalPages = ceil($totalStudents / $limit);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Student Management</h2>
        <div class="d-flex gap-2">
            <!-- Search Form -->
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by name or phone..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="index.php?page=students&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Student
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div id="searchResults" class="alert alert-info" style="display: none;"></div>

    <div class="card dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Nick Name</th>
                            <th>Age</th>
                            <th>Parent Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <?php echo !empty($search) ? 'No students found matching your search.' : 'No students found.'; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['nick_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['age'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['parent_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['phone'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['address'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($student['notes'] ?? ''); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=students&action=edit&id=<?php echo $student['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete(<?php echo $student['id']; ?>)" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div id="paginationContainer">
                    <?php if ($totalPages > 1): ?>
                    <nav aria-label="Student pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Previous button -->
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="index.php?page=students&page_num=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Page numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="index.php?page=students&page_num=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next button -->
                            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="index.php?page=students&page_num=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this student?')) {
        window.location.href = 'index.php?page=students&action=delete&id=' + id;
    }
}

// Real-time search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const searchResults = document.getElementById('searchResults');
    const studentsTableBody = document.getElementById('studentsTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    let searchTimeout;

    function performSearch() {
        const searchTerm = searchInput.value.trim();
        
        // Show/hide clear button
        clearSearch.style.display = searchTerm ? 'block' : 'none';
        
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Set new timeout for search
        searchTimeout = setTimeout(() => {
            if (searchTerm.length > 0) {
                // Show loading state
                studentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2">Searching...</div>
                        </td>
                    </tr>
                `;

                fetch(`/RongVang/admin/ajax/search_students.php?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Search results:', data);
                        
                        if (data.error) {
                            throw new Error(data.message || 'Search failed');
                        }
                        
                        // Update table body
                        if (data.students && Array.isArray(data.students) && data.students.length > 0) {
                            let html = '';
                            data.students.forEach(student => {
                                html += `
                                    <tr>
                                        <td>${student.id || ''}</td>
                                        <td>${student.full_name || ''}</td>
                                        <td>${student.nick_name || ''}</td>
                                        <td>${student.age || ''}</td>
                                        <td>${student.parent_name || ''}</td>
                                        <td>${student.phone || ''}</td>
                                        <td>${student.address || ''}</td>
                                        <td>${student.notes || ''}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?page=students&action=edit&id=${student.id}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmDelete(${student.id})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            });
                            studentsTableBody.innerHTML = html;
                            searchResults.style.display = 'block';
                            searchResults.innerHTML = `Showing results for: "${searchTerm}" (${data.students.length} results found)`;
                            paginationContainer.style.display = 'none';
                        } else {
                            studentsTableBody.innerHTML = `
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        No students found matching your search.
                                    </td>
                                </tr>
                            `;
                            searchResults.style.display = 'none';
                            paginationContainer.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        studentsTableBody.innerHTML = `
                            <tr>
                                <td colspan="9" class="text-center py-4 text-danger">
                                    ${error.message || 'An error occurred while searching. Please try again.'}
                                </td>
                            </tr>
                        `;
                        searchResults.style.display = 'none';
                        paginationContainer.style.display = 'none';
                    });
            } else {
                // If search is empty, reload the page to show all students
                window.location.href = 'index.php?page=students';
            }
        }, 300); // 300ms delay to prevent too many requests
    }

    // Event listeners
    searchInput.addEventListener('input', performSearch);
    
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        clearSearch.style.display = 'none';
        window.location.href = 'index.php?page=students';
    });
});
</script> 