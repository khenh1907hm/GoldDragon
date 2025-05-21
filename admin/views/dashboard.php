<?php include_once 'layout.php'; ?>
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card card-gradient-primary">
            <div class="card-body">
                <h5 class="card-title">Total Posts</h5>
                <p class="card-text"><?php echo $totalPosts; ?></p>
                <a href="index.php?action=posts" class="btn">Manage Posts</a>
                <div class="icon-bubble">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card card-gradient-success">
            <div class="card-body">
                <h5 class="card-title">Menu Items</h5>
                <p class="card-text"><?php echo $totalMenus; ?></p>
                <a href="index.php?action=menus" class="btn">Manage Menus</a>
                <div class="icon-bubble">
                    <i class="fas fa-utensils"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card card-gradient-info">
            <div class="card-body">                <h5 class="card-title">Students</h5>
                <p class="card-text"><?php echo $totalStudents; ?></p>
                <a href="index.php?page=students" class="btn">Manage Students</a>
                <div class="icon-bubble">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card card-gradient-warning">
            <div class="card-body">
                <h5 class="card-title">Registrations</h5>
                <p class="card-text display-4"><?php echo $totalRegistrations; ?></p>
                <a href="index.php?action=registrations" class="btn btn-light">View Registrations</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Recent Posts
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($recentPosts as $post): ?>                    <a href="index.php?page=posts&action=edit&id=<?php echo $post['id']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <small><?php echo date('M d, Y', strtotime($post['created_at'])); ?></small>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Recent Registrations
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach ($recentRegistrations as $registration): ?>                    <a href="index.php?page=registrations&action=edit&id=<?php echo $registration['id']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?php echo htmlspecialchars($registration['student_name']); ?></h5>
                            <small><?php echo date('M d, Y', strtotime($registration['created_at'])); ?></small>
                        </div>
                        <p class="mb-1"><?php echo htmlspecialchars($registration['course']); ?></p>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
