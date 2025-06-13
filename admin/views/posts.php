<?php
require_once __DIR__ . '/../../models/Post.php';
$post = new Post();

// Get current page
$currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$currentPage = max(1, $currentPage); // Ensure page is at least 1

// Get posts for current page
$result = $post->getAll($currentPage);
$posts = $result->fetchAll(PDO::FETCH_ASSOC);

// Get total pages
$totalPages = $post->getTotalPages();

$operation = $_GET['op'] ?? '';
$postId = $_GET['id'] ?? null;

// Get current post for editing if in edit mode
$currentPost = null;
if ($operation === 'edit' && $postId) {
    $currentPost = $post->getById($postId);
}
?>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/w3orm81dimzbu62mq1tqrqs7av33lffsc6votwc1c1iopvs0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 400,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save(); // Save content to hidden input
            });
        }
    });
</script>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Manage Posts</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Posts</li>
                <?php if ($operation === 'new' || $operation === 'edit'): ?>
                    <li class="breadcrumb-item active"><?php echo ucfirst($operation); ?> Post</li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
    <?php if (!in_array($operation, ['new', 'edit'])): ?>
        <a href="index.php?page=posts&op=new" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Post
        </a>
    <?php endif; ?>
</div>

<?php if ($operation === 'new' || $operation === 'edit'): ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo $operation === 'edit' ? 'Edit Post' : 'Create New Post'; ?></h5>
            <a href="index.php?page=posts" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <form action="index.php?page=posts&op=<?php echo $operation === 'edit' ? 'update&id=' . $postId : 'store'; ?>" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="needs-validation" 
                  novalidate>
                
                <div class="mb-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" 
                           class="form-control" 
                           id="title" 
                           name="title" 
                           value="<?php echo $currentPost ? htmlspecialchars($currentPost['title'] ?? '') : ''; ?>"
                           required>
                    <div class="invalid-feedback">Please provide a title.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Content</label>
                    <textarea id="editor" name="content"><?php echo $currentPost ? ($currentPost['content'] ?? '') : ''; ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label">Featured Image</label>
                    <?php if ($currentPost && !empty($currentPost['image'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($currentPost['image']); ?>" 
                                 alt="Current featured image" 
                                 class="img-thumbnail" 
                                 style="max-height: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" 
                           class="form-control" 
                           id="image" 
                           name="image" 
                           accept="image/*">
                    <div class="form-text">Allowed formats: JPG, PNG, GIF. Max size: 2MB</div>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="published" <?php echo ($currentPost && ($currentPost['status'] ?? '') === 'published') ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo ($currentPost && ($currentPost['status'] ?? '') === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?page=posts'">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo $operation === 'edit' ? 'Update Post' : 'Create Post'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Posts List -->
    <div class="card">
        <div class="card-body">
            <!-- Filters and Search -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="searchPosts" class="form-control" placeholder="Search posts...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <select class="form-select d-inline-block w-auto">
                        <option value="all">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <!-- Posts Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Title</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 180px;">Created At</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">No posts found</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['id'] ?? ''); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($post['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                                                 alt="" 
                                                 class="rounded me-3" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($post['title'] ?? ''); ?></h6>
                                            <small class="text-muted">
                                                <?php echo substr(strip_tags($post['content'] ?? ''), 0, 50) . '...'; ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo ($post['status'] ?? 'draft') === 'published' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst(htmlspecialchars($post['status'] ?? 'draft')); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo date('M d, Y H:i', strtotime($post['created_at'] ?? 'now')); ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?page=posts&op=edit&id=<?php echo $post['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success"
                                                onclick="toggleStatus(<?php echo $post['id']; ?>)">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="deletePost(<?php echo $post['id']; ?>)">
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
            <nav class="mt-4" aria-label="Posts navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=posts&page_num=<?php echo $currentPage - 1; ?>" tabindex="-1">Previous</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=posts&page_num=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=posts&page_num=<?php echo $currentPage + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="index.php?action=post_delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" id="deletePostId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Delete post confirmation
function deletePost(id) {
    document.getElementById('deletePostId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Toggle post status
function toggleStatus(id) {
    // Implement status toggle functionality
    console.log('Toggle status for post:', id);
}

// Search functionality
document.getElementById('searchPosts').addEventListener('keyup', function(e) {
    // Implement search functionality
    console.log('Search:', e.target.value);
});
</script>
