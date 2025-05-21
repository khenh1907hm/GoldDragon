<?php
$registrations = $registrationController->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>View Registrations</h2>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Parent Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrations as $registration): ?>
            <tr>
                <td><?php echo htmlspecialchars($registration['id']); ?></td>
                <td><?php echo htmlspecialchars($registration['student_name']); ?></td>
                <td><?php echo htmlspecialchars($registration['parent_name']); ?></td>
                <td><?php echo htmlspecialchars($registration['email']); ?></td>
                <td><?php echo htmlspecialchars($registration['phone']); ?></td>
                <td><?php echo htmlspecialchars($registration['course']); ?></td>
                <td>
                    <span class="badge bg-<?php echo $registration['status'] === 'pending' ? 'warning' : ($registration['status'] === 'approved' ? 'success' : 'danger'); ?>">
                        <?php echo ucfirst(htmlspecialchars($registration['status'])); ?>
                    </span>
                </td>
                <td><?php echo date('M d, Y', strtotime($registration['created_at'])); ?></td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Update Status
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <form action="index.php?action=registrations&op=update-status" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="dropdown-item">Approve</button>
                                </form>
                            </li>
                            <li>
                                <form action="index.php?action=registrations&op=update-status" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="dropdown-item">Reject</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <form action="index.php?action=registrations&op=delete" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
