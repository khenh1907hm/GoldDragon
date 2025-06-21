<?php
// Student view logic
require_once __DIR__ . '/../../models/Student.php';
require_once __DIR__ . '/../../includes/Pagination.php';

$studentModel = new Student();

// Get current page and search query
$currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get paginated and filtered students
$result = $studentModel->getAll($currentPage, $search);
$students = $result['students'];
$totalPages = $result['totalPages'];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">Student Management</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Students</li>
                </ol>
            </nav>
        </div>
        <a href="index.php?page=students/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Student
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card dashboard-card">
        <div class="card-body">
            <!-- Search Form -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="index.php" class="d-flex">
                        <input type="hidden" name="page" value="students">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, parent, or phone..." value="<?php echo htmlspecialchars($search); ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <?php if (!empty($search)): ?>
                                <a href="index.php?page=students" class="btn btn-outline-danger" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Nickname</th>
                            <th>Age</th>
                            <th>Parent Name</th>
                            <th>Phone</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted mb-0">No students found.</p>
                                    <?php if(!empty($search)): ?>
                                        <p class="text-muted">Try a different search term.</p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['id']); ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['nick_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['age']); ?></td>
                                    <td><?php echo htmlspecialchars($student['parent_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="index.php?page=students/edit&id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?php echo $student['id']; ?>)" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Student pagination">
                    <ul class="pagination justify-content-center mt-4">
                        <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?page=students&page_num=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="index.php?page=students&page_num=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="index.php?page=students&page_num=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
        // Redirect to a delete handler, e.g., in index.php
        window.location.href = 'index.php?page=students/delete&id=' + id;
    }
}
</script>