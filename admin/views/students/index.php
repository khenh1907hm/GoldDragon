<?php
ob_start();
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Student Management</h2>
        <form class="mb-3" method="get" action="index.php">
            <!-- <input type="hidden" name="page" value="students"> -->
            <input type="text" id="searchInput" placeholder="Tìm học sinh...">
        </form>        
        <div id="results"></div>
        <a href="index.php?page=students&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Student
        </a>
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

    <div class="card dashboard-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Nick Name</th>
                            <th>Age</th>
                            <th>Parent Name</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['id']); ?></td>
                                <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['nick_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['age']); ?></td>
                                <td><?php echo htmlspecialchars($student['parent_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td>
                                    <div class="btn-group" role="group">                                        <a href="index.php?page=students&action=edit&id=<?php echo $student['id']; ?>" 
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
                    </tbody>
                </table>
                <!-- Pagination -->
<?php if ($totalPages > 1): ?>
<nav aria-label="Student pagination">
    <ul class="pagination justify-content-center mt-4">
        <!-- Prev button -->
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=students&page_num=<?php echo $page - 1; ?>">Previous</a>
        </li>

        <!-- Page numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?page=students&page_num=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next button -->
        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="index.php?page=students&page_num=<?php echo $page + 1; ?>">Next</a>
        </li>
    </ul>
</nav>
<?php endif; ?>

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
document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value;

    if (keyword.length > 1) {
        fetch('ajax/search_students.php?keyword=' + encodeURIComponent(keyword))
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(student => {
                    html += `<p>${student.full_name}</p>`;
                });
                document.getElementById('results').innerHTML = html;
            });
    } else {
        document.getElementById('results').innerHTML = '';
    }
});
</script>