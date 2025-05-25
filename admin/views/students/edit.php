<?php
ob_start();
?>

<div class="container-fluid">
    <div class="card dashboard-card">
        <div class="card-body">
            <h2 class="h3 mb-4">Edit Student</h2>
              <form action="index.php?page=students&action=update&id=<?php echo htmlspecialchars($student['id']); ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="nick_name" class="form-label">Nick Name</label>
                        <input type="text" class="form-control" id="nick_name" name="nick_name"
                               value="<?php echo htmlspecialchars($student['nick_name']); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age"
                               value="<?php echo htmlspecialchars($student['age']); ?>" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="parent_name" class="form-label">Parent Name</label>
                        <input type="text" class="form-control" id="parent_name" name="parent_name"
                               value="<?php echo htmlspecialchars($student['parent_name']); ?>" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                               value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($student['address']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($student['notes']); ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index.php?action=students" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
