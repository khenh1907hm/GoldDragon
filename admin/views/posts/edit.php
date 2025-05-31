<?php ob_start(); ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">Edit Post</h2>
        <a href="index.php?page=posts" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Posts
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card dashboard-card">
        <div class="card-body">
            <form action="index.php?page=posts&action=update&id=<?php echo $post['id']; ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="<?php echo isset($old['title']) ? htmlspecialchars($old['title']) : htmlspecialchars($post['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea name="content" id="content" class="form-control" rows="8" required><?php 
                        echo isset($old['content']) ? $old['content'] : $post['content']; 
                    ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Post
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Include TinyMCE -->
<script src="https://cdn.tiny.cloud/1/do7d50kusl26wzfcvwj3fs3ses7hwtfmiwi0v9w11vogg7af/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
    selector: '#content',
    height: 300,
    menubar: false,
    plugins: [
      'advlist autolink lists link image charmap preview anchor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic backcolor | \
              alignleft aligncenter alignright alignjustify | \
              bullist numlist outdent indent | removeformat | help',
    content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }"
});
</script>
